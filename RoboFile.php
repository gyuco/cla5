<?php

define('CLA_PATH', __DIR__ . '/cla/');
define('LIB_PATH', __DIR__ . '/lib/');
define('APPS_PATH', __DIR__ . '/apps/');
define('VENDOR_PATH', __DIR__ . '/vendor/');
define('SYSTEM_PATH', __DIR__ . '/system/');

class RoboFile extends \Robo\Tasks
{
    private $env;
    
    public function __construct()
    {
        $this->yell('Cla robo');
        $this->env = $this->ask('Please, set environment: '.$this->getEnvs());
    }
    
    public static $setup = "Setup Cla application";
    public function setup()
    {
        $this->taskFileSystemStack()
             ->chmod(APPLICATION_PATH.'system/logs/', 777)
             ->chmod(APPLICATION_PATH.'system/cache/', 777)
             ->chmod(APPLICATION_PATH.'system/tmp/', 777)
             ->run();
        
        $this->pux();
    }
    
    public static $pux = "pux utilities";
    public function pux()
    {
        $mux_file = APPS_PATH.$this->env.'/mux.php';
        $routes_file = APPS_PATH.'common/routes.php';
        $routes_env_file = APPS_PATH.$this->env.'/routes.php';
        $tmp_pux = SYSTEM_PATH.'tmp/pux_'.$this->env.'.php';
        
        $tmpRoutes = file_get_contents($routes_file);
        $tmpRoutesEnv = str_replace( '<?php', '', file_get_contents($routes_env_file) );
        
        $return_string = 'return $mux;';
        $content = str_replace($return_string, $tmpRoutesEnv, $tmpRoutes);
        file_put_contents($tmp_pux, $content.$return_string);
        
        $pux_path = 'vendor/corneltek/pux/pux ';
        $this->taskExec('php '.$pux_path.' compile -o '.$mux_file.' '.$tmp_pux)
            ->run();
        
        $this->taskCleanDir(SYSTEM_PATH.'tmp')
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
    
    private function getEnvs() {
        $envsTmp = glob(APPS_PATH."*");
        $envs = array();
        foreach($envsTmp as $env) {
            $envs[] = str_replace(APPS_PATH, '', $env);
        }
        if(($key = array_search('common', $envs)) !== false) {
            unset($envs[$key]);
        }
        return implode(' | ', $envs);
    }
}