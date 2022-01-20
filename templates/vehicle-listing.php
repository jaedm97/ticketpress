<?php
/**
 * Vehicle listing and booking template
 */

defined( 'ABSPATH' ) || exit;

$param_values   = wp_unslash( $_GET );
$p_start        = ticketpress()->get_args_option( 'p_start', $param_values );
$p_end          = ticketpress()->get_args_option( 'p_end', $param_values );
$type           = ticketpress()->get_args_option( 'type', $param_values );
$route          = ticketpress()->get_args_option( 'route', $param_values );
$vehicle_types  = get_terms( array(
	'taxonomy'   => 'vehicle_type',
	'hide_empty' => false,
) );
$vehicle_routes = get_terms( array(
	'taxonomy'   => 'vehicle_route',
	'hide_empty' => false,
) );
$meta_query     = array();
$tax_query      = array();

if ( ! empty( $p_start ) && ! empty( $p_end ) ) {
	$meta_query[] = array(
		'key'     => '_price',
		'value'   => array( $p_start, $p_end ),
		'compare' => 'BETWEEN',
		'type'    => 'numeric',
	);
}


if ( ! empty( $type ) ) {
	$tax_query[] = array(
		'taxonomy' => 'vehicle_type',
		'field'    => 'slug',
		'terms'    => $type,
	);
}

if ( ! empty( $route ) ) {
	$tax_query[] = array(
		'taxonomy' => 'vehicle_route',
		'field'    => 'slug',
		'terms'    => $route,
	);
}


$query_arg = array(
	'post_type'      => 'vehicle',
	'posts_per_page' => 2,
	'order'          => 'ASC',
	'orderby'        => 'date',
	'year'           => '2022',
	'fields'         => 'ids',
	'meta_query'     => $meta_query,
	'tax_query'      => $tax_query,
	'paged'          => get_query_var( 'paged' ),
);

$vehicle_query = new WP_Query( $query_arg );


?>

    <form action="" method="get" id="VehicleFilterForm">
        <div class="single-field">
            <label for="p_start">Price (start)</label>
            <input type="text" name="p_start" placeholder="100" id="p_start" value="<?php echo esc_attr( $p_start ); ?>">
        </div>
        <div class="single-field">
            <label for="p_end">Price (end)</label>
            <input type="text" name="p_end" placeholder="500" id="p_end" value="<?php echo esc_attr( $p_end ); ?>">
        </div>
        <div class="single-field">
            <label for="type">Type</label>
			<?php foreach ( $vehicle_types as $vehicle_type ) : ?>
                <label>
                    <input <?php checked( $type, $vehicle_type->slug ); ?> type="radio" name="type" value="<?php echo esc_attr( $vehicle_type->slug ); ?>" required>
                    <span><?php echo esc_html( $vehicle_type->name ); ?></span>
                </label>
			<?php endforeach; ?>
        </div>
        <div class="single-field">
            <label for="route">Routes</label>
            <select name="route" id="route">
                <option value="">Select Route</option>
				<?php foreach ( $vehicle_routes as $vehicle_route ) : ?>
                    <option <?php selected( $route, $vehicle_route->slug ); ?> value="<?php echo esc_attr( $vehicle_route->slug ); ?>"><?php echo esc_html( $vehicle_route->name ); ?></option>
				<?php endforeach; ?>
            </select>
        </div>


        <div class="single-field">
            <input type="submit" value="Filter">
            <button type="reset" id="formReset">Reset</button>
        </div>
    </form>

    <div class="all-vehicles">

		<?php if ( $vehicle_query->have_posts() ) : while ( $vehicle_query->have_posts() ) : $vehicle_query->the_post(); ?>

			<?php $vehicle = new \TicketPress\Vehicle(); ?>

            <div class="single-vehicle">

                <h3><?php echo esc_html( $vehicle->post->post_title ); ?></h3>

                <div class="vehicle-meta-data">
                    <div class="meta">
                        <strong>Price:</strong><span><?php echo esc_html( $vehicle->price ); ?></span>
                    </div>
                    <div class="meta">
                        <strong>Number:</strong><span><?php echo esc_html( $vehicle->number ); ?></span>
                    </div>
                    <div class="meta">
                        <strong>Seats:</strong><span><?php echo esc_html( $vehicle->total_seats ); ?></span>
                    </div>
                    <div class="meta">
                        <strong>Types:</strong><span><?php echo esc_html( $vehicle->get_types() ); ?></span>
                    </div>
                    <div class="meta">
                        <strong>Routes:</strong><span><?php echo esc_html( $vehicle->get_routes() ); ?></span>
                    </div>
                </div>

            </div>

		<?php endwhile; endif; ?>

    </div>

<?php

$big = 99999999999;

echo paginate_links( array(
	'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format'  => '?paged=%#%',
	'current' => max( 1, get_query_var( 'paged' ) ),
	'total'   => $vehicle_query->max_num_pages
) );


wp_reset_query();