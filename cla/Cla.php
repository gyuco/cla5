<?php

namespace cla;

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
        
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        
        $mux = require(APPLICATION_PATH.'routes.php');
        $route = $mux->dispatch( $uri );

        if ($route) {
            echo Dispatcher::execute($route);
        }
        else { 
            $this->respond404();
        }

    }
    
    private function respond404() {
        \app\services\ResponseService::init()->send(404);
        $controller = new \app\controllers\Notfound( new \app\services\PlatesService() );
        $controller->index();
    }

}