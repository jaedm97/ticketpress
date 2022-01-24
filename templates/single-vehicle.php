<?php
/**
 * Vehicle single template
 */

get_header();

$vehicle = new TicketPress\Vehicle();

?>


    <div class="single-vehicle">
        <h2><?php echo $vehicle->get_name(); ?></h2>

        <div class="vehicle-seats">


            <div class="seat-row">

				<?php for ( $i = 0; $i < $vehicle->total_seats; $i ++ ) : ?>

					<?php if ( $i % $vehicle->get_seats_columns() == 0 ) {
						echo '</div><div class="seat-row">';
					} ?>

                    <label class="seat">
                        <input type="checkbox" name="seats_selected" value="<?php echo $i + 1; ?>">
                        <span><?php echo get_seat_label( ( $i + 1 ), $vehicle->get_seats_columns() ); ?></span>
                    </label>

				<?php endfor; ?>

            </div>

        </div>

    </div>

<?php
get_footer();
