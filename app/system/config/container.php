<?php

return array(
    'request' => new \cla\Request(),
    'response' => new \cla\Response(),
    'gatekeeper' => new \cla\auth\Gatekeeper(new cla\Request(), new cla\Response(), cla\Session::instance(), new \cla\auth\providers\UserProvider('User') )
);