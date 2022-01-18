<?php
/**
 * Class Hooks
 */

class TicketPress_Hooks {

	function __construct() {

		add_action( 'init', array( $this, 'register_post_types' ) );
		add_filter( 'single_template', array( $this, 'load_vehicle_template' ) );
	}


	function load_vehicle_template( $single_template ) {

		if ( is_singular( 'vehicle' ) ) {
			$single_template = TICKETPRESS_FILE_DIR . 'templates/single-vehicle.php';
		}

		return $single_template;
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
	}
}

new TicketPress_Hooks();