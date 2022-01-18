<?php
/**
 * Vehicle listing and booking template
 */

defined( 'ABSPATH' ) || exit;

$vehicles = get_posts( array(
	'post_type'      => 'vehicle',
	'posts_per_page' => 2,
	'order'          => 'ASC',
	'orderby'        => 'date',
	'fields'         => 'ids',
) );


foreach ( $vehicles as $vehicle_id ) {

	$vehicle = new \TicketPress\Vehicle( $vehicle_id );

	printf( '<p>%s</p>', $vehicle->post->post_title );
	printf( '<p>Total Seats: %s</p>', $vehicle->total_seats );
}