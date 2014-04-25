<?php

namespace app\controllers;

class Home extends \Pux\Controller {

    function __construct() {}

    public function indexAction() {

        $engine = new \League\Plates\Engine(APPLICATION_PATH.'/views', 'phtml');
        $template = new \League\Plates\Template($engine);
        $template->title = "Cla framework";
        
        echo $template->render('default');

    }

} 

?>
