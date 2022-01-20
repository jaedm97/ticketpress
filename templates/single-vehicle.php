<?php
/**
 * Vehicle single template
 */

get_header();

$vehicle = new TicketPress\Vehicle();


echo $vehicle->post->post_title;
echo $vehicle->number;






get_footer();
