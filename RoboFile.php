<?php

define('CLA_PATH', __DIR__ . '/cla/');
define('APPLICATION_PATH', __DIR__ . '/app/');
define('CONFIG_PATH', APPLICATION_PATH."system/config/");
define('VENDOR_PATH', __DIR__ . '/vendor/');

class RoboFile extends \Robo\Tasks
{
    public static $setup = "Setup Cla application";
    public function setup()
    {

        $this->taskFileSystemStack()
             ->chmod(APPLICATION_PATH.'system/logs/', 777)
             ->chmod(APPLICATION_PATH.'system/cache/', 777)
             ->run();

    }
    
    public static $help = "List of commands";
    public function help() {
        $rClass = new ReflectionClass($this);
        $methods = $rClass->getMethods(ReflectionMethod::IS_PUBLIC);
            echo "==============================================".PHP_EOL;
            echo "=================CLA CONSOLE==================".PHP_EOL;
            echo "==============================================".PHP_EOL.PHP_EOL;
        foreach($methods as $method) {
            $methodName = $method->getName();
            echo $methodName.PHP_EOL.self::$$methodName.PHP_EOL;
            echo "==============================================".PHP_EOL;
        }
        exit;
    }
}