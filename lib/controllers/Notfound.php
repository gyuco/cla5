<?php

namespace lib\controllers;

class Notfound {

    function __construct() {
    }

    public function index() {
        
        $engine = new \League\Plates\Engine(LIB_PATH.'/views', 'phtml');
        $template = new \League\Plates\Template($engine);
        $template->title = "Cla framework - not found";
        
        echo $template->render('default');

    }

}
