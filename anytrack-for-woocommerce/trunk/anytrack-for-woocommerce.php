<?php
/*
Plugin Name: AnyTrack for WooCommerce
Description: Connect with Google, Facebook, Bing, Taboola and Outbrain and sync all your ad campaigns directly from WooCommerce.
Version: 1.5.4
Author: AnyTrack Ltd.
Author URI: https://anytrack.io
Stable tag: 1.5.4
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');


if (! defined('ABSPATH')) {
    wp_die('Direct Access is not Allowed');
}

// core initiation

class anytrack_for_woocommerce_MainStart
{
    public $locale;
    public function __construct($locale, $includes, $path)
    {
        $this->locale = $locale;

        // include files
        foreach ($includes as $single_path) {
            include $path.$single_path;
        }
        // calling localization
        add_action('plugins_loaded', array( $this, 'myplugin_init' ));

        register_activation_hook( __FILE__ , array( $this, 'plugin_activation' ));
    }

    public function plugin_activation()
    {
        flush_rewrite_rules();

        if( !get_option('waap_options') ){
            $inital_values = [
                'add_to_cart' => 'AddToCart',
                'initiate_checkout' => 'InitiateCheckout',
                'purchase' => 'Purchase',
            ];
            update_option('waap_options', $inital_values);
        }
        
    }

    public function plugin_uninstall()
    {
    }

    public function myplugin_init()
    {
        $plugin_dir = basename(dirname(__FILE__));
        load_plugin_textdomain($this->locale, false, $plugin_dir);
    }
}




// initiate main class

$obj = new anytrack_for_woocommerce_MainStart(
    'afwp', array(
    'modules/class-form-elements.php',
    //'modules/class-core-helper.php',

    'modules/scripts.php',

    'modules/hooks.php',
    'modules/ajax.php',
    'modules/settings.php',
    'modules/functions.php',
    ), dirname(__FILE__).'/'
);
