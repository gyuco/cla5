<?php

namespace app\controllers;

class Demo {

    private $request;
    
    public function __construct($request) {
        $this->request = $request;
        \app\models\DbConnect::connect();
    }
    public function itemAction($id) {

        return "Product $id";
        
    }

}