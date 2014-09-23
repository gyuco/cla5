<?php

$mux = new \Pux\Mux;

$mux->get('/', ['\lib\controllers\Home','indexAction'], 
[
    'id' => 'home'
]);

include cla\Config::requireRoutesEnv();

return $mux;

