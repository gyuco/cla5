<?php

namespace app\models;

use cla\Config as ClaConfig;
use ActiveRecord\Config;

final class DbConnect {
    
    static public function connect($name='default') {
        
        $config = ClaConfig::get('database');

        $host = $config[$name]['db_host'];
        $user = $config[$name]['db_user'];
        $pwd = $config[$name]['db_pass'];
        
        $default_db = $config[$name]['db_name'];

        $cfg = Config::instance();
        $cfg->set_connections(
          array(
            'default' => "mysql://{$user}:{$pwd}@{$host}/{$default_db}"
          )
        );

        $cfg->set_default_connection($name);
        
    }
    
}