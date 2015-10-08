<?php

namespace lib\controllers;

class Home {

    private $view;
    
    function __construct($view) {
        $this->view = $view;
    }

    public function indexAction() {

        $this->view->setTemplate('default.phtml');
        $this->view->assets('default');
        
        $this->view->setTitle('Cla');
        $this->view->appendMetaTag('description', _('description') );
        //component Logo
        $this->view->insertComponent('#container', new \lib\components\Logo\Logo());
        
        $this->view->render();

    }

} 