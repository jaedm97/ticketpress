<?php
/**
 * Vehicle listing and booking template
 */

defined( 'ABSPATH' ) || exit;


$vehicle_types  = get_terms( array(
	'taxonomy'   => 'vehicle_type',
	'hide_empty' => false,
) );
$vehicle_routes = get_terms( array(
	'taxonomy'   => 'vehicle_route',
	'hide_empty' => false,
) );
$vehicle_query  = ticketpress_get_vehicle_query( array(
	'posts_per_page' => - 1,
	'order'          => 'ASC',
	'orderby'        => 'date',
	'fields'         => 'ids',
) );

?>

    <form action="" method="get" id="VehicleFilterFormAjax">
        <div class="single-field">
            <label for="p_start">Price (start)</label>
            <input type="text" name="p_start" placeholder="100" id="p_start">
        </div>
        <div class="single-field">
            <label for="p_end">Price (end)</label>
            <input type="text" name="p_end" placeholder="500" id="p_end">
        </div>
        <div class="single-field">
            <label for="type">Type</label>
			<?php foreach ( $vehicle_types as $vehicle_type ) : ?>
                <label>
                    <input type="radio" name="type" value="<?php echo esc_attr( $vehicle_type->slug ); ?>">
                    <span><?php echo esc_html( $vehicle_type->name ); ?></span>
                </label>
			<?php endforeach; ?>
        </div>
        <div class="single-field">
            <label for="route">Routes</label>
            <select name="route" id="route">
                <option value="">Select Route</option>
				<?php foreach ( $vehicle_routes as $vehicle_route ) : ?>
                    <option value="<?php echo esc_attr( $vehicle_route->slug ); ?>"><?php echo esc_html( $vehicle_route->name ); ?></option>
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

			<?php echo ticketpress_get_single_vehicle_html(); ?>

		<?php endwhile; endif; ?>

    </div>

<?php

wp_reset_query();