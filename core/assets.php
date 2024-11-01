<?php

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

function ziggeobbpress_global() {

	//local assets
	wp_register_style('ziggeobbpress-css', ZIGGEOBBPRESS_ROOT_URL . 'assets/css/styles.css', array());    
	wp_enqueue_style('ziggeobbpress-css');

	wp_register_script('ziggeobbpress-js', ZIGGEOBBPRESS_ROOT_URL . 'assets/js/codes.js', array());
	wp_enqueue_script('ziggeobbpress-js');
}

add_action('wp_enqueue_scripts', "ziggeobbpress_global");
add_action('admin_enqueue_scripts', "ziggeobbpress_global");

?>