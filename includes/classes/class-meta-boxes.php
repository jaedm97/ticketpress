<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

class TicketPress_Meta_boxes {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}


	public function vehicle_data_box( WP_Post $post ) {

		$meta_fields = array(
			array(
				'id'          => '_price',
				'title'       => esc_html__( 'Ticket Price', 'ticketpress' ),
				'type'        => 'number',
				'placeholder' => '500',
			),
			array(
				'id'          => '_number',
				'title'       => esc_html__( 'Vehicle Number', 'ticketpress' ),
				'type'        => 'text',
				'placeholder' => 'DHAKA-METRO-GHA-1290',
			),
			array(
				'id'          => '_time_start',
				'title'       => esc_html__( 'Travel Time Start', 'ticketpress' ),
				'type'        => 'timepicker',
				'placeholder' => '20:00',
			),
			array(
				'id'            => '_time_end',
				'title'         => esc_html__( 'Travel Time End', 'ticketpress' ),
				'type'          => 'timepicker',
				'placeholder'   => '05:00',
				'field_options' => array(
					'interval' => 5,
				),
			),
		);


		$vehicle_routes = get_the_terms( $post, 'vehicle_route' );

		foreach ( $vehicle_routes as $index => $route ) {

			$meta_fields[] = array(
				'id'            => "_time[{$route->term_id}][start]",
				'title'         => esc_html__( 'Travel Time ', 'ticketpress' ) . ( $index + 1 ),
				'details'       => $route->name . ' - Start',
				'type'          => 'timepicker',
				'field_options' => array(
					'interval' => 5,
				),
			);

			$meta_fields[] = array(
				'id'            => "_time[{$route->term_id}][end]",
				'details'       => $route->name . ' - End',
				'type'          => 'timepicker',
				'field_options' => array(
					'interval' => 5,
				),
			);
		}


		ticketpress()->WP_Settings()->generate_fields( array( array( 'options' => $meta_fields ) ) );
	}


	/**
	 * Add meta boxes
	 *
	 * @param $post_type
	 */
	public function add_meta_boxes( $post_type ) {

		if ( $post_type == 'vehicle' ) {
			add_meta_box( 'vehicle_data', esc_html__( 'Vehicle Data', 'ticketpress' ), array( $this, 'vehicle_data_box' ), $post_type, 'advanced', 'high' );
		}
	}


	/**
	 * Return meta fields for direct use to PB_Settings
	 *
	 * @param string $fields_for
	 *
	 * @return mixed|void
	 */
	function get_meta_fields( $fields_for = 'orders' ) {
		return array( array( 'options' => cstools()->get_poll_meta_fields( $fields_for ) ) );
	}
}

new TicketPress_Meta_boxes();