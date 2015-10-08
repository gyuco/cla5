<?php

/*
 * Cla Framework - All you need is Cla
 * @author     Giuseppe Concas <giuseppe.concas@gmail.com>
 * @copyright  (c) Giuseppe Concas
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

//with trailing slash
define('CLA_PATH', __DIR__ . '/cla/');
define('LIB_PATH', __DIR__ . '/lib/');
define('APPS_PATH', __DIR__ . '/apps/');
define('VENDOR_PATH', __DIR__ . '/vendor/');
define('ASSETS_PATH', __DIR__ . '/assets/');
define('SYSTEM_PATH', __DIR__ . '/system/');

require 'vendor/autoload.php';

$app = new cla\Cla();
$app->run();
