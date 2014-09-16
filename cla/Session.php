<?php

namespace cla;

class Session {

    const SESSION_STARTED = true;
    const SESSION_NOT_STARTED = false;
    
    private $sessionState = self::SESSION_NOT_STARTED;
    
    protected static $instance;
    protected $flashdata = array();
    
    private function __construct() {}

    public static function instance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
        }
        
        self::$instance->startSession();
        
        return self::$instance;
    }

    public function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED )
        {
            $this->sessionState = session_start();
        }
        
        return $this->sessionState;
    }
    
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        if ( $this->exists($key) )
        {
            return $_SESSION[$key];
        }
        
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }
        
    public function flash($name) {
        if ( $this->exists($name) ) {
            $msg = $this->get($name);
            $this->delete($name);
            return $msg;
        }
        return null;
    }

    public function clear()
    {
        $_SESSION = array();
    }

    public function id()
    {
        return session_id();
    }

    public function regenerate($deleteOld = true)
    {
        return session_regenerate_id($deleteOld);
    }

    public function destroy()
    {
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();
            unset( $_SESSION );
            return !$this->sessionState;
        }
        
        return false;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(static::instance(), $name), $arguments);
    }
}