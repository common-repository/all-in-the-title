<?php
/**
 * Enqueues Class.
 *
 * @since 1.0.0
 *
 * @package AITT
 */

namespace AITT;

/**
 * Class Enqueues
 *
 * Enqueue assets used in the plugin.
 *
 * @since 1.0.0
 *
 * @package AITT
 */
class Enqueues {

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
		add_action( 'admin_enqueue_scripts', array( $this, 'aitt_enqueue_admin_assets' ), 10 );
	}

	/**
	 * Enqueue JS/CSS for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function aitt_enqueue_admin_assets() {

		// Ensure that we only enqueue the assets on the dashboard.
		$screen = get_current_screen();

		if ( 'dashboard' !== $screen->id ) {
			return;
		}

		// Enqueue Admin JS.
		$admin_js_url  = plugins_url( '/assets/dist/' . AITT_SLUG . '-admin.js', AITT_ROOT );
		$admin_js_path = dirname( AITT_ROOT ) . '/assets/dist/' . AITT_SLUG . '-admin.js';

		wp_enqueue_script(
			AITT_SLUG . '-admin-js',
			$admin_js_url,
			[ 'jquery' ],
			filemtime( $admin_js_path ),
			true
		);

		// Localize parameters for AJAX request.
		wp_localize_script(
			AITT_SLUG . '-admin-js',
			'aitt_js_data',
			[
				'action' => 'aitt_ajax',
				'nonce'  => wp_create_nonce( 'aitt_ajax_security' ),
			]
		);

		$admin_css_url  = plugins_url( '/assets/dist/' . AITT_SLUG . '-admin.css', AITT_ROOT );
		$admin_css_path = dirname( AITT_ROOT ) . '/assets/dist/' . AITT_SLUG . '-admin.css';

		// Enqueue Admin CSS.
		wp_enqueue_style(
			AITT_SLUG . '-admin-css',
			$admin_css_url,
			[],
			filemtime( $admin_css_path )
		);
	}
}
