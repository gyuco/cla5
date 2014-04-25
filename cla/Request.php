<?php

namespace cla;

class Request
{

    protected $id;

    protected $params_get;
    protected $params_post;
    protected $params_named;
    protected $cookies;
    protected $server;
    protected $headers;
    protected $files;
    protected $route;
    
    public function __construct($route) {
        
        $this->params_get   = new Collection($_GET);
        $this->params_post  = new Collection($_POST);
        $this->cookies      = new Collection($_COOKIE);
        $this->server       = new Collection($_SERVER);
        $this->files        = new Collection($_FILES);
        $this->route        = $route;
        $this->set_paramsNamed();
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

    public function set_paramsNamed()
    {
        if (isset($this->route[3]['vars'])) {
            foreach($this->route[3]['vars'] as $key=>$value) {
                if(!is_numeric($key)) {
                    $this->params_named[$key] = $value;
                }
            }
        }
    }
    
    public function paramsGet()
    {
        return $this->params_get;
    }

    public function paramsPost()
    {
        return $this->params_post;
    }

    public function paramsNamed()
    {
        return new Collection($this->params_named);
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

}