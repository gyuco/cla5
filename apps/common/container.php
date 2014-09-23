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
    'plates' => function () {
        return new \app\services\PlatesService();
    }
            
);