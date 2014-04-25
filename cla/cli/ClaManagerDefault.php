<?php

namespace cla\cli;

use cla\Config;

class ClaManagerDefault {

    protected $argv;
    
    function  __construct($argv) {
        $this->argv = $argv;
        StaticConsole::help($this);

        if(isset($this->argv[1])) {
            $method = $this->argv[1];
            $this->$method();
        }
    }

    public static $create_models = "create models from database";
    public function create_models() {

        $config = Config::get('database');

        try {
            $this->openConnection();
            $query = 'SHOW TABLES FROM '.$config['mysql']['default']['db_name'];
            $rs = mysql_query($query);
        }
        catch(\Exception $e) {
            exit( $e->getMessage() );
        }
        //foreach ($tables as $table) {
        while($row = mysql_fetch_assoc($rs)) {

            $tbl_name = "Tables_in_".$config['mysql']['default']['db_name'];
            $model = \ActiveRecord\Utils::singularize($row[$tbl_name]);
            
            $content = 
"<?php
    
namespace app\models;
use \ActiveRecord\Model;

class ".ucfirst($model)." extends Model {
    
}

?>
";
            
            $filename = APPLICATION_PATH."/models/".ucfirst($model).".php";
            if (!file_exists($filename)) {
                file_put_contents($filename, $content);
            }
            
        }
        echo Console::format_msg("DONE!\n", "blue|bold")."\n";
    }
    
    public static $clean_html = "Clean html tags with tidy";
    public function clean_html() {

        echo Console::format_msg('Html file?', "red|bold")."\n->";
        $file = rtrim(Console::GetLine());
        if (file_exists($file)) {
            
            $config = array(
           'indent'         => true,
           'output-xhtml'   => true,
           'wrap'           => 200);
            
            $html = file_get_contents($file);
            $tidy = new \Tidy();
            $tidy->parseString($html, $config, 'utf8');
            $tidy->cleanRepair();

            file_put_contents($file, (string)$tidy);
            echo Console::format_msg("DONE!\n", "blue|bold")."\n";
        
        }
        else echo Console::format_msg("File not exists!\n", "red|bold")."\n";
        
    }

    public static $quit = "close application";
    public function quit() {
        StaticConsole::quit();
    }
    
    private function openConnection() {
        $config = Config::get('database');
        
        $conn = mysql_pconnect(
                $config['mysql']['default']['db_host'],
                $config['mysql']['default']['db_user'],
                $config['mysql']['default']['db_pass']
                );
        mysql_select_db( $config['mysql']['default']['db_name'] );
        return $conn;
    }
}



?>
