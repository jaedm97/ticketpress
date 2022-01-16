<?php
/**
 * Class Functions
 *
 * @author Jaed
 */


class TicketPress_Functions {

	private static $wp_settings = null;


	function __construct() {

		if ( ! self::$wp_settings ) {
			self::$wp_settings = new WP_Settings();
		}
	}



	/**
	 * Return WP_Settings class
	 *
	 * @param array $args
	 *
	 * @return WP_Settings
	 */
	function WP_Settings( $args = array() ) {

		if ( is_array( $args ) && ! empty( $args ) ) {
			self::$wp_settings->set_data( $args );
		}

		return self::$wp_settings;
	}


	/**
	 * Return plugin settings fields
	 *
	 * @return mixed|void
	 */
	function get_plugin_settings() {

		$pages['cstools-options'] = array(
			'page_nav'      => esc_html__( 'Options', 'cstools' ),
			'page_settings' => apply_filters( 'cstools_filters_settings_page_options', array(
				array(
					'title'   => esc_html__( 'General Settings', 'cstools' ),
					'options' => array(
						array(
							'id'          => 'cstools_btn_text_new_option',
							'title'       => esc_html__( 'Buttons Text', 'cstools' ),
							'details'     => esc_html__( 'New option button', 'cstools' ),
							'placeholder' => esc_html__( 'New Option', 'cstools' ),
							'type'        => 'text',
						),
						array(
							'id'          => 'cstools_btn_text_submit',
							'details'     => esc_html__( 'Submit button', 'cstools' ),
							'placeholder' => esc_html__( 'Submit now', 'cstools' ),
							'type'        => 'text',
						),
						array(
							'id'          => 'cstools_btn_text_results',
							'details'     => esc_html__( 'Results button', 'cstools' ),
							'placeholder' => esc_html__( 'Results', 'cstools' ),
							'type'        => 'text',
						),
					)
				),
				array(
					'title'   => esc_html__( 'Poll Archive', 'cstools' ),
					'options' => array(
						array(
							'id'      => 'cstools_page_archive',
							'title'   => esc_html__( 'Archive Page', 'cstools' ),
							'details' => esc_html__( 'Select a poll archive page', 'cstools' ),
							'type'    => 'select',
							'args'    => 'PAGES',
						),
						array(
							'id'          => 'cstools_archive_items_per_page',
							'title'       => esc_html__( 'Items per page', 'cstools' ),
							'details'     => esc_html__( 'How many poll do you want to show per page | Default: 10', 'cstools' ),
							'placeholder' => esc_html__( '10', 'cstools' ),
							'type'        => 'number',
						),
						array(
							'id'      => 'cstools_archive_show_hide',
							'title'   => esc_html__( 'Show / Hide', 'cstools' ),
							'details' => esc_html__( 'Choose what you want to display on archive page.', 'cstools' ),
							'type'    => 'checkbox',
							'args'    => array(
								'thumb'        => esc_html__( 'Display poll thumbnail', 'cstools' ),
								'results'      => esc_html__( 'Display poll results', 'cstools' ),
								'pagination'   => esc_html__( 'Display pagination', 'cstools' ),
								'page-content' => esc_html__( 'Display archive page content', 'cstools' ),
							),
							'default' => array( 'pagination' ),
						),
					)
				),
			) ),
		);

		$pages['cstools-reports'] = array(
			'page_nav'      => esc_html__( 'Poll Reports', 'cstools' ),
			'show_submit'   => false,
			'page_settings' => apply_filters( 'cstools_filters_settings_page_reports', array(
				array(
					'title'       => esc_html__( 'Poll Reports', 'cstools' ),
					'description' => esc_html__( 'View reports for specific poll item', 'cstools' ),
					'options'     => array(
						array(
							'id'       => 'cstools_reports_poll_id',
							'title'    => esc_html__( 'Select Poll', 'cstools' ),
							'details'  => esc_html__( 'Select a poll you want to see report. Reports will generate automatically', 'cstools' ),
							'type'     => 'select',
							'value'    => isset( $_GET['poll-id'] ) ? sanitize_text_field( $_GET['poll-id'] ) : '',
							'args'     => 'POSTS_%poll%',
							'required' => true,
						),
						array(
							'id'    => 'cstools_reports_style',
							'title' => esc_html__( 'Report Style', 'cstools' ),
							'type'  => 'select',
							'value' => isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : 'pie',
							'args'  => array(
								'pie' => esc_html__( 'Pie Charts', 'cstools' ),
								'bar' => esc_html__( 'Bar Charts', 'cstools' ),
							),
						),

						array(
							'id' => 'cstools_export_button',
						),

					),
				),
			) ),
		);

		return apply_filters( 'cstools_filters_settings_pages', $pages );
	}


	/**
	 * Print notices
	 *
	 * @param string $message
	 * @param string $type
	 * @param bool $is_dismissible
	 */
	function print_notice( $message = '', $type = 'success', $is_dismissible = true ) {

		$is_dismissible = $is_dismissible ? 'is-dismissible' : '';

		if ( ! empty( $message ) ) {
			printf( '<div class="notice notice-%s %s"><p>%s</p></div>', $type, $is_dismissible, $message );
		}
	}


	/**
	 * Return Post Meta Value
	 *
	 * @param bool $meta_key
	 * @param bool $post_id
	 * @param string $default
	 *
	 * @return mixed|string|void
	 */
	function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

		if ( ! $meta_key ) {
			return '';
		}

		$post_id    = ! $post_id ? get_the_ID() : $post_id;
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		$meta_value = empty( $meta_value ) ? $default : $meta_value;

		return apply_filters( 'eem_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
	}


	/**
	 * Return option value
	 *
	 * @param string $option_key
	 * @param string $default_val
	 *
	 * @return mixed|string|void
	 */
	function get_option( $option_key = '', $default_val = '' ) {

		if ( empty( $option_key ) ) {
			return '';
		}

		$option_val = get_option( $option_key, $default_val );
		$option_val = empty( $option_val ) ? $default_val : $option_val;

		return apply_filters( 'cstools_filters_option_' . $option_key, $option_val );
	}


	/**
	 * Return Arguments Value
	 *
	 * @param string $key
	 * @param array $args
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	function get_args_option( $key = '', $args = array(), $default = '' ) {

		$default = empty( $default ) ? '' : $default;
		$key     = empty( $key ) ? '' : $key;

		if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
			return $args[ $key ];
		}

		return $default;
	}
}

global $ticketpress;

$ticketpress = new TicketPress_Functions();