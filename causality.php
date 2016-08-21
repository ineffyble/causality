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
		'causality_fb_app_id',
		'Facebook App ID',
		'causality_settings_fb_app_id_render',
		'causality_settings',
		'causality_settings_section'
	);

	register_setting(
		'causality', 'causality_title'
	);

	register_setting(
		'causality', 'causality_overlays'
	);

	register_setting(
		'causality', 'causality_fb_app_id'
	);
}

function causality_settings_section_render() {
	echo '<p>Causality settings head</p>';
}

function causality_settings_title_render() {
	$setting = esc_attr( get_option( 'causality_title' ) );
	echo "<input type='text' name='causality_title' value='$setting' />"; // XSS ok.
}

function causality_settings_overlays_render() {
	$setting = get_option( 'causality_overlays' );
	echo "<input type='text' id='causality_overlays' name='causality_overlays' value='$setting' style='display: none;'/'>"; // XSS ok.
	echo '<div id="causality_overlay_fields">';
	if ( $overlays = json_decode( $setting, true ) ) {
		foreach ( $overlays as $title => $url ) {
			echo "<div class='causality_overlay_field'><b>Title</b><input type='text' class='causality_overlay_title' value='" . esc_attr( $title ) . "'><b>URL</b><input type='text' class='causality_overlay_url' value='" . esc_url( $url ) . "'></div><br>";
		}
	} else {
		echo "<div class='causality_overlay_field'><b>Title</b><input type='text' class='causality_overlay_title' value=''><b>URL</b><input type='text' class='causality_overlay_url' value=''></div><br>";
	}
	echo '</div>';
	echo '<button id="causality_add_overlay_field" onclick="causalityAddOverlayField(event)">Add more</button>';
}

function causality_settings_fb_app_id_render() {
	$setting = esc_attr( get_option( 'causality_fb_app_id' ) );
	echo "<input type='text' name='causality_fb_app_id' value='$setting' />"; // XSS ok.
}

/**
 * Settings page
 */
function causality_settings_page() {
	wp_enqueue_script( 'causality-admin', plugins_url( 'causality-admin.js', __FILE__ ), [ 'jquery' ] );
	?>
	<div class="wrap">
		<h1>Causality settings</h1>
		<form method="post" action="options.php" id="causality_settings_form">
			<?php settings_fields( 'causality' ); ?>
			<?php do_settings_sections( 'causality_settings' ); ?>
			<?php causality_settings_overlays_render(); ?>
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
	$overlays = json_decode( get_option( 'causality_overlays' ), true );
	if ( ! ( $overlays ) ) {
		return '<p>ERROR: No overlays configured</p>';
	}
	$html = '';
	$html .= '<script>';
	$html .= 'var causality_overlays = "' . esc_js( get_option( 'causality_overlays' ) ) . '";';
	$html .= 'var causality_fb_app_id = "' . esc_attr( get_option( 'causality_fb_app_id' ) ) . '";';
	$html .= '</script>';
	$html .= '<h3>' . esc_attr( get_option( 'causality_title' ) ) . '</h3>';
	$html .= '<select name="causality_overlay" id="causality_overlay">';
	$html .= '<option title="Choose your overlay" value="">Choose your overlay</option>';
	foreach ( $overlays as $title => $url ) {
		$html .= '<option title="' . esc_attr( $title ) . '" value="' . esc_url( $url ) . '">' . esc_attr( $title ) . '</option>';
	}
	$html .= '</select><br>';
	$html .= '<input type="file" id="causality_upload" value="Upload your profile photo" accept="image/*"/>';
	$html .= '<button id="causality_activate_upload" onclick="causalityActivateUpload()" class="causality-button">Upload a photo</button>';
	$html .= '<button id="causality_facebook_login" onclick="causalityFacebookLogin()" class="causality-button">Use my Facebook photo</button>';
	$html .= '<div id="causality_jcrop_container"><div id="causality_jcrop"></div></div>';
	$html .= '<button id="causality_crop" class="causality-button">Crop</button>';
	$html .= '<canvas id="causality_canvas" style="display: none;" width="960px" height="960px"></canvas>';
	$html .= '<div id="causality_output"></div>';
	return $html;
}
