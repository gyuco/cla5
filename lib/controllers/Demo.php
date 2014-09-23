<?php

namespace lib\controllers;

class Demo {

    private $request;
    private $response;
    
    public function __construct($request, $response) {
        $this->request = $request;
        $this->response = $response;
    }
    public function itemAction($id) {
        
        var_dump($this->request->server()->HTTP_HOST);
        return "Product $id";
        
    }

}