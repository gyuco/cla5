<?php

namespace lib\services;

use DOMTemplate\DOMTemplate;

abstract class DomComponent {
    
    public $template = null;
    
    public function __construct($template = null) {
        if (!$template) {
            $componentTmp = get_class($this);
            $componentTmp = explode('\\', $componentTmp);
            $component = $componentTmp[count($componentTmp)-1];

            $layout = LIB_PATH."/components/{$component}/{$component}.phtml";
            $layout = file_get_contents($layout);
        }
        else {
            $layout = $template;
        }

        $this->template = new DOMTemplate( $layout );
    }
    
    public function insertComponent($node, $component) {
        $component->content();
        $this->template->setValue($node, $component->template, true);
    }
    
    abstract public function content();
    
}