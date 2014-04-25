<?php

namespace cla;

class Registry {

    private static $objects = array();
    private static $instance;

    private function __construct() {}
		
    public static function singleton() {
        if( !isset( self::$instance ) ) {
            $obj = __CLASS__;
            self::$instance = new $obj;
        }
        return self::$instance;
    }

    public function __clone() {
        trigger_error( 'Cloning not permitted', E_USER_ERROR );
    }
	
    public function set( $object, $key ) {
        self::$objects[ $key ] = $object;
    }

    public function get( $key ) {
        if( is_object ( self::$objects[ $key ] ) ) {
            return self::$objects[ $key ];
        }
    }

}

