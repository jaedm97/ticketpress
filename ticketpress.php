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

class TicketPress_Main {

	function __construct() {

		require plugin_dir_path( __FILE__ ) . '/includes/classes/class-wp-settings.php';

		add_action( 'init', array( $this, 'register_post_types' ) );

		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
	}


	function save_meta_box_data( $post_id ) {

		$total_seats = isset( $_POST['total_seats'] ) ? $_POST['total_seats'] : '';

		update_post_meta( $post_id, 'total_seats', $total_seats );
	}


	function render_data_box( WP_Post $post ) {

		$total_seats = get_post_meta( $post->ID, 'total_seats', true );

		?>
        <label for="TotalSeats">Seat Number</label>
        <input id="TotalSeats" type="number" name="total_seats" placeholder="40" value="<?php echo $total_seats; ?>">
		<?php
	}


	function register_meta_boxes( $post_type ) {

		if ( $post_type == 'vehicle' ) {
			add_meta_box( 'vehicle_data', 'Vehicle Data', array( $this, 'render_data_box' ), $post_type, 'advanced', 'high' );
		}
	}


	function register_post_types() {

		$wp_settings = new WP_Settings();

		$wp_settings->register_post_type( 'vehicle', array(
			'singular'  => 'Vehicle',
			'plural'    => 'Vehicles',
			'menu_icon' => 'dashicons - car',
			'supports'  => array( 'title', 'thumbnail' ),
		) );
	}
}

new TicketPress_Main();