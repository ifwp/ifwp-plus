<?php
/*
Author: Vidsoe
Author URI: https://vidsoe.com
Description: Improvements and Fixes for WordPress + third-party plugins.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network:
Plugin Name: IFWP +
Plugin URI: https://ifwp.plus
Text Domain: ifwp-plus
Version: 0.4.14.2
*/

defined('ABSPATH') or die("Hi there! I'm just a plugin, not much I can do when called directly.");

define('IFWP_PLUS_VERSION', '0.4.14.2');
define('IFWP_PLUS_FILE', __FILE__);
define('IFWP_PLUS_BASENAME', plugin_basename(IFWP_PLUS_FILE));
define('IFWP_PLUS_DIR', plugin_dir_path(IFWP_PLUS_FILE));
define('IFWP_PLUS_NAME', 'IFWP +');
define('IFWP_PLUS_SLUG', basename(IFWP_PLUS_BASENAME, '.php'));
define('IFWP_PLUS_URL', plugin_dir_url(IFWP_PLUS_FILE));

require_once(IFWP_PLUS_DIR . 'core/_ifwp-plus.php');
require_once(IFWP_PLUS_DIR . 'core/_ifwp-tab.php');
require_once(IFWP_PLUS_DIR . 'core/functions.php');
require_once(IFWP_PLUS_DIR . 'core/ifwp-plus.php');

foreach(glob(LDC_AIO_DIR . 'more/*', GLOB_ONLYDIR) as $dir){
    $file = $dir . '/' . basename($dir) . '.php';
    if(file_exists($file)){
        require_once($file);
    }
    $file = $dir . '/functions.php';
    if(file_exists($file)){
        require_once($file);
    }
}
