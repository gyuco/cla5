<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace cla\auth\user;

/**
 * User interface.
 *
 * @author  Frederic G. Østby
 */

interface UserInterface
{
	public function generateActionToken();
	public function getActionToken();
	public function generateAccessToken();
	public function getAccessToken();
	public function activate();
	public function deactivate();
	public function isActivated();
	public function ban();
	public function unban();
	public function isBanned();
	public function save();
	public function delete();
}