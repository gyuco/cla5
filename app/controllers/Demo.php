<?php

namespace app\controllers;

class Demo {

    private $request;
    
    public function __construct(\cla\Request $request) {
        $this->request = $request;
        \app\models\DbConnect::connect();
    }
    public function itemAction($id) {

        var_dump( $this->request );
        return "Product $id";
        
    }

}