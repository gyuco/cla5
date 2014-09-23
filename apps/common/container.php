<?php

return array(
    'request' => function () {
        return new \cla\Request();
    },
    'response' => function () {
        return new \cla\Response();
    },
    'gatekeeper' => function () {
        return new \cla\auth\Gatekeeper(
                new cla\Request(), 
                new cla\Response(), 
                cla\Session::instance(), 
                new \cla\auth\providers\UserProvider('User')
            );
    },
    'session' => function () {
        return cla\Session::instance();
    },
    'plates' => function () {
        return new \app\services\PlatesService();
    },
    'elasticService' => function () {
        return new \app\services\ElasticSearchService();
    }
            
);