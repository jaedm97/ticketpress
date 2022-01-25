<?php
/**
 * Vehicle single template
 */

get_header();

$vehicle = new TicketPress\Vehicle();

?>

    <div class="single-vehicle">

        <div class="side-left">
            <div class="vehicle-info">
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

            <div class="vehicle-info">
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
                    <div class="ticketpress-btn btn-continue-booking">Continue Booking</div>
                </div>
            </div>
        </div>

        <div class="side-right">
            <div class="vehicle-seats">
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
        </div>

        <div class="passengers-info">
            <h3>Passengers Information</h3>
            <div class="passengers">
                <div class="single-passenger">
                    <div class="label">
                        <input id="p_name" type="text" name="p_name" placeholder="Full Name">
                    </div>
                    <div class="label">
                        <label>
                            <span>Male</span>
                            <input type="radio" name="p_gender" value="male">
                        </label>
                        <label>
                            <span>Female</span>
                            <input type="radio" name="p_gender" value="female">
                        </label>
                        <label>
                            <span>Others</span>
                            <input type="radio" name="p_gender" value="others">
                        </label>
                    </div>
                    <div class="label">
                        <input id="p_phone" type="text" name="p_phone" placeholder="Phone Number">
                    </div>
                    <div class="label">
                        <input id="p_email" type="text" name="p_email" placeholder="me@mysite.com">
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php
get_footer();
