<?php

$mux = new \Pux\Mux();
        
$mux->get('/get/:id/hello_:name', ['\app\controllers\Home','indexAction'] , [
    'id' => 'demo'
]);
$mux->get('/', ['\app\controllers\Home','indexAction'] , [
    'id' => 'home'
]);

$pageMux = new \Pux\Mux();
$pageMux->get('/page1', [ '\app\controllers\Home', 'indexAction' ]);
$pageMux->get('/page2', [ '\app\controllers\Home', 'indexAction' ]);

$mux->mount('/sub', $pageMux);

return $mux;