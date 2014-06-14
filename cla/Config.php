<?php

/*
 * Config class.
 *
 * @author     Giuseppe Concas <giuseppe.concas@gmail.com>
 * @copyright  (c) Giuseppe Concas
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html

 * usage:         
 * echo Config::get('env.lang'); 
 * echo Config::get('env.environment'); 
 */

namespace cla;

class Config {
	
    protected static $config;
    public static $env;

    private static function load() {

        $files = glob(CONFIG_PATH.'*.php');

        foreach($files as $file)
        {
            preg_match('/(\w+).php$/', $file, $params);
            $key = $params[1];
            $config[$key] = include($file);
        }
        
        $path_env = CONFIG_PATH.'environments/'.self::$env.'/';
        $files_env = glob($path_env.'*.php');

        foreach($files_env as $key=>$path)
        {
            preg_match('/(\w+).php$/', $path, $params);
            $key = $params[1];
            $config[$key] = array_merge($config[$key], include($path));
        }
        
        static::$config = $config;
    }
    
    public static function getAll() {
        if (!isset(static::$config) ) {
            static::load();
        }
        return static::$config;
    }

    public static function get($key) {
    	
        if (!isset(static::$config) ) {
            static::load();
        }

        $keys = explode('.', $key, 2);
        
        if(isset($keys[1])) {
            if (!isset( static::$config[ $keys[0] ][ $keys[1] ] )) {
                throw new \Exception("Key {$key} not found");
            }
            return static::$config[ $keys[0] ][ $keys[1] ];
        }
        else {
            if (!isset( static::$config[ $keys[0] ] )) {
                throw new \Exception("Key {$key} not found");
            }
            return static::$config[ $keys[0] ];
        }
    }
    
}