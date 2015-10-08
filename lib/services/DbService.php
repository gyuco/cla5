<?php

namespace lib\services;

use cla\Config as ClaConfig;
use ActiveRecord\Config;

final class DbService {
    
    static public function connect() {
        
        $config = ClaConfig::get('database');
        
        $host = $config['default']['db_host'];
        $user = $config['default']['db_user'];
        $pwd = $config['default']['db_pass'];
        
        $default_db = $config['db_name'];
        
        $cfg = Config::instance();
        $cfg->set_connections(
          array(
            'default' => "mysql://{$user}:{$pwd}@{$host}/{$default_db}?charset=utf8",
          )
        );

        $cfg->set_default_connection('default');
        
        //cache config
        $config_cache = ClaConfig::get('cache.Memcache');
        $host_m = $config_cache['servers']['server1']['server'];
        $cfg->set_cache("memcache://{$host_m}",array("expire" => $config_cache['expire']));
    }
    
}
