<?php
    
namespace app\models;

class User extends \ActiveRecord\Model implements \cla\auth\user\UserInterface {

	public function generateActionToken() {
        $this->action_token = $this->generateToken();
    }
	public function getActionToken() {
        return $this->action_token;
    }
	public function generateAccessToken() {
        $this->access_token = $this->generateToken();
    }
	public function getAccessToken() {
        return $this->access_token;
    }
	public function activate() {
        $this->activated = 1;
    }
	public function deactivate() {
        $this->activated = 0;
    }
	public function isActivated() {
        return $this->activated == 1;
    }
	public function ban() {
        $this->banned = 1;
    }
	public function unban() {
        $this->banned = 0;
    }
	public function isBanned() {
        return $this->banned == 1;
    }
    
	protected function generateToken() {
		return hash('sha256', \cla\services\UUID::v4() . $this->id . uniqid('token', true));
	}
}