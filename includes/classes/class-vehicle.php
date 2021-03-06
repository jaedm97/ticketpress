<?php
/**
 * Vehicle Class
 */

namespace TicketPress;

use WP_Term;

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


	function get_seats_columns() {
		return ticketpress()->get_args_option( 'columns', $this->seats, 0 );
	}

	function get_seats_rows() {
		return ticketpress()->get_args_option( 'rows', $this->seats, 0 );
	}


	function get_name() {
		return $this->post->post_title;
	}


	function get_permalink() {
		return get_the_permalink( $this->id );
	}


	/**
	 * Get html route selection
	 *
	 * @param string $separator
	 *
	 * @return string
	 */
	function get_routes_selection( $separator = '' ) {

		$routes = $this->routes;
		$routes = ! is_array( $routes ) || empty( $routes ) ? array() : $routes;
		$routes = array_map( function ( $route ) {
			if ( $route instanceof WP_Term ) {
				return sprintf( '<label><span>%s</span><input type="radio" name="route" value="%s"></label>',
					$route->name, $route->term_id
				);
			}

			return '';
		}, $routes );

		array_filter( $routes );

		return implode( $separator, $routes );
	}

	function get_routes( $separator = ', ' ) {

		$routes = $this->routes;
		$routes = ! is_array( $routes ) || empty( $routes ) ? array() : $routes;
		$routes = array_map( function ( $route ) {
			if ( $route instanceof WP_Term ) {
				return $route->name;
			}

			return '';
		}, $routes );

		return implode( $separator, $routes );
	}

	function get_types( $separator = ', ' ) {

		$types = $this->types;
		$types = ! is_array( $this->types ) || empty( $this->types ) ? array() : $types;
		$types = array_map( function ( $type ) {
			if ( $type instanceof WP_Term ) {
				return $type->name;
			}

			return '';
		}, $types );

		return implode( $separator, $types );
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