<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

defined( 'ABSPATH' ) || exit;

class CSTOOLS_Poll_meta {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'wp_ajax_cstools_file_upload', array( $this, 'file_upload' ) );
		add_action( 'save_post', array( $this, 'save_meta_data' ) );

		add_action( 'manage_orders_posts_columns', array( $this, 'add_orders_columns' ), 10, 1 );
		add_action( 'manage_orders_posts_custom_column', array( $this, 'orders_columns_content' ), 10, 2 );
		add_action( 'pre_get_posts', array( $this, 'update_orders_by_role' ), 999 );
		add_filter( 'post_row_actions', array( $this, 'remove_post_row_actions' ), 10, 2 );
	}


	/**
	 * Alter post row actions for orders post type
	 *
	 * @param $actions
	 * @param WP_Post $post
	 *
	 * @return mixed
	 */
	function remove_post_row_actions( $actions, WP_Post $post ) {

		if ( $post->post_type === 'orders' ) {
			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['view'] );
		}

		return $actions;
	}


	/**
	 * Display orders based on role
	 *
	 * @param WP_Query $wp_query
	 */
	function update_orders_by_role( WP_Query $wp_query ) {

		if ( current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( is_admin() && $wp_query->is_main_query() && $wp_query->get( 'post_type' ) === 'orders' ) {

			$meta_query = $wp_query->get( 'meta_query' );
			$meta_query = empty( $meta_query ) || ! is_array( $meta_query ) ? array() : $meta_query;

			$meta_query[] = array(
				'key'     => '_client',
				'value'   => get_current_user_id(),
				'compare' => '=',
			);

			$wp_query->set( 'meta_query', $meta_query );
		}
	}


	/**
	 * Updated orders columns
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	function add_orders_columns( $columns ) {

		return array(
			'cb'       => cstools()->get_args_option( 'cb', $columns ),
			'id'       => esc_html__( 'Order ID', 'cstools' ),
			'client'   => esc_html__( 'Client', 'cstools' ),
			'status'   => esc_html__( 'Status', 'cstools' ),
			'images'   => esc_html__( 'Total Images', 'cstools' ),
			'download' => esc_html__( 'Download', 'cstools' ),
			'deadline' => esc_html__( 'Deadline', 'cstools' ),
		);
	}


	/**
	 * Orders columns content
	 *
	 * @param $column_id
	 * @param $post_id
	 */
	function orders_columns_content( $column_id, $post_id ) {

		if ( $column_id === 'id' ) {
			printf( '<strong><a class="row-title" href="%s">Order #%s</a></strong>', get_edit_post_link( $post_id ), $post_id );
		}

		if ( $column_id === 'client' ) {

			$client_id = cstools()->get_meta( '_client', $post_id );
			$client    = get_user_by( 'ID', $client_id );

			printf( '<strong>%s</strong>', $client->display_name );
			printf( '<i class="cstools-meta">%s</i>', $client->user_email );
		}

		if ( $column_id === 'images' ) {
			printf( _n( '<i>%s image</i>', '<i>%s images</i>', $count = cstools()->get_meta( '_total_images', $post_id, 0 ), 'cstools' ), number_format_i18n( $count ) );
		}

		if ( $column_id === 'status' ) {
			printf( '<strong>%s</strong>', ucwords( cstools()->get_meta( '_status', $post_id ) ) );
		}

		if ( $column_id === 'download' ) {
			if ( ! empty( $attachment_url = cstools()->get_meta( 'images', $post_id ) ) ) {
				printf( '<a href="%s" target="_blank" download class="cstools-btn">Download Images</a>', $attachment_url );
			} else {
				printf( '<span class="cstools-error">%s</span>', esc_html__( 'No attachment found!', 'cstools' ) );
			}
		}

		if ( $column_id === 'deadline' ) {

			if ( empty( $deadline = cstools()->get_meta( '_deadline', $post_id ) ) ) {
				printf( '<span class="cstools-error">%s</span>', esc_html__( 'No deadline found!', 'cstools' ) );
			} else {
				printf( '<strong>%s</strong>', date( 'jS F, Y', strtotime( $deadline ) ) );
			}

			printf( '<i class="cstools-meta">Order placed %s ago</i>', human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
		}
	}


	/**
	 * Save meta box
	 *
	 * @param $post_id
	 */
	public function save_meta_data( $post_id ) {

		global $current_user;

		$posted_data = wp_unslash( $_POST );

		if ( ! wp_verify_nonce( cstools()->get_args_option( 'order_nonce_val', $posted_data ), 'order_nonce' ) ) {
			return;
		}

		foreach ( cstools()->get_poll_meta_fields() as $field ) {

			$field_id    = cstools()->get_args_option( 'id', $field );
			$field_type  = cstools()->get_args_option( 'type', $field );
			$field_value = cstools()->get_args_option( $field_id, $posted_data );

			if ( $field_type == 'file' ) {

				$file_name       = isset( $_FILES['images']['name'] ) ? $_FILES['images']['name'] : '';
				$file_size       = isset( $_FILES['images']['size'] ) ? $_FILES['images']['size'] : '';
				$file_name_parts = explode( '.', $file_name );
				$file_ext        = strtolower( end( $file_name_parts ) );

				if ( in_array( $file_ext, array( 'jpeg', 'jpg', 'png', 'zip', 'rar' ) ) && $file_size < ( 2 * ( 1024 * 1024 ) ) ) {

					$file_tmp  = isset( $_FILES['images']['tmp_name'] ) ? $_FILES['images']['tmp_name'] : '';
					$file_path = get_user_meta( $current_user->ID, 'user_directory', true );
					$file_path .= '/' . $post_id;

					if ( ! file_exists( $file_path ) ) {
						wp_mkdir_p( $file_path );
					}

					$file_path .= '/' . $file_name;
					$file_url  = str_replace( ABSPATH, site_url( '/' ), $file_path );

					if ( move_uploaded_file( $file_tmp, $file_path ) ) {
						update_post_meta( $post_id, $field_id, $file_url );
					}
				}
			} else {
				update_post_meta( $post_id, $field_id, $field_value );
			}
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			update_post_meta( $post_id, '_client', get_current_user_id() );
		}

		if ( empty( $_status = cstools()->get_args_option( '_status', $posted_data ) ) ) {
			update_post_meta( $post_id, '_status', $_status );
		}
	}


	/**
	 * Meta box output
	 *
	 * @param $post
	 */
	public function render_order_metabox( $post ) {

		wp_nonce_field( 'order_nonce', 'order_nonce_val' );

		cstools()->WP_Settings()->generate_fields( $this->get_meta_fields(), $post->ID );
	}


	/**
	 * Meta box output
	 *
	 * @param $post
	 */
	public function render_info_metabox( WP_Post $post ) {

		$client_id      = cstools()->get_meta( '_client', $post->ID );
		$client         = get_user_by( 'ID', $client_id );
		$attachment_url = wp_get_attachment_url( cstools()->get_meta( '_images', $post->ID ) );
		$deadline       = cstools()->get_meta( '_deadline', $post->ID );
		$meta_data      = array();

		if ( $client instanceof WP_User ) {
			$meta_data[] = array(
				'label' => esc_html__( 'Client Name', 'cstools' ),
				'value' => $client->display_name,
			);
			$meta_data[] = array(
				'label' => esc_html__( 'Email address', 'cstools' ),
				'value' => $client->user_email,
			);
		}

		$meta_data[] = array(
			'label' => esc_html__( 'Images Count', 'cstools' ),
			'value' => sprintf( _n( '<i>%s image</i>', '<i>%s images</i>', $count = cstools()->get_meta( '_total_images', $post->ID, 0 ), 'cstools' ), number_format_i18n( $count ) ),
		);
		$meta_data[] = array(
			'label' => esc_html__( 'Download Images', 'cstools' ),
			'value' => empty( $attachment_url ) ?
				sprintf( '<span class="cstools-error">%s</span>', esc_html__( 'No attachment found!', 'cstools' ) ) :
				sprintf( '<a href="%s" class="cstools-btn">Download Images</a>', $attachment_url )
		);
		$meta_data[] = array(
			'label' => esc_html__( 'Deadline', 'cstools' ),
			'value' => empty( $deadline ) ?
				sprintf( '<span class="cstools-error">%s</span>', esc_html__( 'No deadline found!', 'cstools' ) ) :
				sprintf( '<strong>%s</strong>', date( 'jS F, Y', strtotime( $deadline ) ) )
		);
		$meta_data[] = array(
			'label' => esc_html__( 'Order Placed', 'cstools' ),
			'value' => get_the_time( 'jS F, Y' ),
		);

		$meta_data = array_map( function ( $meta ) {
			return sprintf( '<div class="meta-data"><div class="label">%s</div><div class="value">%s</div></div>',
				cstools()->get_args_option( 'label', $meta ),
				cstools()->get_args_option( 'value', $meta )
			);
		}, $meta_data );

		printf( '<div class="meta-data-wrap">%s</div>', implode( '', $meta_data ) );
	}


	/**
	 * Add meta boxes
	 *
	 * @param $post_type
	 */
	public function add_meta_boxes( $post_type ) {

		if ( $post_type == 'orders' ) {
			add_meta_box( 'order-metabox', esc_html__( 'Order Data Box', 'cstools' ), array( $this, 'render_order_metabox' ), $post_type, 'normal', 'high' );
			add_meta_box( 'info-metabox', esc_html__( 'Order Info Box', 'cstools' ), array( $this, 'render_info_metabox' ), $post_type, 'side', 'high' );
		}
	}


	/**
	 * Return meta fields for direct use to PB_Settings
	 *
	 * @param string $fields_for
	 *
	 * @return mixed|void
	 */
	function get_meta_fields( $fields_for = 'orders' ) {
		return array( array( 'options' => cstools()->get_poll_meta_fields( $fields_for ) ) );
	}
}

new CSTOOLS_Poll_meta();