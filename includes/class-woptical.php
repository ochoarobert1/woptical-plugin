<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://robertochoaweb.com/
 * @since      1.0.0
 *
 * @package    Woptical
 * @subpackage Woptical/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woptical
 * @subpackage Woptical/includes
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */
class Woptical
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woptical_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WOPTICAL_VERSION')) {
            $this->version = WOPTICAL_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'woptical';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woptical_Loader. Orchestrates the hooks of the plugin.
     * - Woptical_i18n. Defines internationalization functionality.
     * - Woptical_Admin. Defines all hooks for the admin area.
     * - Woptical_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woptical-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woptical-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woptical-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woptical-woocommerce-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-woptical-public.php';

        $this->loader = new Woptical_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woptical_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Woptical_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT taxonomy FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy LIKE '%pa_%' GROUP BY taxonomy", 'OBJECT');
        $attributes_tax_slugs = array_keys( wc_get_attribute_taxonomy_labels() );
        $plugin_admin = new Woptical_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_woo_admin = new Woptical_Woocommerce_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 99);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 99);

        $this->loader->add_action('woocommerce_after_add_attribute_fields', $plugin_admin, 'woocommerce_custom_product_attribute_field');
        $this->loader->add_action('woocommerce_after_edit_attribute_fields', $plugin_admin, 'woocommerce_custom_product_attribute_field');

        $this->loader->add_action('woocommerce_attribute_added', $plugin_admin, 'save_woocommerce_custom_product_attribute');
        $this->loader->add_action('woocommerce_attribute_updated', $plugin_admin, 'save_woocommerce_custom_product_attribute');

        foreach ($attributes_tax_slugs as $key => $value) {
            $action_add_image = 'pa_' . $value . '_add_form_fields';
            $action_edit_image = 'pa_' . $value . '_edit_form_fields';
            $action_created_image = 'created_' . 'pa_' . $value;
            $action_edited_image = 'edited_' . 'pa_' . $value;
            $action_manage_edit = 'manage_edit-' . 'pa_' . $value . '_columns';
            $action_manage_custom = 'manage_' . 'pa_' . $value . '_custom_column';
            $this->loader->add_action($action_add_image, $plugin_admin, 'add_woocommerce_terms_image', 10, 2);
            $this->loader->add_action($action_created_image, $plugin_admin, 'save_woocommerce_terms_image', 10, 2);
            $this->loader->add_action($action_edit_image, $plugin_admin, 'update_woocommerce_terms_image', 10, 2);
            $this->loader->add_action($action_edited_image, $plugin_admin, 'updated_edited_woocommerce_terms_image', 10, 2);
            $this->loader->add_filter($action_manage_edit, $plugin_admin, 'display_image_column_heading');
            $this->loader->add_action($action_manage_custom, $plugin_admin, 'display_image_column_value', 10, 3);
        }

        $this->loader->add_action('admin_menu', $plugin_admin, 'register_my_custom_submenu_page', 99);
        $this->loader->add_action('wp_ajax_custom_pricing_table_save_data', $plugin_admin, 'custom_pricing_table_save_data_handler', 99);

        $this->loader->add_action('woocommerce_product_data_tabs', $plugin_woo_admin, 'woptical_custom_product_tab', 10, 1);
        $this->loader->add_action('woocommerce_product_data_panels', $plugin_woo_admin, 'woptical_custom_tab_data');
        $this->loader->add_action('woocommerce_process_product_meta_simple', $plugin_woo_admin, 'woptical_save_proddata_custom_fields');
        $this->loader->add_action('woocommerce_process_product_meta_variable', $plugin_woo_admin, 'woptical_save_proddata_custom_fields');

        $this->loader->add_action('wp_ajax_get_attributes_spheres', $plugin_woo_admin, 'ajax_get_attributes_spheres_handler');
        $this->loader->add_action('wp_ajax_get_attributes_cilinder', $plugin_woo_admin, 'ajax_get_attributes_cilinder_handler');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Woptical_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_action('woocommerce_single_product_summary', $plugin_public, 'custom_optical_selections', 27);

        
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woptical_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
