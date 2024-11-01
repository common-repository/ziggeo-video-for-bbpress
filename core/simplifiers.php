<?php

// Helps make some functions easier to execute.

// Index
//	1. Options
//		1.1. ziggeobbpress_get_plugin_options
//		1.2. ziggeobbpress_zero_or_one

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();


// Function that retrieves a specific option or all options together.
// It also uses a fallback system to provide defaults if needed.
function ziggeobbpress_get_plugin_options($specific = null) {
	$options = get_option('ziggeobbpress');

	//in case we need to get the defaults
	if($options === false || $options === '') {
		// the defaults need to be applied
		$options = array(
			'on_forum'			=> 1,
			'on_topic'			=> 1,
			'on_reply'			=> 1,
			'public_recorder'	=> 1,
			'public_screen'		=> 1
		);
	}

	// In case we are after a specific one.
	if($specific !== null) {
		if(isset($options[$specific])) {
			return $options[$specific];
		}
	}
	else {
		return $options;
	}

	return false;
}

// Helper function to return 0 or 1 as a value
function ziggeobbpress_zero_or_one($value) {
	if((int)$value === 1) {
		return 1;
	}
	else {
		return 0;
	}
}

?>