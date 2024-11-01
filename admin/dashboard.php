<?php

//Mainly used for the purpose of selecting where Ziggeo would be used to show video or record it.

// Index
//	1. Hooks
//		1.1. admin_init
//		1.2. admin_menu
//	2. Fields and sections
//		2.1. ziggeobbpress_show_form()
//		2.2. ziggeobbpress_d_hooks()
//		2.3. ziggeobbpress_o_forum_content()
//		2.4. ziggeobbpress_o_topic_content()
//		2.5. ziggeobbpress_o_reply_content()

//Checking if WP is running or if this is a direct call..
defined('ABSPATH') or die();



/////////////////////////////////////////////////
//	1. HOOKS
/////////////////////////////////////////////////

	//Add plugin options
	add_action('admin_init', function() {
		//Register settings
		register_setting('ziggeobbpress', 'ziggeobbpress', 'ziggeobbpress_validate');

		//Active hooks
		add_settings_section('ziggeobbpress_section_hooks', '', 'ziggeobbpress_d_hooks', 'ziggeobbpress');


			// 
			add_settings_field('ziggeobbpress_forum_content',
			                    __('Use Ziggeo within forum description', 'ziggeobbpress'),
			                    'ziggeobbpress_o_forum_content',
			                    'ziggeobbpress',
			                    'ziggeobbpress_section_hooks');

			// 
			add_settings_field('ziggeobbpress_topic_content',
			                    __('Use Ziggeo within topic description', 'ziggeobbpress'),
			                    'ziggeobbpress_o_topic_content',
			                    'ziggeobbpress',
			                    'ziggeobbpress_section_hooks');

			// 
			add_settings_field('ziggeobbpress_reply_content',
			                    __('Use Ziggeo within reply description', 'ziggeobbpress'),
			                    'ziggeobbpress_o_reply_content',
			                    'ziggeobbpress',
			                    'ziggeobbpress_section_hooks');

			// 
			add_settings_field('ziggeobbpress_public_recorder',
			                    __('Show video recorder for public replies (by anyone seeing form)', 'ziggeobbpress'),
			                    'ziggeobbpress_o_public_recorder',
			                    'ziggeobbpress',
			                    'ziggeobbpress_section_hooks');

			// 
			add_settings_field('ziggeobbpress_public_screen',
			                    __('Show screen recorder for public replies (by anyone seeing form)', 'ziggeobbpress'),
			                    'ziggeobbpress_o_public_screen',
			                    'ziggeobbpress',
			                    'ziggeobbpress_section_hooks');
	});

	add_action('admin_menu', function() {
		if(function_exists('ziggeo_p_add_addon_submenu')) {
			ziggeo_p_add_addon_submenu(array(
				'page_title'    => 'Ziggeo Video for bbPress',      //page title
				'menu_title'    => 'Ziggeo Video for bbPress',      //menu title
				'capability'    => 'manage_options',                //min capability to view
				'slug'          => 'ziggeobbpress',                 //menu slug
				'callback'      => 'ziggeobbpress_show_form')       //function
			);
		}
		else {
			add_action( 'admin_notices', function() {
				?>
				<div class="error notice">
					<p><?php _e( 'Please install <a href="https://wordpress.org/plugins/ziggeo/">Ziggeo plugin</a>. It is required for this plugin (Ziggeo Video For bbPress) to work properly!', 'ziggeobbpress' ); ?></p>
				</div>
				<?php
			});
		}
	}, 12);




/////////////////////////////////////////////////
//	2. FIELDS AND SECTIONS
/////////////////////////////////////////////////

	//Dashboard form
	function ziggeobbpress_show_form() {
		?>
		<div>
			<h2>Ziggeo Video for bbPress</h2>

			<form action="options.php" method="post" class="ziggeobbpress_form">
				<?php
				wp_nonce_field('ziggeobbpress_nonce_action', 'ziggeobbpress_video_nonce');
				get_settings_errors();
				settings_fields('ziggeobbpress');
				do_settings_sections('ziggeobbpress');
				submit_button('Save Changes');
				?>
			</form>
		</div>
		<?php
	}

		function ziggeobbpress_d_hooks() {
			?>
			<h3><?php _e('Parse Locations', 'ziggeobbpress'); ?></h3>
			<?php
			_e('Use the settings bellow to select which area of bbPress should parse the Ziggeo templates or shortcodes.', 'ziggeobbpress');
		}

			function ziggeobbpress_o_forum_content() {
				$option = ziggeobbpress_get_plugin_options('on_forum');

				?>
				<input id="ziggeobbpress_on_forum" name="ziggeobbpress[on_forum]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeobbpress_on_forum"><?php _e('When checked your forum description will be processed for Ziggeo templates or shortcodes', 'ziggeobbpress'); ?></label>
				<?php
			}

			function ziggeobbpress_o_topic_content() {
				$option = ziggeobbpress_get_plugin_options('on_topic');

				?>
				<input id="ziggeobbpress_on_topic" name="ziggeobbpress[on_topic]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeobbpress_on_topic"><?php _e('When checked your topic description will be processed for Ziggeo templates or shortcodes', 'ziggeobbpress'); ?></label>
				<?php
			}

			function ziggeobbpress_o_reply_content() {
				$option = ziggeobbpress_get_plugin_options('on_reply');

				?>
				<input id="ziggeobbpress_on_reply" name="ziggeobbpress[on_reply]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeobbpress_on_reply"><?php _e('When checked your topic repies will be processed for Ziggeo templates or shortcodes', 'ziggeobbpress'); ?></label>
				<?php
			}

			function ziggeobbpress_o_public_recorder() {
				$option = ziggeobbpress_get_plugin_options('public_recorder');

				?>
				<input id="ziggeobbpress_public_recorder" name="ziggeobbpress[public_recorder]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeobbpress_public_recorder"><?php _e('When checked your toolbar above reply form gets recorder button allowing anyone to add video to their reply.', 'ziggeobbpress'); ?></label>
				<?php
			}

			function ziggeobbpress_o_public_screen() {
				$option = ziggeobbpress_get_plugin_options('public_screen');

				?>
				<input id="ziggeobbpress_public_screen" name="ziggeobbpress[public_screen]" size="50" type="checkbox" value="1"
					<?php echo checked( 1, $option, false ); ?> />
				<label for="ziggeobbpress_public_screen"><?php _e('When checked your toolbar above reply form gets screen recorder button allowing anyone to add video of their screen to their reply.', 'ziggeobbpress'); ?></label>
				<?php
			}


?>