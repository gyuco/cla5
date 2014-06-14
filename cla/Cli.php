<?php

namespace cla;

use cla\Config;

class Cli {

    public function __construct() {
    }
    
    public function run() {

        $c = getopt( 'c:e:' );
        
        if (count($c) == 0) {
            $this->help();
        }
        
        Config::$env = $c['e'];
        
        $config = Config::get('cli');
        $options = $config[$c['c']];

        $paramsTmp = explode(':', 'c:e:'.$options['opts']);
        $params = array_filter($paramsTmp);
        $mandatories = array_flip($params);
        $options_parsed = getopt( 'c:e:'.$options['opts'] );
        
        $diff = array_diff_key($mandatories, $options_parsed);

        if (count($diff) == 0) {
            $class = '\app\cli\\'.$options['class'];
            $obj = new $class( $options_parsed );

            $obj->{$options['method']}();
        }
        else {
            echo "Params error, usage:".PHP_EOL.$options['usage'].PHP_EOL;
        }
    }

    private function help() {
        $commands = Config::get('cli');
            echo "==============================================".PHP_EOL;
            echo "=================CLA CONSOLE==================".PHP_EOL;
            echo "==============================================".PHP_EOL.PHP_EOL;
        foreach($commands as $cmd=>$options) {
            echo $cmd.PHP_EOL.$options['describe'].PHP_EOL.$options['usage'].PHP_EOL;
            echo "==============================================".PHP_EOL;
        }
        exit;
    }
}