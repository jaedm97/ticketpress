<?php
/**
 * Plugin Name: TicketPress
 * Plugin URI: https://pluginbazar.com/ticketpress
 * Description: Multipurpose ticketing solution for WordPress website.
 * Version: 1.0.0
 * Author: Pluginbazar
 * Text Domain: ticketpress
 * Domain Path: /languages/
 * Author URI: https://pluginbazar.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


if ( ! defined( 'TICKETPRESS_FILE_DIR' ) ) {
	define( 'TICKETPRESS_FILE_DIR', plugin_dir_path( __FILE__ ) . '/' );
}


class TicketPress_Main {

	function __construct() {

		require TICKETPRESS_FILE_DIR . 'includes/classes/class-wp-settings.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-functions.php';
		require TICKETPRESS_FILE_DIR . 'includes/functions.php';

		require TICKETPRESS_FILE_DIR . 'includes/classes/class-hooks.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-meta-boxes.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-vehicle.php';
	}
}

new TicketPress_Main();

// single-vehicle-hanif-enterprise.php
// single-vehicle.php
// single.php
// singular.php
// index.php
//
//add_action( 'wp_footer', function () {
//
//	$vehicle_id = get_the_ID();
//
//	$vehicle = new TicketPress\Vehicle( 77 );
//
//	echo "<pre>";
//	print_r( $vehicle );
//	echo "</pre>";
//
//} );