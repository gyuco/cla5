<?php

namespace cla;

use cla\Config;
use Tracy\Debugger;

class Cla {

    public function __construct() {
        Config::$env = filter_input(INPUT_SERVER, 'HTTP_HOST');
        if (Config::get('system.environment') != "PROD") {
            Debugger::enable(false);
        }
        else {
            error_reporting(0);
        }
    }
    
    public function run() {
        
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        $mux = require( sprintf(Config::get('system.routes_path'), Config::$env) );
        $route = $mux->dispatch( $uri );

        if ($route) {
            echo Dispatcher::execute($route);
        }
        else { 
            $this->respond404();
        }

    }
    
    private function respond404() {
        $response = new \cla\http\Response();
        $response->code(404);
        $response->send();
        $controller = new \lib\controllers\Notfound( new \lib\services\DomView() );
        $controller->index();
    }

}