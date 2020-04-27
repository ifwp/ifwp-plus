<?php
/*
Author: Vidsoe
Author URI: https://vidsoe.com
Description: Improvements and Fixes for WordPress and third-party plugins.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network:
Plugin Name: IFWP+
Plugin URI: https://ifwp.plus
Text Domain: ifwp-plus
Version: 0.4.27.7
*/

defined('ABSPATH') or die("Hi there! I'm just a plugin, not much I can do when called directly.");

define('IFWP_PLUS_VERSION', '0.4.27.7');
define('IFWP_PLUS_FILE', __FILE__);
define('IFWP_PLUS_BASENAME', plugin_basename(IFWP_PLUS_FILE));
define('IFWP_PLUS_DIR', plugin_dir_path(IFWP_PLUS_FILE));
define('IFWP_PLUS_NAME', 'IFWP+');
define('IFWP_PLUS_SLUG', basename(IFWP_PLUS_BASENAME, '.php'));
define('IFWP_PLUS_URL', plugin_dir_url(IFWP_PLUS_FILE));

$ifwp_plus = [];
$ifwp_plus['classes'][] = IFWP_PLUS_DIR . 'core/class-ifwp-plus.php';
$ifwp_plus['classes'][] = IFWP_PLUS_DIR . 'core/class-ifwp-tab.php';
$ifwp_plus['functions'][] = IFWP_PLUS_DIR . 'core/functions.php';
$ifwp_plus['extensions'][] = IFWP_PLUS_DIR . 'core/ifwp-plus.php';
foreach(glob(IFWP_PLUS_DIR . 'more/*', GLOB_ONLYDIR) as $dir){
	$file = $dir . '/class-ifwp-' . basename($dir) . '.php';
    if(file_exists($file)){
        $ifwp_plus['classes'][] = $file;
    }
	$file = $dir . '/functions.php';
    if(file_exists($file)){
        $ifwp_plus['functions'][] = $file;
    }
    $file = $dir . '/' . basename($dir) . '.php';
    if(file_exists($file)){
        $ifwp_plus['extensions'][] = $file;
    }
}
foreach($ifwp_plus['classes'] as $file){
	require_once($file);
}
foreach($ifwp_plus['functions'] as $file){
	require_once($file);
}
foreach($ifwp_plus['extensions'] as $file){
	require_once($file);
}
