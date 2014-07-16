<?php

namespace cla;

use Exception;
use ReflectionClass;

class Dispatcher
{
    /*
     * $route: {pcre flag}, {pattern}, {callback}, {options}
     */
    public static function execute($route)
    {
        list($pcre,$pattern,$cb,$options) = $route;

        $rc = new ReflectionClass( $cb[0] );

        $constructArgs = array();

        $services = Config::get('container');
        $args = $rc->getConstructor()->getParameters();
        if (count($args)) {
            foreach($args as $obj) {
                $constructArgs[$obj->getName()] = $services[$obj->getName()];
            }
            $controller = $rc->newInstanceArgs($constructArgs);
        } else {
            $controller = $rc->newInstance();
        }

        if( $controller && ! method_exists( $controller ,$cb[1]) ) {
            throw new Exception('Controller exception');
        }

        $rps = $rc->getMethod($cb[1])->getParameters();
        
        $method = $rc->getMethod($cb[1])->getName();
        
        $vars = isset($options['vars'])? $options['vars']: array();
        $arguments = array();
        foreach( $rps as $param ) {
            $n = $param->getName();
            if( isset( $vars[ $n ] ) ) {
                $arguments[] = $vars[ $n ];
            }
            else if( isset($route[3]['default'][ $n ] ) && $default = $route[3]['default'][ $n ] ) {
                $arguments[] = $default;
            }
            else if( ! $param->isOptional() && ! $param->allowsNull() ) {
                throw new Exception('parameter is not defined.');
            }
        }
        return call_user_func_array(array($controller, $method), $arguments);
    }
}
