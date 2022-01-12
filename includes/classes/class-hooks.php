<?php
/**
 * Class Hooks
 */


if ( ! class_exists( 'CSTOOLS_Hooks' ) ) {
	/**
	 * Class CSTOOLS_Hooks
	 */
	class CSTOOLS_Hooks {

		/**
		 * CSTOOLS_Hooks constructor.
		 */
		function __construct() {

			add_action( 'init', array( $this, 'register_everything' ) );
			add_action( 'admin_head', array( $this, '__admin__root_vars' ) );
//			add_action( 'pre_get_posts', array( $this, 'restrict_media_to_own' ) );
			add_action( 'post_edit_form_tag', array( $this, 'enable_file_upload' ) );
			add_filter( 'admin_body_class', array( $this, 'add_body_class_to_admin' ) );
			add_filter( 'post_updated_messages', array( $this, 'change_updated_messages' ) );
		}


		/**
		 * Update order message
		 *
		 * @param $messages
		 *
		 * @return array
		 */
		function change_updated_messages( $messages ) {

			global $post;

			if ( $post->post_type == 'orders' ) {

				if ( isset( $messages['post'][1] ) ) {
					$messages['post'][1] = esc_html__( 'Order updated successfully' );
				}

				if ( isset( $messages['post'][6] ) ) {
					$messages['post'][6] = esc_html__( 'New order created successfully.' );
				}
			}

			return $messages;
		}


		function add_body_class_to_admin( $classes ) {

			global $current_user;

			if ( in_array( 'client', $current_user->roles ) ) {
				$classes .= ' type-client';
			}

			return $classes;
		}


		/**
		 * Enable file upload in post edit form
		 */
		function enable_file_upload() {
			echo 'enctype="multipart/form-data"';
		}


		/**
		 * Restrict users to his own attachments
		 *
		 * @param WP_Query $query
		 *
		 * @return WP_Query
		 */
		function restrict_media_to_own( WP_Query $query ) {

			if ( $query->get( 'post_type' ) === 'attachment' && ! current_user_can( 'manage_options' ) ) {
				$query->set( 'author', get_current_user_id() );
			}

			return $query;
		}


		/**
		 * Render :root css rules
		 */
		function __admin__root_vars() {

			// Zip Icon
			$root_css[] = sprintf( '%s:url(%s)', esc_attr( '--zip-icon' ), site_url( WPINC . '/images/media/archive.png' ) );
			$root_css[] = sprintf( '%s:url(%s)', esc_attr( '--document-icon' ), site_url( WPINC . '/images/media/document.png' ) );

			printf( '<style>:root{%s}</style>', implode( ';', $root_css ) );

			if ( ! current_user_can( 'manage_options' ) ) {
				printf( '<style>body.wp-admin .menu-top.menu-icon-dashboard { display: none; }</style>' );
			}
		}

		/**
		 * Register Post types, Taxes, Pages and Shortcodes
		 */
		function register_everything() {

			global $current_user;

			// Register post type - orders
			cstools()->WP_Settings()->register_post_type( 'orders', apply_filters( 'cstools_filters_post_type_orders', array(
				'singular'        => esc_html__( 'Orders', 'cstools' ),
				'plural'          => esc_html__( 'Orders', 'cstools' ),
				'labels'          => array(
					'add_new'   => esc_html__( 'Add Order', 'cstools' ),
					'edit_item' => esc_html__( 'Edit Order', 'cstools' ),
				),
				'menu_icon'       => 'dashicons-images-alt',
				'menu_position'   => 5,
				'supports'        => array( '' ),
				'capability_type' => 'orders',
			) ) );


			// Enabling orders for client role
			$client_role = get_role( 'client' );
			$admin_role  = get_role( 'administrator' );

			// Enabling uploads
//			$client_role->add_cap( 'upload_files' );

			foreach ( cstools()->get_orders_capabilities() as $capability ) {
				$client_role->add_cap( $capability );
				$admin_role->add_cap( $capability );
			}


			// Create user directory based on username if it is not created already
			if ( empty( get_user_meta( $current_user->ID, 'user_directory' ) ) ) {

				$user_directory = ABSPATH . 'files/' . $current_user->user_email;

				wp_mkdir_p( $user_directory );

				update_user_meta( $current_user->ID, 'user_directory', $user_directory );
			}
		}
	}

	new CSTOOLS_Hooks();
}