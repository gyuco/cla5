<?php
/*
 * Cla Framework - All you need is Cla
 * @author     Giuseppe Concas <giuseppe.concas@gmail.com>
 * @copyright  (c) Giuseppe Concas
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

//with trailing slash
define('CLA_PATH', __DIR__ . '/cla/');
define('APPS_PATH', __DIR__ . '/app/');
define('CONFIG_PATH', APPS_PATH."system/config/");
define('VENDOR_PATH', __DIR__ . '/vendor/');

require_once 'vendor/autoload.php';

$hello_cmd = new Commando\Command();

$cla_version = \cla\Config::get('env.cla_version');

$hello_cmd->option('d')
    ->aka('demo')
    ->require()
    ->describedAs('A demo example');

echo "Hello {$hello_cmd['d']} -->Cla version {$cla_version}!", PHP_EOL;