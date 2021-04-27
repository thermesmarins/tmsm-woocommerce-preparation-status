<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nicomollet
 * @since             1.0.0
 * @package           Tmsm_Woocommerce_Preparation_Status
 *
 * @wordpress-plugin
 * Plugin Name:       TMSM WooCommerce Preparation Status
 * Plugin URI:        https://github.com/thermesmarins/tmsm-woocommerce-preparation-status
 * Description:       Adds a "Preparation" status to WooCommerce order statuses
 * Version:           1.0.3
 * Author:            Nicolas Mollet
 * Author URI:        https://github.com/nicomollet
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       tmsm-woocommerce-preparation-status
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/thermesmarins/tmsm-woocommerce-preparation-status
 * Github Branch:     master
 * Requires PHP:      7.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TMSM_WOOCOMMERCE_PREPARATION_STATUS_VERSION', '1.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tmsm-woocommerce-preparation-status-activator.php
 */
function activate_tmsm_woocommerce_Preparation_Status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-woocommerce-preparation-status-activator.php';
	Tmsm_Woocommerce_Preparation_Status_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tmsm-woocommerce-preparation-status-deactivator.php
 */
function deactivate_tmsm_woocommerce_Preparation_Status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-woocommerce-preparation-status-deactivator.php';
	Tmsm_Woocommerce_Preparation_Status_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tmsm_woocommerce_Preparation_Status' );
register_deactivation_hook( __FILE__, 'deactivate_tmsm_woocommerce_Preparation_Status' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-woocommerce-preparation-status.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tmsm_woocommerce_Preparation_Status() {

	$plugin = new Tmsm_Woocommerce_Preparation_Status();
	$plugin->run();

}
run_tmsm_woocommerce_Preparation_Status();
