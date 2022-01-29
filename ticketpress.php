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


defined( 'ABSPATH' ) || exit;


defined( 'TICKETPRESS_FILE_DIR' ) || define( 'TICKETPRESS_FILE_DIR', plugin_dir_path( __FILE__ ) . '/' );
defined( 'TICKETPRESS_FILE_URL' ) || define( 'TICKETPRESS_FILE_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );

class TicketPress_Main {

	function __construct() {

		require TICKETPRESS_FILE_DIR . 'includes/classes/class-wp-settings.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-functions.php';
		require TICKETPRESS_FILE_DIR . 'includes/functions.php';

		require TICKETPRESS_FILE_DIR . 'includes/classes/class-hooks.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-meta-boxes.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-vehicle.php';
		require TICKETPRESS_FILE_DIR . 'includes/classes/class-settings.php';

		add_action( 'wp_enqueue_scripts', array( $this, 'add_frontend_scripts' ) );
	}


	function add_frontend_scripts() {

		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_enqueue_style( 'jquery-ui', TICKETPRESS_FILE_URL . 'assets/front/css/jquery-ui.min.css', array(), time() );
		wp_enqueue_style( 'ticketpress-front', TICKETPRESS_FILE_URL . 'assets/front/css/style.css', array(), time() );
		wp_enqueue_script( 'ticketpress-front', TICKETPRESS_FILE_URL . 'assets/front/js/scripts.js', array( 'jquery' ), time() );
		wp_localize_script( 'ticketpress-front', 'ticketPress', array(
			'ajaxURL'             => admin_url( 'admin-ajax.php' ),
			'favText'             => esc_html__( 'Favourite', 'ticketpress' ),
			'unFavText'           => esc_html__( 'Un Favourite', 'ticketpress' ),
			'singlePassengerHTML' => sprintf( '' ),
			'confirmBookingText'  => esc_html__( 'Confirm Booking', 'ticketpress' ),
		) );
	}
}

new TicketPress_Main();
