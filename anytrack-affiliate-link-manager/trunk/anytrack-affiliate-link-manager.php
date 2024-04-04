<?php
/*
Plugin Name: AnyTrack Affiliate Link Manager
Plugin URI: https://anytrack.io/
Description: Generate safe and nicer offer links with built-in support for the AnyTrack tracker.
Version: 1.0.4
Author: AnyTrack Ltd.
Stable tag: 1.0.4
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');


if (!defined('ABSPATH')) {
	wp_die('Direct Access is not Allowed');
}

// core initiation

class aalmMainStart
{
	public $locale;
	function __construct($locale, $includes, $path)
	{
		$this->locale = $locale;

		// include files
		foreach ($includes as $single_path) {
			include($path . $single_path);
		}
		// calling localization
		add_action('plugins_loaded', array($this, 'myplugin_init'));

		register_activation_hook(__FILE__, array($this, 'plugin_activation'));
	}

	function plugin_activation()
	{
		flush_rewrite_rules();
	}

	function plugin_uninstall()
	{
	}

	function myplugin_init()
	{
		$plugin_dir = basename(dirname(__FILE__));
		load_plugin_textdomain($this->locale, false, $plugin_dir);
	}
}





// initiate main class

$obj = new aalmMainStart('aalm', array(
	'modules/formElementsClass.php',

	'modules/scripts.php',
	'modules/meta_box.php',
	'modules/ajax.php',
	'modules/cpt.php',
	'modules/settings.php',
	'modules/hooks.php',
	'modules/functions.php',
), dirname(__FILE__) . '/');
