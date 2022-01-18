<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

class TicketPress_Meta_boxes {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_vehicle_data' ) );
	}


	function save_vehicle_data( $post_id ) {

		$posted_data  = wp_unslash( $_POST );
		$_price       = ticketpress()->get_args_option( '_price', $posted_data );
		$_number      = ticketpress()->get_args_option( '_number', $posted_data );
		$_time        = ticketpress()->get_args_option( '_time', $posted_data );
		$_total_seats = ticketpress()->get_args_option( '_total_seats', $posted_data );
		$_seats       = ticketpress()->get_args_option( '_seats', $posted_data );

		update_post_meta( $post_id, '_price', $_price );
		update_post_meta( $post_id, '_number', $_number );
		update_post_meta( $post_id, '_time', $_time );
		update_post_meta( $post_id, '_total_seats', $_total_seats );
		update_post_meta( $post_id, '_seats', $_seats );

//		die();
	}


	function vehicle_data_box( WP_Post $post ) {

		$meta_fields    = array(
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
		);
		$vehicle_routes = get_the_terms( $post, 'vehicle_route' );
		$vehicle_routes = ! is_array( $vehicle_routes ) ? array() : $vehicle_routes;
		$_time          = get_post_meta( $post->ID, '_time', true );

		foreach ( $vehicle_routes as $index => $route ) {

			$start_time = isset( $_time[ $route->term_id ]['start'] ) ? $_time[ $route->term_id ]['start'] : '';
			$end_time   = isset( $_time[ $route->term_id ]['end'] ) ? $_time[ $route->term_id ]['end'] : '';

			$meta_fields[] = array(
				'id'            => "_time[{$route->term_id}][start]",
				'title'         => esc_html__( 'Travel Time ', 'ticketpress' ) . ( $index + 1 ),
				'details'       => $route->name . ' - Start',
				'type'          => 'timepicker',
				'value'         => $start_time,
				'field_options' => array(
					'interval' => 5,
				),
			);

			$meta_fields[] = array(
				'id'            => "_time[{$route->term_id}][end]",
				'details'       => $route->name . ' - End',
				'type'          => 'timepicker',
				'value'         => $end_time,
				'field_options' => array(
					'interval' => 5,
				),
			);
		}


		$_seats = get_post_meta( $post->ID, '_seats', true );

		$meta_fields[] = array(
			'id'          => '_total_seats',
			'title'       => esc_html__( 'Seats ', 'ticketpress' ),
			'type'        => 'number',
			'placeholder' => 40,
		);

		$meta_fields[] = array(
			'id'          => '_seats[rows]',
			'details'     => esc_html__( 'Rows, ex 10 ', 'ticketpress' ),
			'type'        => 'number',
			'placeholder' => 10,
			'value'       => ticketpress()->get_args_option( 'rows', $_seats ),
		);

		$meta_fields[] = array(
			'id'          => '_seats[columns]',
			'details'     => esc_html__( 'Columns, ex 4 ', 'ticketpress' ),
			'type'        => 'number',
			'placeholder' => 4,
			'value'       => ticketpress()->get_args_option( 'columns', $_seats ),
		);

		ticketpress()->WP_Settings()->generate_fields( array( array( 'options' => $meta_fields ) ), $post->ID );
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