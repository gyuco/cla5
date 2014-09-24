<?php

$mux = new \Pux\Mux;

$mux->get('/', ['\lib\controllers\Home','indexAction'], ['id' => 'home']);

return $mux;