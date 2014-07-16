<?php

namespace cla;

class Session {

    protected static $instance;
    protected $flashdata = array();

    protected function __construct()
    {
    }

    public static function instance()
    {
        if(empty(static::$instance))
        {
            session_start();
            static::$instance = new Session();
        }

        return static::$instance;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
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
        return session_destroy();
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(static::instance(), $name), $arguments);
    }
}