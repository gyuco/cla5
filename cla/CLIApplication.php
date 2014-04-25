<?php

namespace cla;

use cla\cli\StaticConsole;
use app\cli\ClaManager;

class CLIApplication extends Application {
    
    public function __construct($argv) {
        $this->argv = $argv;
    }
    
    public function run() {
        StaticConsole::Start(new ClaManager($this->argv));
    }
    
    public static function instance($argv) {
        return new static($argv);
    }
    
}
