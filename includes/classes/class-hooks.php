<?php
/**
 * Class Hooks
 */

defined( 'ABSPATH' ) || exit;

class TicketPress_Hooks {

	function __construct() {

		add_action( 'init', array( $this, 'register_post_types' ) );
		add_filter( 'single_template', array( $this, 'load_vehicle_template' ) );

		// Filter Ajax
		add_action( 'wp_ajax_ticketpress_search_vehicle', array( $this, 'ticketpress_search_vehicle' ) );
	}


	function ticketpress_search_vehicle() {

		$posted_data = wp_unslash( $_POST );
		$_form_data  = ticketpress()->get_args_option( 'form_data', $posted_data );

		parse_str( $_form_data, $form_data );

		$vehicle_query = ticketpress_get_vehicle_query( array(
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'date',
			'fields'         => 'ids',
			'extra_args'     => $form_data,
		) );
		$vehicle_html  = array();

		if ( $vehicle_query->have_posts() ) :
			while ( $vehicle_query->have_posts() ) :

				$vehicle_query->the_post();

				$vehicle_html[] = ticketpress_get_single_vehicle_html();

			endwhile;
		endif;

		wp_send_json_success( implode( ' ', $vehicle_html ) );
	}


	function load_vehicle_template( $single_template ) {

		if ( is_singular( 'vehicle' ) ) {
			$single_template = TICKETPRESS_FILE_DIR . 'templates/single-vehicle.php';
		}

		return $single_template;
	}


	function render_vehicle_listing( $atts ) {

		ob_start();

		require TICKETPRESS_FILE_DIR . 'templates/vehicle-listing.php';

		return ob_get_clean();
	}

	function render_vehicle_listing_ajax( $atts ) {

		ob_start();

		require TICKETPRESS_FILE_DIR . 'templates/vehicle-listing-ajax.php';

		return ob_get_clean();
	}


	/**
	 * Register all post types
	 */
	function register_post_types() {

		ticketpress()->WP_Settings()->register_post_type( 'vehicle', array(
			'singular'  => 'Vehicle',
			'plural'    => 'Vehicles',
			'menu_icon' => 'dashicons-car',
			'supports'  => array( 'title', 'thumbnail' ),
		) );

		ticketpress()->WP_Settings()->register_taxonomy( 'vehicle_type', 'vehicle', array(
			'singular'     => 'Vehicle Type',
			'plural'       => 'Vehicle Types',
			'hierarchical' => true,
		) );

		ticketpress()->WP_Settings()->register_taxonomy( 'vehicle_route', 'vehicle', array(
			'singular'     => 'Route',
			'plural'       => 'Routes',
			'hierarchical' => true,
		) );

		ticketpress()->WP_Settings()->register_shortcode( 'vehicle-listing', array( $this, 'render_vehicle_listing' ) );
		ticketpress()->WP_Settings()->register_shortcode( 'vehicle-listing-ajax', array( $this, 'render_vehicle_listing_ajax' ) );
	}
}

new TicketPress_Hooks();