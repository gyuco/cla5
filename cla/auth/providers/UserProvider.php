<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace cla\auth\providers;

/**
 * User provider.
 *
 * @author  Frederic G. Østby
 */

class UserProvider implements \cla\auth\providers\UserProviderInterface
{
	/**
	 * Model.
	 * 
	 * @var string
	 */

	protected $model;

	/**
	 * Constructor.
	 * 
	 * @access  public
	 * @param   string  $model  Model class
	 */

	public function __construct($model)
	{
		$this->model = "\app\models\\".$model;
	}

	/**
	 * Creates and returns a user.
	 * 
	 * @access  public
	 * @param   array                         $data 
	 * @return  \cla\auth\user\UserInterface
	 */

	public function createUser($data)
	{

        $model = $this->model;

		$user = new $model;
        foreach($data as $key=>$value) {
            $user->$key = $value;
        }
		return $user;
	}

	/**
	 * Fetches a user by its action token.
	 * 
	 * @access  public
	 * @param   string                                 $token  Action token
	 * @return  \mako\auth\user\UserInterface|boolean
	 */

	public function getByActionToken($token)
	{
		$model = $this->model;
		return $model::find_by_action_token($token);
	}

	/**
	 * Fetches a user by its access token.
	 * 
	 * @access  public
	 * @param   string                                 $token  Access token
	 * @return  \mako\auth\user\UserInterface|boolean
	 */

	public function getByAccessToken($token)
	{
		$model = $this->model;
        return $model::find_by_access_token($token);
	}

	/**
	 * Fetches a user by its email address.
	 * 
	 * @access  public
	 * @param   string                                 $email  Email address
	 * @return  \mako\auth\user\UserInterface|boolean
	 */

	public function getByEmailPassword($email, $password)
	{
		$model = $this->model;
        return $model::find_by_email_and_password($email, $password);
	}

	/**
	 * Fetches a user by its id.
	 * 
	 * @access  public
	 * @param   string                                 $id  User id
	 * @return  \mako\auth\user\UserInterface|boolean
	 */

	public function getById($id)
	{
		$model = $this->model;

		return $model::find($id);
	}

}