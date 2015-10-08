<?php

namespace cli;
use cla\Config;

class Gettext extends \Robo\Tasks {

    private $lang;
    private $locale_path;
    private $po, $mo;
    private $tmp_list = '/tmp/list';
    private $config;
    
    function  __construct() {
        $this->config = (object)Config::get('system');
        $this->_setAttrs();
    }

    public function createPo() {
        $this->taskExec("find ".LIB_PATH." -type f \( -name '*.php' -or -name '*.phtml' \)  -print > ".$this->tmp_list)
            ->run();
        $arg = file_exists($this->locale_path.$this->po)?'-j':'-o';
        $this->taskExec("cd {$this->locale_path} && xgettext --files-from={$this->tmp_list} --package-version=1.0 --package-name=robo --language=PHP --keyword=ngettext:1,2 {$arg} {$this->po}")
            ->run();
        $this->say('DONE!');
    }

    public function createMo() {
        $poparser = new \Sepia\PoParser();
        $entries = $poparser->parse( $this->locale_path.$this->po );

        $i = 1;
        $tot = count($entries);
        foreach($entries as $entry) {
            if (!strlen($entry['msgstr'][0])) {
                $key = $entry['msgid'][0];
                $value = $this->ask('Translate ('.$i.'/'.$tot.'): "'.$key.'":');
                $poparser->updateEntry( $key, $value );
                $this->say('OK '.$value);
            }
            $i++;
        }
        $date = new \DateTime();
        $now = $date->format('Y-m-d H:iO');
        $headers = $poparser->getHeaders();
        $headers[3] = '"PO-Revision-Date: '.$now.'\n"';
        $headers[6] = '"Language: '.Config::get('system.default_language_short').'\n"';
        $poparser->setHeaders( $headers );
        $poparser->write( $this->locale_path.$this->po );
        //msgfmt -o lang.mo lang.po
        $this->taskExec("msgfmt -o")->arg($this->locale_path.$this->mo)->arg($this->locale_path.$this->po)->run();
        
        $this->taskExec("sudo service apache2 restart")->run();
        
        $this->say('DONE');
    }
    
    private function _setAttrs() {
        $this->lang = $this->config->default_language.'.utf8';
        $this->locale_path = sprintf( $this->config->gettext_path_lang, $this->lang);
        $this->po = $this->config->gettext_domain.'.po';
        $this->mo = $this->config->gettext_domain.'.mo';
    }
    
}
