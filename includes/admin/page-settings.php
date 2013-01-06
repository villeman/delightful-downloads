<?php
/**
 * @package Settings Page
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register settings page
 *
 * @return void
 */
function dedo_register_page_settings() {
	add_submenu_page( 'edit.php?post_type=dedo_download', 'Delightful Downloads ' . __( 'Settings', 'delightful-downloads' ), __( 'Settings', 'delightful-downloads' ), 'manage_options', 'dedo_settings', 'dedo_render_page_settings' );
}
add_action( 'admin_menu', 'dedo_register_page_settings' );

/**
 * Register settings API
 *
 * @return void
 */
function dedo_register_settings() {
	register_setting( 'dedo_settings', 'delightful-downloads', 'dedo_validate_settings' ); 
	
	// Form sections
	add_settings_section( 'dedo_settings_general', __( 'General', 'delightful-downloads' ), 'dedo_settings_general_section', __FILE__ );
	add_settings_section( 'dedo_settings_shortcodes', __( 'Shortcodes', 'delightful-downloads' ), 'dedo_settings_shortcodes_section', __FILE__ );
	add_settings_section( 'dedo_settings_uninstall', __( 'Uninstall', 'delightful-downloads' ), 'dedo_settings_uninstall_section', __FILE__ );
	
	// Form fields
	add_settings_field( 'members_only', __( 'Members Download', 'delightful-downloads' ), 'dedo_settings_members_only_field', __FILE__, 'dedo_settings_general' );
	add_settings_field( 'members_redirect', __( 'Non-Members Redirect', 'delightful-downloads' ), 'dedo_settings_members_redirect_field', __FILE__, 'dedo_settings_general' );
	add_settings_field( 'enable_css', __( 'Default CSS Styles', 'delightful-downloads' ), 'dedo_settings_enable_css_field', __FILE__, 'dedo_settings_general' );
	add_settings_field( 'default_text', __( 'Default Text', 'delightful-downloads' ), 'dedo_settings_default_text_field', __FILE__, 'dedo_settings_shortcodes' );
	add_settings_field( 'default_style', __( 'Default Style', 'delightful-downloads' ), 'dedo_settings_default_style_field', __FILE__, 'dedo_settings_shortcodes' );
	add_settings_field( 'default_color', __( 'Default Color', 'delightful-downloads' ), 'dedo_settings_default_color_field', __FILE__, 'dedo_settings_shortcodes' );
	add_settings_field( 'reset_settings', __( 'Reset Settings', 'delightful-downloads' ), 'dedo_settings_reset_settings_field', __FILE__, 'dedo_settings_uninstall' );
} 
add_action( 'admin_init', 'dedo_register_settings' );

/**
 * Validate settings callback
 *
 * @return void
 */
function dedo_validate_settings( $input ) {
	 $defaults = dedo_get_default_options();
	 
	 $parsed = wp_parse_args( $input, $defaults );
	 
	 // Fix empty default text textbox
	 if( trim( $input['default_text'] == '' ) ) {
		 $parsed['default_text'] = $defaults['default_text'];
	 }
	 
	 return $parsed;
}

/**
 * Render settings page
 *
 * @return void
 */
function dedo_render_page_settings() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br></div>
		<h2>Delightful Downloads <?php _e( 'Settings', 'delightful-downloads' ); ?></h2>
		<?php if ( isset( $_GET['settings-updated'] ) ) {
			echo '<div class="updated"><p>' . __( 'Settings updated successfully.', 'delightful-downloads' ) . '</p></div>';
		} ?>
		<form action="options.php" method="post">
			<?php 
				settings_fields( 'dedo_settings' ); 
				do_settings_sections( __FILE__ );
			?>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			</p>
		</form>
	</div>
	<?php
}

/**
 * Render general section
 *
 * @return void
 */
function dedo_settings_general_section() {
	return;
}

/**
 * Render shortcodes section
 *
 * @return void
 */
function dedo_settings_shortcodes_section() {
	return;
}

/**
 * Render uninstall section
 *
 * @return void
 */
function dedo_settings_uninstall_section() {
	return;
}

