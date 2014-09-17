<?php

namespace cla\http;

class Request
{

    protected $id;

    protected $params_get;
    protected $params_post;
    protected $cookies;
    protected $server;
    protected $headers;
    protected $files;
    
    public function __construct() {
        
        $this->params_get   = (object)$_GET;
        $this->params_post  = (object)$_POST;
        $this->cookies      = (object)$_COOKIE;
        $this->server       = (object)$_SERVER;
        $this->files        = (object)$_FILES;
        $this->headers      = getallheaders();
    }

    public function id($hash = true)
    {
        if (null === $this->id) {
            $this->id = uniqid();

            if ($hash) {
                $this->id = sha1($this->id);
            }
        }

        return $this->id;
    }
    
    public function get()
    {
        return $this->params_get;
    }

    public function post()
    {
        return $this->params_post;
    }

    public function cookies()
    {
        return $this->cookies;
    }

    public function server()
    {
        return $this->server;
    }

    public function files()
    {
        return $this->files;
    }
    
    public function headers()
    {
        return $this->headers;
    }
    
    public function isSecure()
    {
        return ($this->server->HTTPS == true);
    }

    public function ip()
    {
        return $this->server->REMOTE_ADDR;
    }

    public function userAgent()
    {
        return $this->server->HTTP_USER_AGENT;
    }
    
    public function uri()
    {
        return $this->server->REQUEST_URI;
    }

    public function pathname()
    {
        $uri = $this->uri();

        return strstr($uri, '?', true) ?: $uri;
    }

    public function method()
    {
        return $this->server->REQUEST_METHOD;
    }
    
	public function cookie($name = null, $default = null)
	{
        if ( isset($this->cookies->$name) )
        {
            return $this->cookies->$name;
        }
		return $default;
	}


}