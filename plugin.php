<?php
/**
 * All In The Title
 *
 * @link https://github.com/davetgreen/all-in-the-title
 *
 * @since 1.0.0
 *
 * @package AITT
 *
 * Plugin Name:  All In The Title
 * Plugin URI:   https://github.com/davetgreen/all-in-the-title
 * Description:  Quickly save blog post titles for inspiration and later use, without clogging up your database with drafts.
 * Version:      1.0.0
 * Contributors: davetgreen
 * Author:       Dave Green <hello@davetgreen.me>
 * Author URI:   https://davetgreen.me
 * License:      GPL-3.0+
 * License URI:  http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:  aitt
 * Domain Path:  /languages
 */

/**
 * Abort if this file is called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants.
 */
define( 'AITT_ROOT', __FILE__ );
define( 'AITT_NAME', __( 'All In The Title', 'aitt' ) );
define( 'AITT_SLUG', 'aitt' );

/**
 * Textdomain.
 */
load_plugin_textdomain(
	'aitt',
	false,
	AITT_ROOT . '/languages'
);

/**
 * Classes.
 */
require_once 'classes/class-ajax.php';
require_once 'classes/class-dashboard-widget.php';
require_once 'classes/class-enqueues.php';
require_once 'classes/class-helpers.php';

/**
 * Namespaces.
 */
use AITT\Ajax;
use AITT\Dashboard_Widget;
use AITT\Enqueues;
use AITT\Helpers;

/**
 * Instances.
 */
$aitt_ajax             = new Ajax();
$aitt_dashboard_widget = new Dashboard_Widget();
$aitt_enqueues         = new Enqueues();

/**
 * Unleash Hell.
 */
$aitt_ajax->run();
$aitt_dashboard_widget->run();
$aitt_enqueues->run();