/**
 * Render members only field
 *
 * @return void
 */
function dedo_settings_members_only_field() {
	global $dedo_options;
	
	$checked = $dedo_options['members_only'];

	echo '<label for="delightful-downloads[members_only]">';
	echo '<input type="checkbox" name="delightful-downloads[members_only]" id="delightful-downloads-members-only" value="1" ' . checked( $checked, 1, false ) . ' /> ';
	echo __( 'Member Only', 'delightful-downloads' );
	echo '</label>';
	echo '<p class="description">' . __( 'Allow only logged in users to download files.', 'delightful-downloads' ) . '</p>';
}

/**
 * Render members redirect field
 *
 * @return void
 */
function dedo_settings_members_redirect_field() {
	global $dedo_options;
	
	// Default selected item
	$selected = $dedo_options['members_redirect'];
	
	// Output select input
	$args = array(
		'name'						=> 'delightful-downloads[members_redirect]',
		'selected'					=> $selected,
		'show_option_none'			=> 'Select...',
		'show_option_none_value'	=> 0
	);
	
	wp_dropdown_pages( $args );
	echo '<p class="description">' . __( 'The page to redirect non-logged in users when attempting to download a file. If no page is selected a default error will be displayed.', 'delightful-downloads' ) . '</p>';
}


/**
 * Render enable css field
 *
 * @return void
 */
function dedo_settings_enable_css_field() {
	global $dedo_options;
	
	$checked = $dedo_options['enable_css'];

	echo '<label for="delightful-downloads[enable_css]">';
	echo '<input type="checkbox" name="delightful-downloads[enable_css]" value="1" ' . checked( $checked, 1, false ) . ' /> ';
	echo __( 'Enable', 'delightful-downloads' );
	echo '</label>';
	echo '<p class="description">' . __( 'Disable this option to remove the default button styling and the Delightful Downloads CSS file.', 'delightful-downloads' ) . '</p>';
}

/**
 * Render default text field
 *
 * @return void
 */
function dedo_settings_default_text_field() {
	global $dedo_options;

	$text = $dedo_options['default_text'];

	echo '<input type="text" name="delightful-downloads[default_text]" value="' . $text . '" class="regular-text" />';
	echo '<p class="description">' . __( 'The default text displayed on link and button styles shortcode. Use %title% to automatically insert download title.', 'delightful-downloads' ) . '</p>';
}

/**
 * Render default style field
 *
 * @return void
 */
function dedo_settings_default_style_field() {
	global $dedo_options;

	$styles = dedo_get_shortcode_styles();
	$default_style = $dedo_options['default_style'];

	echo '<select name="delightful-downloads[default_style]">';
	foreach( $styles as $key => $value ) {
		$selected = ( $default_style == $key ? ' selected="selected"' : '' );
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';	
	}
	echo '</select>';
	echo '<p class="description">' . __( 'The default display style.', 'delightful-downloads' ) . '</p>';
}

/**
 * Render default color field
 *
 * @return void
 */
function dedo_settings_default_color_field() {
	global $dedo_options;

	$colors = dedo_get_shortcode_colors();
	$default_color = $dedo_options['default_color'];

	echo '<select name="delightful-downloads[default_color]">';
	foreach( $colors as $key => $value ) {
		$selected = ( $default_color == $key ? ' selected="selected"' : '' );
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';	
	}
	echo '</select>';
	echo '<p class="description">' . __( 'The default button color.', 'delightful-downloads' ) . '</p>';
}

/**
 * Render reset settings field
 *
 * @return void
 */
function dedo_settings_reset_settings_field() {
	global $dedo_options;
	
	$checked = $dedo_options['reset_settings'];
	
	echo '<label for="delightful-downloads[reset_settings]">';
	echo '<input type="checkbox" name="delightful-downloads[reset_settings]" value="1" ' . checked( $checked, 1, false ) . ' /> ';
	echo __( 'Enable', 'delightful-downloads' );
	echo '<p class="description">' . __( 'Reset plugin settings on re-activation.', 'delightful-downloads' ) . '</p>';
	echo '</label>';
}