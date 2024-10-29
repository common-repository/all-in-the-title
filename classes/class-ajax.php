<?php
/**
 * Ajax Class.
 *
 * @since 1.0.0
 *
 * @package AITT
 */

namespace AITT;

/**
 * Class Ajax
 *
 * Handles the response to AJAX calls from the dashboard widget.
 *
 * @since 1.0.0
 */
class Ajax {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Unleash Hell.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		add_action( 'wp_ajax_aitt_ajax', array( $this, 'aitt_update_dashboard_widget_data' ), 10 );
	}

	/**
	 * Update the option data via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function aitt_update_dashboard_widget_data() : void {

		// Abort if we're not in an admin context.
		if ( ! is_admin() ) {

			wp_send_json_error(
				[
					'message' => __( 'Request was not made from within the /wp-admin/ area!', 'aitt' ),
				]
			);
		}

		// Get the nonce.
		$nonce = isset( $_POST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpnonce'] ) ) : false;

		// Abort if nonce is invalid.
		if ( ! wp_verify_nonce( $nonce, 'aitt_ajax_security' ) ) {

			wp_send_json_error(
				[
					'message' => __( 'Invalid nonce supplied!', 'aitt' ),
				]
			);
		}

		// Get the action type.
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

		// Abort if action type is invalid.
		if ( empty( $type ) ) {

			wp_send_json_error(
				[
					'message' => __( 'Invalid type supplied!', 'aitt' ),
				]
			);
		}

		// Get the title we're processing.
		$title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';

		// Abort if title is empty.
		if ( empty( $title ) ) {

			wp_send_json_error(
				[
					'message' => __( 'Invalid title supplied!', 'aitt' ),
				]
			);
		}

		// Get the timestamp (milliseconds).
		$timestamp = ! empty( $_POST['timestamp'] ) ? sanitize_text_field( wp_unslash( $_POST['timestamp'] ) ) : false;

		// Is this title private or global?
		$private = isset( $_POST['private'] ) && 'true' === $_POST['private'] ? true : false;

		// Fetch data.
		$global_widget_data = get_option( 'aitt_dashboard_widget_data', [] );
		$user_widget_data   = get_user_meta( get_current_user_id(), '_aitt_dashboard_widget_data', true );
		$user_widget_data   = ! empty( $user_widget_data ) ? $user_widget_data : [];

		// Which data-set are we updating for this action? Which is the other data-set?
		$widget_data       = $private ? $user_widget_data : $global_widget_data;
		$other_widget_data = ! $private ? $user_widget_data : $global_widget_data;

		// Search for the supplied title.
		$key = array_search( $title, array_column( $widget_data, 0 ), true );

		switch ( $type ) {

			case 'add':
				// Check if the title already exists before adding.
				if ( false !== $key ) {

					// Return error.
					$data = array_merge( $widget_data, $other_widget_data );
					wp_send_json_error(
						[
							'message' => __( 'Unable to add title as it already exists!', 'aitt' ),
							'titles'  => Helpers::aitt_generate_titles_markup( $data, true ),
						]
					);
				} else {

					// Add to the beginning of our array.
					array_unshift( $widget_data, [ $title, $private, $timestamp ] );

					// Re-index the array keys.
					$widget_data = array_values( $widget_data );

					// Update the option.
					Helpers::aitt_update_widget_data( $widget_data, $private );

					// Return titles.
					$data = array_merge( $widget_data, $other_widget_data );
					wp_send_json_success(
						[
							'message' => __( 'Title added!', 'aitt' ),
							'titles'  => Helpers::aitt_generate_titles_markup( $data, true ),
						]
					);
				}
				break;

			case 'remove':
				// Check if the title exists before removing.
				if ( false !== $key ) {

					// Remove the title.
					unset( $widget_data[ $key ] );

					// Re-index the array keys.
					$widget_data = array_values( $widget_data );

					// Update the option.
					Helpers::aitt_update_widget_data( $widget_data, $private, $timestamp );

					// Return titles.
					$data = array_merge( $widget_data, $other_widget_data );
					wp_send_json_success(
						[
							'message' => __( 'Title removed!', 'aitt' ),
							'titles'  => Helpers::aitt_generate_titles_markup( $data, true ),
						]
					);
				} else {

					// Return error.
					$data = array_merge( $widget_data, $other_widget_data );
					wp_send_json_error(
						[
							'message' => __( 'Unable to remove title as it does not exist!', 'aitt' ),
							'titles'  => Helpers::aitt_generate_titles_markup( $data, true ),
						]
					);
				}
				break;

			case 'create':
				// Check if the title exists before creating.
				if ( false !== $key ) {

					// Create the post.
					$post_obj = array(
						'post_title'   => wp_strip_all_tags( $title ),
						'post_content' => __( 'You have the title, now write the rest!', 'aitt' ),
						'post_status'  => 'draft',
					);

					// Insert post.
					$created_post_id = wp_insert_post( $post_obj, true );

					if ( is_wp_error( $created_post_id ) ) {

						// Return error.
						$data = array_merge( $widget_data, $other_widget_data );
						wp_send_json_error(
							[
								'message' => __( 'Unable to create post!', 'aitt' ),
								'titles'  => Helpers::aitt_generate_titles_markup( $data, true ),
							]
						);
					} else {

						// Remove the title.
						unset( $widget_data[ $key ] );

						// Re-index the array keys.
						$widget_data = array_values( $widget_data );

						// Update the option.
						Helpers::aitt_update_widget_data( $widget_data, $private, $timestamp );

						// Where are we redirecting to?
						$redirect_url = home_url( '/wp-admin/post.php?post=' . $created_post_id . '&action=edit' );

						// Return titles.
						$data = array_merge( $widget_data, $other_widget_data );
						wp_send_json_success(
							[
								'message'      => __( 'Post created!', 'aitt' ),
								'titles'       => Helpers::aitt_generate_titles_markup( $data, true ),
								'redirect_url' => esc_url( $redirect_url ),
							]
						);
					}
				} else {

					// Return error.
					$data = array_merge( $widget_data, $other_widget_data );
					wp_send_json_error(
						[
							'message' => __( 'Unable to create post as the title does not exist!', 'aitt' ),
							'titles'  => Helpers::aitt_generate_titles_markup( $data, true ),
						]
					);
				}
				break;

			default:
				// Return error.
				wp_send_json_error(
					[
						'message' => __( 'Greetings Professor Falken. Would you like to play a game?', 'aitt' ),
					]
				);
				break;
		}
	}
}
