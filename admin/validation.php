<?php

//
// Settings validation
//

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();




function ziggeobbpress_validate($input) {
	$options = ziggeobbpress_get_plugin_options();

	if(isset($input['on_forum'])) {
		$options['on_forum'] = ziggeobbpress_zero_or_one($input['on_forum']);
	}
	else {
		$options['on_forum'] = 0;
	}

	if(isset($input['on_topic'])) {
		$options['on_topic'] = ziggeobbpress_zero_or_one($input['on_topic']);
	}
	else {
		$options['on_topic'] = 0;
	}

	if(isset($input['on_reply'])) {
		$options['on_reply'] = ziggeobbpress_zero_or_one($input['on_reply']);
	}
	else {
		$options['on_reply'] = 0;
	}

	if(isset($input['public_recorder'])) {
		$options['public_recorder'] = ziggeobbpress_zero_or_one($input['public_recorder']);
	}
	else {
		$options['public_recorder'] = 0;
	}

	if(isset($input['public_screen'])) {
		$options['public_screen'] = ziggeobbpress_zero_or_one($input['public_screen']);
	}
	else {
		$options['public_screen'] = 0;
	}

	return $options;
}

?>