<?php
/**
 * Easy Q&A
 *
 * Easily create a responsive Q&A section for your Wordpress site. Key features
 * include a built-in rating system, email notifications, social media sharing,
 * search, topic categories, and the ability for visitors to ask questions.
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @author jason@emptyset.co
 *
 * @wordpress-plugin
 * Plugin Name: Easy Q&A
 * Plugin URI: http://emptyset.co/wordpress/easy-qa
 * Description: Easily create a responsive Q&A section for your Wordpress site. Key features include a built-in rating system, email notifications, social media sharing, search, topic categories, and the ability for visitors to ask questions.
 * Version: 1.0.0
 * Author: Jason Adkison
 * Author URI: http://emptyset.co/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: easy-qa
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

// Load helper functions
require_once plugin_dir_path( __FILE__ ) . 'includes/easy-qa-helpers.php';

/**
 * The code that runs during plugin activation.
 */
function activate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-qa-activator.php';
  Easy_QA_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-qa-deactivator.php';
  Easy_QA_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization and hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-qa.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

  $plugin = new Easy_QA();
  $plugin->run();

}
run_plugin_name();
