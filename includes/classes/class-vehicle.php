<?php
/**
 * Vehicle Class
 */

namespace TicketPress;

defined( 'ABSPATH' ) || exit;

class Vehicle {

	public $id = null;

	/**
	 * @var null
	 */
	public $post = null;

	public $price = null;

	public $number = null;

	public $time = null;

	public $total_seats = null;

	public $seats = null;

	public $types = null;

	public $routes = null;


	function __construct( $vehicle_id = '' ) {

		$this->id = empty( $vehicle_id ) || $vehicle_id == 0 ? get_the_ID() : $vehicle_id;

		$this->set_data();
	}


	function set_data() {

		$this->post = get_post( $this->id );

		if ( ! $this->post instanceof \WP_Post ) {
			return;
		}

		$this->price       = ticketpress()->get_meta( '_price', $this->id );
		$this->number      = ticketpress()->get_meta( '_number', $this->id );
		$this->time        = ticketpress()->get_meta( '_time', $this->id );
		$this->total_seats = ticketpress()->get_meta( '_total_seats', $this->id );
		$this->seats       = ticketpress()->get_meta( '_seats', $this->id );
		$this->types       = get_the_terms( $this->id, 'vehicle_type' );
		$this->routes      = get_the_terms( $this->id, 'vehicle_route' );
	}
}