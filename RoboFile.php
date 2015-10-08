<?php

define('CLA_PATH', __DIR__ . '/cla/');
define('LIB_PATH', __DIR__ . '/lib/');
define('APPS_PATH', __DIR__ . '/apps/');
define('VENDOR_PATH', __DIR__ . '/vendor/');
define('SYSTEM_PATH', __DIR__ . '/system/');

use cla\Config;

class RoboFile extends \Robo\Tasks
{
    private $env;
    
    public function __construct()
    {
        $this->yell('Cla robo');
    }

    public function createdb()
    {
        $host = $this->ask('Database host?');
        $dbname = $this->ask('Database name?');
        $this->taskExec('mysql -uroot -h'.$host.' -p -e "CREATE DATABASE '.$dbname.'"')
            ->run();
        $this->yell('Database '.$dbname.' succesfully created!');
        
        $create_user = $this->ask('Do you want create a new database user? y | n');
        if($create_user == "y") {
            $dbuser = $this->ask('Database user?');
            $dbpwd = $this->ask('Database password?');
            $this->taskExec('mysql -uroot -h'.$host.' -p -e "CREATE USER \''.$dbuser.'\'@\''.$host.'\' IDENTIFIED BY \''.$dbpwd.'\';GRANT ALL PRIVILEGES ON '.$dbname.' . * TO \''.$dbuser.'\'@\''.$host.'\' identified by \''.$dbpwd.'\' with grant option;FLUSH PRIVILEGES;"')
            ->run();
        
            $write_config = $this->ask('Do you want write config file? y | n');
            if($write_config == "y") {
                $this->taskWriteToFile(APPS_PATH.'common/config/database.php')
                ->line('<?php')
                ->line('return array(')
                ->line("    'default'=> array(")
                ->line("        'db_host' => '{$host}',")
                ->line("        'db_user' => '{$dbuser}',")
                ->line("        'db_pass' => '{$dbpwd}',")
                ->line("        'db_name' => '{$dbname}',")
                ->line("        'memcache' => false,")
                ->line("    )")
                ->line(");")
                ->run();
            }
        }
        $this->yell('DONE!');
    }
    
    public function createvirtualhost()
    {
        $servername = $this->ask('Servername?');
        $document_root = $this->ask('Dcoument root?');
        
        $this->taskExec('apache2ctl -S')
        ->run();
        
        $virtualhost_path = '/etc/apache2/sites-available/';
        $virtualhost_conf_name = $this->ask('Virtual host .conf file name?');
        
        $template = file_get_contents('cli/templates/vh.conf.tpl');
        
        $ro_replace = array('{servername}', '{document_root}');
        $replaces = array($servername, $document_root);
        
        $content = str_replace($ro_replace, $replaces, $template);
        
        $virtualhost_conf_file = $virtualhost_path.$virtualhost_conf_name;
        $this->taskExecStack()
        ->stopOnFail()
        ->exec('echo "'.$content.'" > '.$virtualhost_conf_file)
        ->exec('cd '.$virtualhost_path)
        ->exec('a2ensite '.$virtualhost_conf_name)
        ->exec('service apache2 reload')
        ->run();
        
        $etc_hosts = $this->ask('Do you want add a line into /etc/hosts? y | n');
        if($etc_hosts == "y") {
            $this->taskExecStack()
            ->stopOnFail()
            ->exec('echo "127.0.0.1    '.$servername.'" >> /etc/hosts')
            ->run();
        }
        
    }
    
    public function compile_routes()
    {
        $this->env = $this->ask('Please, set environment: '.$this->getEnvs());
        $mux_file = APPS_PATH.$this->env.'/mux.php';
        $routes_file = APPS_PATH.'common/routes.php';
        $routes_env_file = APPS_PATH.$this->env.'/routes.php';
        $tmp_pux = SYSTEM_PATH.'tmp/pux_'.$this->env.'.php';
        
        $tmpRoutes = file_get_contents($routes_file);
        $tmpRoutesEnv = str_replace( '<?php', '', file_get_contents($routes_env_file) );
        
        $return_string = 'return $mux;';
        $content = str_replace($return_string, $tmpRoutesEnv, $tmpRoutes);
        file_put_contents($tmp_pux, $content.$return_string);
        
        $pux_path = 'vendor/corneltek/pux/pux ';
        $this->taskExec('php '.$pux_path.' compile -o '.$mux_file.' '.$tmp_pux)
            ->run();
        
        $this->taskCleanDir(SYSTEM_PATH.'tmp')
            ->run();
        
    }
    
    public function backupmysql() {
        Config::$env = 'cli';
        $location = Config::get('system.backup_dir');
        $mysql = Config::get('database.default');
        $db = Config::get('database.db_name');
 
        $today = Date('Y_m_d');
        $filename = $location.$db.'_'.$today.'.sql';
        $this->taskExec("mysqldump -u{$mysql['db_user']} -p{$mysql['db_pass']} {$db} > ".$filename)
            ->run();

        $this->taskExec("gzip ".$filename)
            ->run();

    }

    public function createpo($env) {
        Config::$env = $env;
        $gt = new \cli\Gettext();
        $gt->createPo();
    }
    
    public function createmo($env) {
        Config::$env = $env;
        $gt = new \cli\Gettext();
        $gt->createMo();
    }
    
    private function getEnvs() {
        $envsTmp = glob(APPS_PATH."*");
        $envs = array();
        foreach($envsTmp as $env) {
            $envs[] = str_replace(APPS_PATH, '', $env);
        }

        if(($key = array_search('common', $envs)) !== false ) {
            unset($envs[$key]);
        }
        return implode(' | ', $envs);
    }

}