<?php

$mux->get('/product/:id', ['\lib\controllers\Demo','itemAction'], 
[
    'id' => 'product'
]);
