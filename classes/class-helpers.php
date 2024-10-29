<?php
/**
 * Helpers Class.
 *
 * @since 1.0.0
 *
 * @package AITT
 */

namespace AITT;

/**
 * Class Helpers
 *
 * Helper class containing useful static methods.
 *
 * @since 1.0.0
 */
class Helpers {

	/**
	 * Generate titles with markup.
	 *
	 * @since 1.0.0
	 *
	 * @param array $widget_data Array of widget titles.
	 * @param bool  $return_json Whether to return with JSON encoding.
	 */
	public static function aitt_generate_titles_markup( array $widget_data, bool $return_json = false ) {

		// Abort if we have no widget data.
		if ( empty( $widget_data ) ) {
			return;
		}

		// Sort the nested array by the timestamp value (key [0] ),
		// in ascending order.
		usort(
			$widget_data,
			function( $a, $b ) {
				return $b[2] <=> $a[2];
			}
		);

		// Generate the markup for the entries.
		$titles = '';

		foreach ( $widget_data as $entry ) {

			if ( empty( $entry ) ) {
				continue;
			}

			$titles .= '<li class="aitt-list-titles--entry dashicons-before" data-private=' . esc_attr( empty( $entry[1] ) ? 'false' : 'true' ) . '>';
			$titles .= '<span class="title">' . esc_html( $entry[0] ) . '</span>';
			$titles .= '<button title="' . __( 'Create New Post', 'aitt' ) . '" class="dashicons-before aitt-list-titles--create-post">';
			$titles .= '<span class="screen-reader-text">' . __( 'Create', 'aitt' ) . '</span></button>';
			$titles .= '<button title="' . __( 'Remove This Title', 'aitt' ) . '" class="dashicons-before aitt-list-titles--delete-title">';
			$titles .= '<span class="screen-reader-text">' . __( 'Remove', 'aitt' ) . '</span></button>';
			$titles .= '</li>';
		}

		// Return the data, JSON encoded or as-is.
		if ( $return_json ) {
			return wp_json_encode( $titles );
		} else {
			return $titles;
		}
	}

	/**
	 * Update widget data: option or user meta.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $widget_data  Array of titles data.
	 * @param string $private     Is the title private or global.
	 */
	public static function aitt_update_widget_data( array $widget_data, string $private ) : void {

		if ( $private ) {
			update_user_meta( get_current_user_id(), '_aitt_dashboard_widget_data', $widget_data );
		} else {
			update_option( 'aitt_dashboard_widget_data', $widget_data, false );
		}
	}
}
