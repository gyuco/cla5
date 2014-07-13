<?php

$mux = new \Pux\Mux;

$mux->get('/product/:id', ['\app\controllers\Demo','itemAction'], 
[
    'id' => 'product'
]);

$mux->get('/', ['\app\controllers\Home','indexAction'], 
[
    'id' => 'home'
]);

//$mux->get('/product/:id', ['\app\controllers\Demo','itemAction'], 
//    
//[
//    'require' => [ 'id' => '\d+', ],
//    'default' => [ 'id' => 1 ],
//    'id' => 'product'
//]
//        );

return $mux;

