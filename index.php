<?
/**
 * Plugin Name: WP Dynamic Ads
 * Plugin URI: https://sascha-huber.com/projekte/wp-dynamic-ads
 * Text Domain: wp-dynamic-ads
 * Domain Path: /languages
 * Description: Diplay content based on self defined keywords
 * Version: 0.2
 * Author: Sascha Huber
 * Author URI: https://sascha-huber.com
*/

function wpda_load_plugin_textdomain() {
    load_plugin_textdomain( 'wp-dynamic-ads', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wpda_load_plugin_textdomain' );

#include('class/AdMetaProvider.php');
#include('class/OptionsProvider.php');
#include('class/Settings.php');

include('class/MetaBoxPostListRegisterHook.php');
include('class/MetaBoxRegisterHook.php');
include('class/AdPostTypeRegisterHook.php');
include('class/AdShortcodeRegisterHook.php');

?>