<?php

namespace lib\controllers;

class Notfound {

    private $view;
    
    function __construct($view) {
        $this->view = $view;
    }

    public function index() {
        
        $this->view->setTemplate('default.phtml');
        $this->view->assets('default');
        
        $this->view->setTitle('Cla - not found');
        $this->view->appendMetaTag('description', 'description not found');

        $this->view->setValue('#container', '404 - not found');
        
        $this->view->render();

    }

}
