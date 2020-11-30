<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://anytrack.io
 * @since             0.1.0
 * @package           AnyTrack_Redirects
 *
 * @wordpress-plugin
 * Plugin Name:       AnyTrack Redirects
 * Plugin URI:        https://wordpress.org/plugins/anytrack-redirects/
 * Description:       Generate safe and nicer offer links with built-in support for the AnyTrack tracker
 * Version:           0.1.0
 * Author:            AnyTrack Inc.
 * Author URI:        https://anytrack.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       anytrack-redirects
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ANYTRACK_REDIRECTS_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-anytrack-redirects-activator.php
 */
function activate_anytrack_redirects() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-anytrack-redirects-activator.php';
	AnyTrack_Redirects_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-anytrack-redirects-deactivator.php
 */
function deactivate_anytrack_redirects() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-anytrack-redirects-deactivator.php';
	AnyTrack_Redirects_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_anytrack_redirects' );
register_deactivation_hook( __FILE__, 'deactivate_anytrack_redirects' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-anytrack-redirects.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_anytrack_redirects() {

	$plugin = new AnyTrack_Redirects();
	$plugin->run();

}
run_anytrack_redirects();
