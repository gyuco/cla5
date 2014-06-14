<?php

namespace cla;

use Pux\Executor;
use Pux\Dispatcher\APCDispatcher;
use cla\Config;
use Tracy\Debugger;

class Cla {

    public function __construct() {
        Config::$env = filter_input(INPUT_SERVER, 'HTTP_HOST');
        if (Config::get('env.environment') != "PROD") {
            Debugger::enable(false);
        }
        else {
            error_reporting(0);
        }
    }
    
    public function run() {
        $config = Config::get('pux');
        $mux = $this->getRouter();
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        
        $mux->sort();
        
        if ($config['apc']) {
            $dispatcher = new APCDispatcher($mux, array('namespace' => $config['apc_namespace'],'expiry' => $config['apc_expire']));
            $route = $dispatcher->dispatch( $uri );
        }
        else { $route = $mux->dispatch( $uri ); }

        if ($route) {
            $session = include VENDOR_PATH."/aura/session/scripts/instance.php";
            
            Registry::singleton()->set($session, 'session');
            Registry::singleton()->set($mux, 'mux');
            Registry::singleton()->set(new Request($route), 'request');
            Registry::singleton()->set(new Response(), 'response');
            
            \app\services\Localization::configure();
            
            echo Executor::execute($route);
        }
        else { $this->respond404(); }

    }
    
    private function respond404() {
        $response = new Response();
        $response->code(404);
        $response->send();
        $controller = new \app\controllers\Notfound();
        $controller->index();
    }
    
    private function getRouter() {
        $routes = Config::get('routes');
        
        $mux = new \Pux\Mux();
        
        foreach ($routes as $id=>$route) {
            $http = isset($route['http'])?$route['http']:'get';
            $mux->$http($route['pattern'], [$route['class'],$route['method']], ['id' => $id]);
        }
        
        return $mux;
    }
}