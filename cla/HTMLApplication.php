<?php

namespace cla;

use Pux\Executor;
use Pux\Dispatcher\APCDispatcher;
use cla\Config;
use Tracy\Debugger;

class HTMLApplication extends Application {

    public function __construct() {
        if (Config::get('env.environment') != "PROD") {
            Debugger::enable(false);
        }
        else {
            error_reporting(0);
        }
    }
    
    public function run() {

        $config = Config::get('pux');

        $mux = require APPLICATION_PATH.'system/routes.php';

        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        
        if ($config['apc']) {
            $dispatcher = new APCDispatcher($mux, array(
                'namespace' => $config['apc_namespace'],
                'expiry' => $config['apc_expire']
            ));
            $route = $dispatcher->dispatch( $uri );
        }
        else { $route = $mux->dispatch( $uri ); }

        if ($route) {
            Registry::singleton()->set($mux, 'mux');
            Registry::singleton()->set(new Request($route), 'request');
            Registry::singleton()->set(new Response(), 'response');
            
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
    
    public static function instance() {
        return new static();
    }
    
}