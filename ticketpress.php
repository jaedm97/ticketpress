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

		add_action( 'wp_enqueue_scripts', array( $this, 'add_frontend_scripts' ) );
	}


	function add_frontend_scripts() {

		wp_enqueue_style( 'ticketpress-front', TICKETPRESS_FILE_URL . 'assets/front/css/style.css', array(), time() );
		wp_enqueue_script( 'ticketpress-front', TICKETPRESS_FILE_URL . 'assets/front/js/scripts.js', array( 'jquery' ) );
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

//
//function render_my_email( $atts ) {
//
//	ob_start();
//
//
//	$user_id = isset( $atts['id'] ) && ! empty( $atts['id'] ) ? $atts['id'] : get_current_user_id();
//
//	if ( empty( $user_id ) ) {
//		return '<p>No User ID found!</p>';
//	}
//
//	$user = get_user_by( 'id', $user_id );
//
//	?>
    <!---->
    <!--    <div class="p-wrap">-->
    <!--        <p>Username: --><?php //echo $user->user_login; ?><!--</p>-->
    <!--        <p>Full Name: --><?php //echo $user->display_name; ?><!--</p>-->
    <!--        <p>Email: --><?php //echo $user->user_email; ?><!--</p>-->
    <!--    </div>-->
    <!---->
    <!--	--><?php
//
//	return ob_get_clean();
//}
//
//add_shortcode( 'user-details', 'render_my_email' );