<?php

return array(
    'request' => function () {
        return new cla\http\Request();
    },
    'response' => function () {
        return new \cla\http\Response();
    },
    'session' => function () {
        return cla\Session::instance();
    },
    'db' => function () {
        return \lib\services\DbService::connect();
    },
    'view' => function () {
        return new \lib\services\DomView();
    } 
);