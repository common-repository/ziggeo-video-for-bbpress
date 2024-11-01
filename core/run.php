<?php

//
//	This file represents the integration module for bbPress and Ziggeo
//

// Index
//	1. Hooks
//		1.1. ziggeo_list_integration
//		1.2. plugins_loaded
//	2. Functionality
//		2.1. ziggeobbpress_get_version()
//		2.2. ziggeobbpress_init()
//		2.3. ziggeobbpress_run()

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();

//Show the entry in the integrations panel
add_filter('ziggeo_list_integration', function($integrations) {

	$current = array(
		//This section is related to the plugin that we are combining with the Ziggeo, not the plugin/module that does it
		'integration_title'		=> 'bbPress', //Name of the plugin
		'integration_origin'	=> 'https://bbpress.org/', //Where you can download it from

		//This section is related to the plugin or module that is making the connection between Ziggeo and the other plugin.
		'title'					=> 'Ziggeo Video for bbPress', //the name of the module
		'author'				=> 'Ziggeo', //the name of the author
		'author_url'			=> 'https://ziggeo.com/', //URL for author website
		'message'				=> 'Add video to forum topics and replies', //Any sort of message to show to customers
		'status'				=> true, //Is it turned on or off?
		'slug'					=> 'ziggeo-video-for-bbpress', //slug of the module
		//URL to image (not path). Can be of the original plugin, or the bridge
		'logo'					=> ZIGGEOBBPRESS_ROOT_URL . 'assets/images/logo.png',
		'version'				=> ZIGGEOBBPRESS_VERSION
	);

	//Check current Ziggeo version
	if(ziggeobbpress_run() === true) {
		$current['status'] = true;
	}
	else {
		$current['status'] = false;
	}

	$integrations[] = $current;

	return $integrations;
});

add_action('plugins_loaded', function() {
	ziggeobbpress_run();
});

//Checks if the bbPress exists and returns the version of it
function ziggeobbpress_get_version() {
	if(class_exists('bbPress')) {
		return bbp_get_version();
	}

	return 0;
}

//We add all of the hooks we need
function ziggeobbpress_init() {

	$options = ziggeobbpress_get_plugin_options();

	//If admin, lets make sure we show it there as well
	//Add Record Video button
	if(is_admin()) {
		//This runs the call similar to media_buttons, and creates our own buttons in the post editor.
		add_filter( 'edit_form_after_title', 'ziggeo_p_pre_editor' );
	}

	//Parse videos on?

	if($options['on_forum'] === 1) {
		//parse embeddings in forum
		add_filter('bbp_get_forum_content', 'ziggeo_p_content_filter');
	}

	if($options['on_topic'] === 1) {
		//parse embeddings in topic
		add_filter('bbp_get_topic_content', 'ziggeo_p_content_filter');
	}

	if($options['on_reply'] === 1) {
		//parse embeddings in replies
		add_filter('bbp_get_reply_content', 'ziggeo_p_content_filter');
	}


	if( (isset($options['public_recorder']) && (int)$options['public_recorder'] === 1) ||
		(isset($options['public_screen']) && (int)$options['public_screen'] === 1) ) {

		//This is all public side. If you want to add toolbar anywhere else, you can just include it as follows into your script and call ziggeobbpress_smalltoolbbar()
		include_once( ZIGGEOBBPRESS_ROOT_PATH . 'core/toolbar.php');

		//When replying on the topic
		add_action('bbp_theme_before_reply_form_content', 'ziggeobbpress_smalltoolbbar');
		//When creating topics
		add_action('bbp_theme_before_topic_form', 'ziggeobbpress_smalltoolbbar');
		//When form for creating forum is shown
		add_action('bbp_theme_before_forum_form', 'ziggeobbpress_smalltoolbbar');
	}

}

//Function that we use to run the module 
function ziggeobbpress_run() {

	//Needed during activation of the plugin
	if(!function_exists('ziggeo_get_version')) {
		add_action( 'admin_notices', function() {
			?>
			<div class="error notice">
				<p><?php _e( 'Please install <a href="https://wordpress.org/plugins/ziggeo/">Ziggeo plugin</a>. It is required for this plugin (Ziggeo Video For bbPress) to work properly!', 'ziggeobbpress' ); ?></p>
			</div>
			<?php
		});

		return false;
	}

	//Check current Ziggeo version
	if( version_compare(ziggeo_get_version(), '2.0') >= 0 &&
		//check the bbPress version
		version_compare(ziggeobbpress_get_version(), '2.5.12') >= 0) {

		if(ziggeo_integration_is_enabled('ziggeo-video-for-bbpress')) {
			ziggeobbpress_init();
			return true;
		}
	}

	return false;
}


?>