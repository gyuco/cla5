<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace cla\auth\providers;

/**
 * User provider interface.
 *
 * @author  Frederic G. Østby
 */

interface UserProviderInterface
{
	public function createUser($data);
	public function getByActionToken($token);
	public function getByAccessToken($token);
	public function getByEmailPassword($email,$password);
	public function getById($id);
}