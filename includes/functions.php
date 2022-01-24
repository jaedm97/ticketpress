<?php
/**
 * All Functions
 *
 * @author Pluginbazar
 */


if ( ! function_exists( 'ticketpress' ) ) {
	/**
	 * Return global $ticketpress
	 *
	 * @return TicketPress_Functions
	 */
	function ticketpress() {

		global $ticketpress;

		if ( empty( $ticketpress ) ) {
			$ticketpress = new TicketPress_Functions();
		}

		return $ticketpress;
	}
}


if ( ! function_exists( 'cstools_get_template_part' ) ) {
	/**
	 * Get Template Part
	 *
	 * @param $slug
	 * @param string $name
	 * @param array $args
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 */
	function cstools_get_template_part( $slug, $name = '', $args = array(), $main_template = false ) {

		$template   = '';
		$plugin_dir = CSTOOLS_PLUGIN_DIR;

		/**
		 * Locate template
		 */
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				"cstools/{$slug}-{$name}.php"
			) );
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		// Search in Poll Pro
		if ( strpos( $backtrace_file, 'wp-poll-pro' ) !== false && defined( 'WPPP_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? CSTOOLS_PLUGIN_DIR : WPPP_PLUGIN_DIR;
		}

		// Search in Survey
		if ( strpos( $backtrace_file, 'wp-poll-survey' ) !== false && defined( 'WPPS_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? CSTOOLS_PLUGIN_DIR : WPPS_PLUGIN_DIR;
		}


		/**
		 * Search for Template in Plugin
		 *
		 * @in Plugin
		 */
		if ( ! $template && $name && file_exists( untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php" ) ) {
			$template = untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php";
		}


		/**
		 * Search for Template in Theme
		 *
		 * @in Theme
		 */
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", "cstools/{$slug}.php" ) );
		}


		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @filter cstools_filters_get_template_part
		 */
		$template = apply_filters( 'cstools_filters_get_template_part', $template, $slug, $name );


		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'cstools_get_template' ) ) {
	/**
	 * Get Template
	 *
	 * @param $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return WP_Error
	 */
	function cstools_get_template( $template_name, $args = array(), $template_path = '', $default_path = '', $main_template = false ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		$located = cstools_locate_template( $template_name, $template_path, $default_path, $backtrace_file, $main_template );


		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', 'cstools' ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'cstools_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'cstools_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'cstools_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'cstools_locate_template' ) ) {
	/**
	 *  Locate template
	 *
	 * @param $template_name
	 * @param string $template_path
	 * @param string $default_path
	 * @param string $backtrace_file
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return mixed|void
	 */
	function cstools_locate_template( $template_name, $template_path = '', $default_path = '', $backtrace_file = '', $main_template = false ) {

		$plugin_dir = CSTOOLS_PLUGIN_DIR;

		/**
		 * Template path in Theme
		 */
		if ( ! $template_path ) {
			$template_path = 'cstools/';
		}

		// Check for Poll Pro
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-pro' ) !== false && defined( 'WPPP_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? CSTOOLS_PLUGIN_DIR : WPPP_PLUGIN_DIR;
		}

		// Check for survey
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-survey' ) !== false && defined( 'WPPS_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? CSTOOLS_PLUGIN_DIR : WPPS_PLUGIN_DIR;
		}

		// Check for MCQ
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-quiz' ) !== false && defined( 'WPPQUIZ_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? CSTOOLS_PLUGIN_DIR : WPPQUIZ_PLUGIN_DIR;
		}


		/**
		 * Template default path from Plugin
		 */
		if ( ! $default_path ) {
			$default_path = untrailingslashit( $plugin_dir ) . '/templates/';
		}

		/**
		 * Look within passed path within the theme - this is priority.
		 */
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		/**
		 * Get default template
		 */
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Return what we found with allowing 3rd party to override
		 *
		 * @filter cstools_filters_locate_template
		 */
		return apply_filters( 'cstools_filters_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'ticketpress_get_vehicle_query' ) ) {
	/**
	 * return vehicle query args
	 *
	 * @param array $args
	 *
	 * @return WP_Query
	 */
	function ticketpress_get_vehicle_query( $args = array() ) {

		$default_args = array(
			'post_type'      => 'vehicle',
			'posts_per_page' => 10,
			'order'          => 'ASC',
			'orderby'        => 'date',
		);

		$args = wp_parse_args( $args, $default_args );

		$extra_args = ticketpress()->get_args_option( 'extra_args', $args );
		$p_start    = ticketpress()->get_args_option( 'p_start', $extra_args );
		$p_end      = ticketpress()->get_args_option( 'p_end', $extra_args );
		$type       = ticketpress()->get_args_option( 'type', $extra_args );
		$route      = ticketpress()->get_args_option( 'route', $extra_args );
		$meta_query = array();
		$tax_query  = array();

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


		$args['meta_query'] = $meta_query;

		$args['tax_query'] = $tax_query;


		return new WP_Query( apply_filters( 'ticketpress_vehicle_query_args', $args ) );
	}
}


if ( ! function_exists( 'ticketpress_get_single_vehicle_html' ) ) {
	function ticketpress_get_single_vehicle_html( $vehicle_id = '' ) {

		$vehicle     = new TicketPress\Vehicle( $vehicle_id );
		$fav_ids     = get_user_meta( get_current_user_id(), 'fav_vehicle_ids', false );
		$is_fav      = in_array( $vehicle->id, $fav_ids ) ? 'fav' : '';
		$is_fav_text = in_array( $vehicle->id, $fav_ids ) ? esc_html__( 'Un Favourite', 'ticketpress' ) : esc_html__( 'Favourite', 'ticketpress' );

		ob_start();
		?>
        <div class="single-vehicle">

            <h3><a href="<?php echo esc_url( $vehicle->get_permalink() ); ?>"><?php echo esc_html( $vehicle->post->post_title ); ?></a></h3>

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

            <div class="actions">
                <span class="action action-fav <?php echo esc_attr( $is_fav ); ?>" data-id="<?php echo esc_attr( $vehicle->id ); ?>"><?php echo $is_fav_text; ?></span>
                <a href="#" class="action">Get Ticket</a>
            </div>

        </div>
		<?php
		return ob_get_clean();
	}
}


function get_seat_label( $seat_index = 0, $seats_per_row = 4 ) {

	$alphabets = 'abcdefghijklmnopqrstuvwxyz';
	$row       = ceil( $seat_index / $seats_per_row );
	$seat_num  = $seat_index % $seats_per_row;
	$seat_num  = $seat_num == 0 ? $seats_per_row : $seat_num;

	return substr( $alphabets, $row - 1, 1 ) . $seat_num;
}