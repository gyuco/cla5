<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 * modified by Giuseppe Concas
 */

namespace cla\auth;

use \LogicException;

use \cla\auth\providers\UserProviderInterface;
use cla\Request;
use cla\Response;
use cla\Session;
/**
 * Gatekeeper authentication.
 *
 * @author  Frederic G. Østby
 */

class Gatekeeper
{
	/**
	 * Have we checked for a valid login?
	 * 
	 * @var boolean
	 */

	protected $isChecked = false;

	/**
	 * Status code for banned users.
	 * 
	 * @var int
	 */

	const LOGIN_BANNED = 100;

	/**
	 * Status code for users who need to activate their account.
	 * 
	 * @var int
	 */

	const LOGIN_ACTIVATING = 101;

	/**
	 * Status code for users who fail to provide the correct credentials.
	 * 
	 * @var int
	 */

	const LOGIN_INCORRECT = 102;

	/**
	 * Request instance.
	 * 
	 * @var \mako\http\Request
	 */

	protected $request;

	/**
	 * Response instance.
	 * 
	 * @var \mako\http\Response
	 */

	protected $response;

	/**
	 * Session instance.
	 * 
	 * @var \mako\session\Session
	 */

	protected $session;

	/**
	 * User provider.
	 * 
	 * @var \mako\auth\UserProviderInterface
	 */

	protected $userProvider;

	/**
	 * Auth key.
	 * 
	 * @var string
	 */

	protected $authKey = 'gatekeeper_auth_key';

	/**
	 * Cookie options.
	 * 
	 * @var array
	 */

	protected $cookieOptions = 
	[
		'path'     => '/',
		'domain'   => '',
		'secure'   => false,
		'httponly' => false,
	];

	/**
	 * User instance.
	 * 
	 * @var \mako\auth\user\User
	 */

	protected $user;

	/**
	 * Constructor.
	 * 
	 * @access  public
	 * @param   \mako\http\Request                           $request        Request instance
	 * @param   \mako\http\Response                          $response       Response instance
	 * @param   \mako\session\Session                        $session        Session instance
	 * @param   \mako\auth\providers\UserProviderInterface   $userProvider   User provider
	 * @param   \mako\auth\providers\GroupProviderInterface  $groupProvider  (optional) Group provider
	 */

	public function __construct(Request $request, Response $response, Session $session, UserProviderInterface $userProvider)
	{
		$this->request       = $request;
		$this->response      = $response;
		$this->session       = $session;
		$this->userProvider  = $userProvider;
	}

	/**
	 * Sets the auth key.
	 * 
	 * @access  public
	 * @param   string  $authKey  Auth key
	 */

	public function setAuthKey($authKey)
	{
		if($this->isChecked)
		{
			throw new LogicException(vsprintf("%s(): Unable to alter auth key after login check.", [__METHOD__]));
		}

		$this->authKey = $authKey;
	}

	/**
	 * Sets cookie options.
	 * 
	 * @access  public
	 * @param   array   $cookieOptions  Cookie options
	 */

	public function setCookieOptions(array $cookieOptions)
	{
		if($this->isChecked)
		{
			throw new LogicException(vsprintf("%s(): Unable to alter cookie options after login check.", [__METHOD__]));
		}

		$this->cookieOptions = $cookieOptions;
	}

	/**
	 * Returns the user provider instance.
	 * 
	 * @access  public
	 * @return  \mako\auth\providers\UserProviderInterface
	 */

	public function getUserProvider()
	{
		return $this->userProvider;
	}

	/**
	 * Creates a new user and returns the user object.
	 * 
	 * @access  public
	 * @param   string                         $email     Email address
	 * @param   string                         $username  Username
	 * @param   string                         $password  Password
	 * @param   boolean                        $activate  (optional) Will activate the user if set to true
	 * @return  \mako\auth\user\UserInterface
	 */

	public function createUser($data, $activate = false)
	{
		$user = $this->userProvider->createUser($data);

		$user->generateActionToken();

		$user->generateAccessToken();

		if($activate)
		{
			$user->activate();
		}

		$user->save();
		return $user;
	}


