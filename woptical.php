<?php

/**
 * Woocommerce Overrides for Optical
 *
 * Woocommerce hooks and overrides for optical shops
 *
 *
 * @link              https://robertochoaweb.com/
 * @since             1.0.0
 * @package           Woptical
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Overrides for Optical
 * Plugin URI:        https://robertochoaweb.com/casos/woptical/
 * Description:       Woocommerce hooks and overrides for optical shops
 * Version:           1.0.0
 * Author:            Robert Ochoa
 * Author URI:        https://robertochoaweb.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woptical
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOPTICAL_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woptical-activator.php
 */
function activate_woptical()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-woptical-activator.php';
    Woptical_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woptical-deactivator.php
 */
function deactivate_woptical()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-woptical-deactivator.php';
    Woptical_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woptical');
register_deactivation_hook(__FILE__, 'deactivate_woptical');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woptical.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_woptical()
{
    $plugin = new Woptical();
    $plugin->run();
}
run_woptical();
