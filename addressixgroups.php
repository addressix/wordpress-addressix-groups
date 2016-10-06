<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.meworla.com
 * @since             1.0.0
 * @package           Addressixgroups
 *
 * @wordpress-plugin
 * Plugin Name:       Addressixgroups
 * Plugin URI:        www.addressix.com
 * Description:       This plugin displays the public member list of an addressix group
 * Version:           1.0.1
 * Author:            Meworla GmbH
 * Author URI:        http://www.meworla.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       addressixgroups
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-addressixgroups-activator.php
 */
function activate_addressixgroups() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addressixgroups-activator.php';
	Addressixgroups_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-addressixgroups-deactivator.php
 */
function deactivate_addressixgroups() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addressixgroups-deactivator.php';
	Addressixgroups_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_addressixgroups' );
register_deactivation_hook( __FILE__, 'deactivate_addressixgroups' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-addressixgroups.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_addressixgroups() {

	$plugin = new Addressixgroups();
	$plugin->run();

}
run_addressixgroups();
