<?php
/**
 * Vehicle single template
 */

get_header();

$vehicle = new TicketPress\Vehicle();

?>

    <div class="single-vehicle">

        <div class="vehicle-notice no-display"></div>

        <div class="side-left">
            <div class="vehicle-data-box vehicle-info">
                <h3>Vehicle Information</h3>
                <div class="info-items">
                    <div class="info-item">
                        <span class="label">Vehicle Name</span>
                        <span class="val"><?php echo esc_html( $vehicle->get_name() ); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Vehicle Number</span>
                        <span class="val"><?php echo esc_html( $vehicle->number ); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Route</span>
                        <span class="val ticketpress-routes-selection"><?php echo $vehicle->get_routes_selection(); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Time</span>
                        <span class="val"><?php // echo esc_html( $vehicle->get_times() ); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Total Seats</span>
                        <span class="val"><?php echo esc_html( $vehicle->total_seats ); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Type</span>
                        <span class="val"><?php echo esc_html( $vehicle->get_types() ); ?></span>
                    </div>
                </div>
            </div>

            <div class="vehicle-data-box vehicle-summary no-display">
                <h3>Summary</h3>
                <div class="info-items">
                    <div class="info-item">
                        <span class="label">Price per ticket</span>
                        <span class="val"><span class="price ticketpress-ticket-price"><?php echo esc_html( $vehicle->price ); ?></span> TK</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Seats selected</span>
                        <span class="val ticketpress-seats-selected">0</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Total price</span>
                        <span class="val"><span class="price ticketpress-total-price">0</span> TK</span>
                    </div>
                </div>
                <div class="seat-actions">
                    <input type="hidden" name="vehicle_id" value="<?php echo esc_attr( $vehicle->id ); ?>">
                    <div class="ticketpress-btn btn-booking" data-action="continue">Continue Booking</div>
                </div>
            </div>
        </div>

        <div class="side-right">
            <div class="vehicle-data-box vehicle-seats no-display">
                <h3>Select Seat(s)</h3>
                <div class="seat-row">
					<?php for ( $i = 0; $i < $vehicle->total_seats; $i ++ ) : ?>
						<?php if ( $i != 0 && $i % $vehicle->get_seats_columns() == 0 ) {
							echo '</div><div class="seat-row">';
						} ?>

                        <label class="seat">
                            <input type="checkbox" name="seats_selected" value="<?php echo $i + 1; ?>">
                            <span><?php echo get_seat_label( ( $i + 1 ), $vehicle->get_seats_columns() ); ?></span>
                        </label>
					<?php endfor; ?>
                </div>
            </div>

            <form class="vehicle-data-box passengers-info no-display">
                <h3>Passengers Information</h3>
                <div class="passengers"></div>
            </form>
        </div>

    </div>

<?php
get_footer();
