<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://robertochoaweb.com/
 * @since      1.0.0
 *
 * @package    Woptical
 * @subpackage Woptical/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woptical
 * @subpackage Woptical/admin
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */
class Woptical_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woptical-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('handsometable_css', 'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('handsometable_js', 'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woptical-admin.js', array( 'jquery' ), $this->version, false);
        wp_localize_script('custom_wp_admin_js', 'custom_admin_url', array(
            'custom_optical_far_price' => get_option('prices_table_1'),
            'custom_optical_near_price' => get_option('prices_table_2'),
        ));
        wp_enqueue_media();
    }

    /**
     * Register a metabox for woocommerce product attributes in admin area
     *
     * @since    1.0.0
     */
    public function woocommerce_custom_product_attribute_field()
    {
        $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
        $value = $id ? get_option("woocommerce_activate_table_price-$id") : ''; ?>
<tr class="form-field">
    <th scope="row" valign="top">
        <label for="activate_table_price"><?php _e('Activate Table Price in these terms?', 'woptical'); ?></label>
    </th>
    <td>
        <input name="activate_table_price" id="activate_table_price" type="checkbox" <?php echo checked($value, 'yes'); ?> />
        <p class="description"><?php _e('Activate this checkbox if these terms will have a table price', 'woptical'); ?></p>
    </td>
</tr>
<?php
    }

    /**
     * Saving custom input in woocommerce product attributes
     *
     * @since    1.0.0
     */
    public function save_woocommerce_custom_product_attribute($id)
    {
        if (is_admin() && isset($_POST['activate_table_price'])) {
            $option = "woocommerce_activate_table_price-$id";
            update_option($option, 'yes');
        }
    }

    /**
     * Add custom image to woocommerce terms
     *
     * @since    1.0.0
     */
    public function add_woocommerce_terms_image($taxonomy)
    {
        ?>
<div class="form-field term-group">
    <label for="image_id"><?php _e('Image', 'woptical'); ?></label>
    <input type="hidden" id="image_id" name="image_id" class="custom_media_url" value="">
    <div id="image_wrapper"></div>
    <p>
        <input type="button" class="button button-secondary taxonomy_media_button" id="taxonomy_media_button" name="taxonomy_media_button" value="<?php _e('Add', 'woptical'); ?>">
        <input type="button" class="button button-secondary taxonomy_media_remove" id="taxonomy_media_remove" name="taxonomy_media_remove" value="<?php _e('Remove', 'woptical'); ?>">
    </p>
</div>
<?php
    }

    /**
     * Save custom image to woocommerce terms
     *
     * @since    1.0.0
     */
    public function save_woocommerce_terms_image($term_id, $tt_id)
    {
        if (isset($_POST['image_id']) && '' !== $_POST['image_id']) {
            $image = $_POST['image_id'];
            add_term_meta($term_id, 'category_image_id', $image, true);
        }
    }

    /**
     * Add custom field to woocommerce terms on edit
     *
     * @since    1.0.0
     */
    public function update_woocommerce_terms_image($term, $taxonomy) { ?>
	<tr class="form-field term-group-wrap">
		<th scope="row">
			<label for="image_id"><?php _e('Image', 'woptical'); ?></label>
		</th>
		<td>
			<?php $image_id = get_term_meta($term -> term_id, 'image_id', true); ?>
			<input type="hidden" id="image_id" name="image_id" value="<?php echo $image_id; ?>">

			<div id="image_wrapper">
				<?php if ($image_id) { ?>
				<?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
				<?php } ?>
			</div>

			<p>
				<input type="button" class="button button-secondary taxonomy_media_button" id="taxonomy_media_button" name="taxonomy_media_button" value="<?php _e('Add', 'woptical'); ?>">
				<input type="button" class="button button-secondary taxonomy_media_remove" id="taxonomy_media_remove" name="taxonomy_media_remove" value="<?php _e('Remove', 'woptical'); ?>">
			</p>

		</td>
	</tr>
	<?php
    }

    /**
     * Save custom field to woocommerce terms on edit
     *
     * @since    1.0.0
     */
    public function updated_edited_woocommerce_terms_image($term_id, $tt_id)
    {
        if (isset($_POST['image_id']) && '' !== $_POST['image_id']) {
            $image = $_POST['image_id'];
            update_term_meta($term_id, 'image_id', $image);
        } else {
            update_term_meta($term_id, 'image_id', '');
        }
    }

    /**
     * Show custom term image on columns
     *
     * @since    1.0.0
     */
    public function display_image_column_heading($columns)
    {
        $columns['category_image'] = __('Image', 'woptical');
        return $columns;
    }

    
    /**
     * Show custom term image on columns
     *
     * @since    1.0.0
     */
    public function display_image_column_value($columns, $column, $id)
    {
        if ('category_image' == $column) {
            $image_id = esc_html(get_term_meta($id, 'image_id', true));
            $columns = wp_get_attachment_image($image_id, array('50', '50'));
        }
        return $columns;
    }
}
