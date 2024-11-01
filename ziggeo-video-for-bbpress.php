<?php
/*
Plugin Name: Ziggeo Video for bbPress
Plugin URI: https://ziggeo.com/integrations/wordpress
Description: Add the Powerful Ziggeo video service platform to your bbPress forum
Author: Ziggeo
Version: 1.6.1
Author URI: https://ziggeo.com
*/

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();


//rooth path
define('ZIGGEOBBPRESS_ROOT_PATH', plugin_dir_path(__FILE__) );

//Setting up the URL so that we can get/built on it later on from the plugin root
define('ZIGGEOBBPRESS_ROOT_URL', plugins_url('', __FILE__) . '/');

//plugin version - this way other plugins can get it as well and we will be updating this file for each version change as is
define('ZIGGEOBBPRESS_VERSION', '1.6.1');

//Include files
include_once(ZIGGEOBBPRESS_ROOT_PATH . 'core/simplifiers.php');
include_once(ZIGGEOBBPRESS_ROOT_PATH . 'core/run.php');
//Add admin pages allowing us to select which hooks it would use (which segments would parse through Ziggeo)
include_once(ZIGGEOBBPRESS_ROOT_PATH . 'admin/dashboard.php');
include_once(ZIGGEOBBPRESS_ROOT_PATH . 'admin/plugins.php');
include_once(ZIGGEOBBPRESS_ROOT_PATH . 'admin/validation.php');
include_once(ZIGGEOBBPRESS_ROOT_PATH . 'core/assets.php');


?>