	/**
	 * Activates a user based on the provided auth token.
	 * 
	 * @access  public
	 * @param   string   $token  Auth token
	 * @return  boolean
	 */

	public function activateUser($token)
	{
		$user = $this->userProvider->getByActionToken($token);

		if($user === false)
		{
			return false;
		}
		else
		{
			$user->activate();

			$user->generateActionToken();

			$user->save();

			return $user;
		}
	}

	/**
	 * Checks if a user is logged in.
	 * 
	 * @access  protected
	 * @return  mako\auth\user\UserInterface|null
	 */

	protected function check()
	{
		if(empty($this->user))
		{
			// Check if there'a user that can be logged in

			$token = $this->session->get($this->authKey);

			if($token === null)
			{
				$token = $this->request->cookie($this->authKey, false);

				if($token !== null)
				{
					$this->session->set($this->authKey, $token);
				}
			}

			if($token !== null)
			{
				$user = $this->userProvider->getByAccessToken($token);

                if($user === null || $user->isBanned() || !$user->isActivated())
				{
					$this->logout();
				}
				else
				{
					$this->user = $user;
				}
			}

			// Set checked status to TRUE

			$this->isChecked = true;
		}

		return $this->user;
	}

	/**
	 * Returns FALSE if the user is logged in and TRUE if not.
	 * 
	 * @access  public
	 * @return  boolean
	 */

	public function isGuest()
	{
		return $this->check() === null;
	}

	/**
	 * Returns FALSE if the user isn't logged in and TRUE if it is.
	 * 
	 * @access  public
	 * @return  boolean
	 */

	public function isLoggedIn()
	{
		return $this->check() !== null;
	}

	/**
	 * Returns the authenticated user or NULL if no user is logged in.
	 * 
	 * @access  public
	 * @return  null|\mako\auth\user\UserInterface
	 */

	public function getUser()
	{
		return $this->check();
	}

	/**
	 * Returns TRUE if the email + password combination matches and the user is activated and not banned.
	 * A status code (LOGIN_ACTIVATING, LOGIN_BANNED or LOGIN_INCORRECT) will be retured in all other situations.
	 * 
	 * @access  protected
	 * @param   string       $email     User email
	 * @param   string       $password  User password
	 * @return  boolean|int
	 */

	protected function authenticate($email, $password)
	{
		$user = $this->userProvider->getByEmailPassword($email, $password);
        
		if($user !== false)
		{
			if(!$user->isActivated())
			{
				return static::LOGIN_ACTIVATING;
			}

			if($user->isBanned())
			{
				return static::LOGIN_BANNED;
			}

			$this->user = $user;

			return true;
		}

		return static::LOGIN_INCORRECT;
	}

	/**
	 * Logs in a user with a valid email/password combination.
	 * Returns TRUE if the email + password combination matches and the user is activated and not banned.
	 * A status code (LOGIN_ACTIVATING, LOGIN_BANNED or LOGIN_INCORRECT) will be retured in all other situations.
	 * 
	 * @access  public
	 * @param   string       $email     User email
	 * @param   string       $password  User password
	 * @param   boolean      $remember  (optional) Set a remember me cookie?
	 * @return  boolean|int
	 */

	public function login($email, $password, $remember = false)
	{
		if(empty($email))
		{
			return static::LOGIN_INCORRECT;
		}

		$authenticated = $this->authenticate($email, $password);

		if($authenticated === true)
		{
			$this->session->regenerate();

			$this->session->set($this->authKey, $this->user->getAccessToken());

			if($remember === true)
			{
				$this->response->cookie($this->authKey, $this->user->getAccessToken(), (3600 * 24 * 365), $this->cookieOptions);
			}

			return true;
		}

		return $authenticated;
	}

	/**
	 * Logs the user out.
	 * 
	 * @access  public
	 */

	public function logout()
	{
		$this->session->regenerate();

		$this->session->destroy();

		$this->response->deleteCookie($this->authKey, $this->cookieOptions);

		$this->user = null;
	}
}