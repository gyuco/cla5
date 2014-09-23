<?php

namespace app\services;

class Url {
    
    private $mux;
    
    public function __construct() {
        $this->mux = \cla\Registry::singleton()->get('mux');
    }
    
    public function getUrl($name, $params = array()) {
        $route = $this->mux->getRoute($name);
        if (!$route[0]) {
            $pattern = $route[1];
        }
        else {
            $pattern = $route[3]['pattern'];
            if(count($params)) {
                foreach ($params as $key=>$value) {
                    $pattern = str_replace(':'.$key, $value, $pattern);
                }
            }
        }
        return $pattern;
    }
    
    public static function factory() {
        return new static;
    }
}