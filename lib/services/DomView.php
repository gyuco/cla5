<?php

namespace lib\services;

use cla\Config;
use DOMTemplate\DOMTemplate;

class DomView {
    
    public $template;
    
    public function __construct($template=null) {
        if(!is_null($template)) {
            $this->setTemplate($template);
        }
    }
    
    public function setTemplate($template) {       

        $file = LIB_PATH."templates/".$template;
        if (file_exists($file)) {
            $temp = file_get_contents($file);
            $template =  str_replace('xmlns="http://www.w3.org/1999/xhtml"','',$temp);
        } else {
            throw new \Exception("Template {$file} not found");
        }
 
        $this->template = new DOMTemplate( $template );

    }

    public function setValue($node, $value, $html=false) { 
        $this->template->setValue($node, $value, $html);
    }
    
    public function insertComponent($node, $component) {
        $component->content();
        $this->template->setValue($node, $component->template, true);
    }

    public function setTitle($title) {
        $this->template->setValue ('//title', $title);
    }

    public function appendMetaTag($metaTag, $value, $type='name') {
        $attr_enc[$type] = $metaTag;
        $attr_enc['content'] = $value;
        $this->template->appendHeadElement('meta',  $attr_enc);
    }

    public function assets($collection) {
        $assets = new Assets( Config::get('assets') );
        $assets->add( $collection );
        $assets->renderDom($this->template);
    }

    public function render() {
        echo (string)$this->template;
    }

}