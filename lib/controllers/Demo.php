<?php

namespace lib\controllers;

class Demo {

    private $request;
    
    public function __construct(\cla\http\Request $request) {
        $this->request = $request;
    }
    public function itemAction($id) {

        var_dump( $this->request );
        return "Product $id";
        
    }

}