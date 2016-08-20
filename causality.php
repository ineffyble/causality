<?php
/**
 * Plugin Name: Causality
 * Description: Promote your cause and have an effect on your supporters
 * Version: 0.01
 * Author: Effy Elden
 *
 * @package WordPress
 */

/**
 * Add action to add page to admin menu
 */
add_action( 'admin_menu', 'causality_admin_menu_page' );

add_action( 'admin_init', 'causality_settings_init' );

/**
 * Add settings page to admin menu
 */
function causality_admin_menu_page() {
	add_menu_page( 'Causality', 'Causality', 'manage_options', 'causality_settings', 'causality_settings_page' );
}

function causality_settings_init() {
	add_settings_section(
		'causality_settings_section',
		'Settings section for Causality',
		'causality_settings_section_render',
		'causality_settings'
	);

	add_settings_field(
		'causality_title',
		'Campaign title',
		'causality_settings_title_render',
		'causality_settings',
		'causality_settings_section'
	);

	add_settings_field(
		'causality_image_url',
		'Campaign image URL',
		'causality_settings_image_url_render',
		'causality_settings',
		'causality_settings_section'
	);

	register_setting(
		'causality', 'causality_title'
	);

	register_setting(
		'causality', 'causality_image_url'
	);
}

function causality_settings_section_render() {
	echo '<p>Causality settings head</p>';
}

function causality_settings_title_render() {
	$setting = esc_attr( get_option( 'causality_title' ) );
	echo "<input type='text' name='causality_title' value='$setting' />"; // XSS ok.
}

function causality_settings_image_url_render() {
	$setting = esc_attr( get_option( 'causality_image_url' ) );
	echo "<input type='text' name='causality_image_url' value='$setting' />"; // XSS ok.
}

/**
 * Settings page
 */
function causality_settings_page() {
	?>
	<div class="wrap">
		<h1>Causality settings</h1>
		<form method="post" action="options.php" id="causality_settings_form">
			<?php settings_fields( 'causality' ); ?>
			<?php do_settings_sections( 'causality_settings' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Add shortcodes
 */
add_shortcode( 'causality', 'causality_shortcode' );


/**
 * Shortcode for rendering Causality
 *
 * @param array $attributes - array of attributes in shortcode.
 */
function causality_shortcode( $attributes ) {
	wp_enqueue_script( 'causality', plugins_url( 'causality.js', __FILE__ ), [ 'jquery', 'jcrop' ] );
	wp_enqueue_style( 'causality', plugins_url( 'causality.css', __FILE__ ), [ 'jcrop' ] );
	return causality_render();
}

/**
 * Render Causality
 */
function causality_render() {
	$html = '';
	$html .= '<script>var causality_overlay = "' . esc_url( get_option( 'causality_image_url' ) ) . '";</script>';
	$html .= '<h3>' . esc_attr( get_option( 'causality_title' ) ) . '</h3>';
	$html .= '<input type="file" id="causality_upload" value="Upload your profile photo" accept="image/*" />';
	$html .= '<div id="causality_jcrop"></div>';
	$html .= '<button id="causality_crop_button">Crop</button>';
	$html .= '<canvas id="causality_canvas" style="display: none;" width="960px" height="960px"></canvas>';
	$html .= '<div id="causality_output"></div>';
	return $html;
}
