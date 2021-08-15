<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           One_Rma
 *
 * @wordpress-plugin
 * Plugin Name:       One Rma
 * Plugin URI:        https://www.fiverr.com
 * Description:       Integration with Devoluciones module of ONE CRM.
 * Version:           1.0.0
 * Author:            Md Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       one-rma
 * Domain Path:       /languages
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
define( 'ONE_RMA_VERSION', '1.0.0' );
define('LIST_ENDPOINT', '/api/rmas/');
define('PRODUCT_ENDPOINT', '/api/sales/');
define('SALES_PRODUCT_ENDPOINT', '/api/sales_products/');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-one-rma-activator.php
 */
function activate_one_rma() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-one-rma-activator.php';
	One_Rma_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-one-rma-deactivator.php
 */
function deactivate_one_rma() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-one-rma-deactivator.php';
	One_Rma_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_one_rma' );
register_deactivation_hook( __FILE__, 'deactivate_one_rma' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-one-rma.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_one_rma() {

	$plugin = new One_Rma();
	$plugin->run();

}
run_one_rma();
