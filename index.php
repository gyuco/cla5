<?php

/*
 * Cla Framework - All you need is Cla
 * @author     Giuseppe Concas <giuseppe.concas@gmail.com>
 * @copyright  (c) Giuseppe Concas
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

//with trailing slash
define('CLA_PATH', __DIR__ . '/cla/');
define('APPLICATION_PATH', __DIR__ . '/app/');
define('CONFIG_PATH', APPLICATION_PATH."system/config/");
define('VENDOR_PATH', __DIR__ . '/vendor/');

require 'vendor/autoload.php';

$app = php_sapi_name() == "cli"?cla\CLIApplication::instance($argv):cla\HTMLApplication::instance();
$app->run();
