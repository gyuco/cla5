<?php

namespace lib\controllers;

class Home {

    function __construct() {}

    public function indexAction() {

        $engine = new \League\Plates\Engine(LIB_PATH.'/views', 'phtml');
        $template = new \League\Plates\Template($engine);
        $template->title = "Cla framework";
        
        echo $template->render('default');

    }

} 