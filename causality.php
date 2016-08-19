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
 * Add init actions
 */
add_action( 'init', 'causality_register_cpt' );

/**
 * Add shortcodes
 */
add_shortcode( 'causality', 'causality_shortcode' );

/**
 * Register 'Causality campaign' custom post type
 */
function causality_register_cpt() {
	$labels = [
		'name' => _x( 'Causality campaigns', 'causality_campaign' ),
		'singular_name' => _x( 'Causality campaign', 'causality_campaign' ),
		'add_new' => _x( 'Add New', 'causality_campaign' ),
		'add_new_item' => _x( 'Add New Causality campaign', 'causality_campaign' ),
		'edit_item' => _x( 'Edit Causality campaign', 'causality_campaign' ),
		'new_item' => _x( 'New Causality campaign', 'causality_campaign' ),
		'view_item' => _x( 'View Causality campaign', 'causality_campaign' ),
		'search_items' => _x( 'Search Causality campaigns',	 'causality_campaign' ),
		'not_found' => _x( 'No Causality campaigns found', 'causality_campaign' ),
		'not_found_in_trash' => _x( 'No Causality campaigns found in Trash', 'causality_campaign' ),
		'menu_name' => _x( 'Causality campaigns', 'causality_campaign' ),
	];

	$parameters = [
		'labels' => $labels,
		'description' => 'Overlay campaign for Causality',
		'public' => true,
	];

	register_post_type( 'causality_campaign', $parameters );
}

/**
 * Shortcode for rendering a Causality campaign
 *
 * @param array $attributes - array of attributes in shortcode.
 * $attributes['id'] - ID of causality campaign.
 */
function causality_shortcode( $attributes ) {
	return render_causality_campaign( 1 );
}

/**
 * Render a specified Causality campaign
 *
 * @param int $id - ID of Causality campaign.
 */
function render_causality_campaign( $id ) {
	$html = '';

	$query_parameters = [
		'post_type' => 'causality_campaign',
	];

	$query = new WP_Query( $query_parameters );

	if ( $query->have_posts() ) {
		$html .= '<b>';
		$query->the_post();
		$html .= get_the_title();
	}
	exit;
	return $html;
}
