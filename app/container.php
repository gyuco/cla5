<?php

return array(
    'request' => function () {
            return new \cla\http\Request;
        },
    'response' => function () {
            return new \cla\http\Response();
        }
);