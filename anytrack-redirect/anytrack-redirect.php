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
 * @package           AnyTrack_Redirect
 *
 * @wordpress-plugin
 * Plugin Name:       AnyTrack RedirectGenerate safe and nicer offer links with built-in support for the AnyTrack tracker
 * Plugin URI:        https://github.com/anytrack/wordpress-plugins
 * Description:
 * Version:           0.1.0
 * Author:            AnyTrack Inc.
 * Author URI:        https://anytrack.io/
 * License:           MIT
 * License URI:       https://github.com/anytrack/wordpress-plugins/blob/main/anytrack-redirect/LICENSE.txt
 * Text Domain:       anytrack-redirect
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
define( 'ANYTRACK_REDIRECT_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-anytrack-redirect-activator.php
 */
function activate_anytrack_redirect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-anytrack-redirect-activator.php';
	AnyTrack_Redirect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-anytrack-redirect-deactivator.php
 */
function deactivate_anytrack_redirect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-anytrack-redirect-deactivator.php';
	AnyTrack_Redirect_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_anytrack_redirect' );
register_deactivation_hook( __FILE__, 'deactivate_anytrack_redirect' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-anytrack-redirect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_anytrack_redirect() {

	$plugin = new AnyTrack_Redirect();
	$plugin->run();

}
run_anytrack_redirect();
