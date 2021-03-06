<?php
/*
Plugin Name:       INXY test plugin
Description:       Simple test plugin according to requirements.
Author:            Alexander Yarosh
Author URI:        https://wpwebdev.pp.ua
Version:           0.0.1
License:           Attribution-NonCommercial 4.0 International (CC BY-NC 4.0)
License URI:       https://creativecommons.org/licenses/by-nc/4.0/legalcode
Text Domain:       inxytest
*/

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define('TESTPLUGIN_DIR', __DIR__);
define('TESTPLUGIN_URL', plugin_dir_url(__FILE__));

use TestPlugin\Plugin;

if (is_dir(TESTPLUGIN_DIR.'/vendor') && file_exists(TESTPLUGIN_DIR . '/vendor/autoload.php')) {
    require_once TESTPLUGIN_DIR . '/vendor/autoload.php';
}


$plugin = new Plugin();
$plugin->run();
