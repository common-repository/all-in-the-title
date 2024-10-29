<?php
/**
 * Dashboard_Widget Class.
 *
 * @since 1.0.0
 *
 * @package AITT
 */

namespace AITT;

/**
 * Class Dashboard_Widget
 *
 * Register and render the widget in the dashboard.
 *
 * @since 1.0.0
 */
class Dashboard_Widget {

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
		add_action( 'wp_dashboard_setup', array( $this, 'aitt_register_dashboard_widget' ), 10 );
	}

	/**
	 * Register the Dashboard Widget.
	 *
	 * @since 1.0.0
	 */
	public function aitt_register_dashboard_widget() : void {

		// Only proceed if the user can publish posts.
		if ( ! current_user_can( 'publish_posts', get_current_user_id() ) ) {
			return;
		}

		// Register the widget.
		wp_add_dashboard_widget(
			'aitt_dashboard_widget',
			__( 'All In The Title', 'aitt' ),
			array( $this, 'aitt_register_dashboard_widget_callback' )
		);
	}

	/**
	 * Callback function to generate the contents of the Dashboard Widget.
	 *
	 * @since 1.0.0
	 */
	public function aitt_register_dashboard_widget_callback() : void {

		// Fetch the data and merge it.
		$global_widget_data = get_option( 'aitt_dashboard_widget_data', [] );
		$user_widget_data   = get_user_meta( get_current_user_id(), '_aitt_dashboard_widget_data', true );
		$user_widget_data   = ! empty( $user_widget_data ) ? $user_widget_data : [];
		$widget_data        = array_merge( $global_widget_data, $user_widget_data );

		// Get the markup for the titles.
		$aitt_titles = Helpers::aitt_generate_titles_markup( $widget_data, false );

		// Include the view.
		require_once dirname( AITT_ROOT ) . '/views/widget.php';
	}
}
