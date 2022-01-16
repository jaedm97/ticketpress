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
		require plugin_dir_path( __FILE__ ) . '/includes/classes/class-functions.php';
		require plugin_dir_path( __FILE__ ) . '/includes/functions.php';

		require plugin_dir_path( __FILE__ ) . '/includes/classes/class-hooks.php';
		require plugin_dir_path( __FILE__ ) . '/includes/classes/class-meta-boxes.php';
	}
}

new TicketPress_Main();