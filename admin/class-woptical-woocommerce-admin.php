<?php
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
class Woptical_Woocommerce_Admin
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

    public function woptical_custom_product_tab($default_tabs)
    {
        $default_tabs['optical_data'] = array(
            'label'   =>  __('Optical Data', 'woptical'),
            'target'  =>  'woptical_custom_tab_data',
            'priority' => 35,
            'class'   => array()
        );
        return $default_tabs;
    }

    public function woocommerce_wp_select_multiple( $field ) {
        global $thepostid, $post, $woocommerce;
    
        $thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
        $field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
        $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
        $field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
        $field['value']         = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? get_post_meta( $thepostid, $field['id'], true ) : array() );
    
        echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" multiple="multiple">';
    
        foreach ( $field['options'] as $key => $value ) {
    
            echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';
    
        }
    
        echo '</select> ';
    
        if ( ! empty( $field['description'] ) ) {
    
            if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
                echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
            } else {
                echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
            }
    
        }
        echo '</p>';
    }

    public function woptical_custom_tab_data()
    {
        global $wpdb;
        $arr_options = array();
        $results = $wpdb->get_results("SELECT attribute_name, attribute_label FROM {$wpdb->prefix}woocommerce_attribute_taxonomies", 'OBJECT');
        foreach ($results as $item) {
            $arr_options['pa_' . $item->attribute_name] = $item->attribute_label;
        } ?>
<div id="woptical_custom_tab_data" class="panel woocommerce_options_panel hidden">
    <div class="options_group">
        <?php
        woocommerce_wp_checkbox(
            array(
                'id' => 'activate_optical_functions',
                'label' => __('Activate these functions', 'woptical'),
                'description' => __('Check this if you want this product to be activated with these options.', 'woptical')
            )
        );

        $this->woocommerce_wp_select_multiple(
            array(
                'id' => 'selected_optical_terms',
                'name' => 'selected_optical_terms[]',
                'label' => __('Select Terms', 'woptical'),
                'description' => __('Select the woocommerce terms you want to integrate with this product.', 'woptical'),
                'desc_tip' => 'true',
                'options' => $arr_options,
            )
        );

        ?>
    </div>
</div>
<?php
    }

    public function woptical_save_proddata_custom_fields($post_id)
    {
        $checkbox = isset($_POST['activate_optical_functions']) ? 'yes' : 'no';
        update_post_meta($post_id, 'activate_optical_functions', $checkbox);

        $select_field = $_POST['selected_optical_terms'];
        if (!empty($select_field)) {
            update_post_meta($post_id, 'selected_optical_terms', $select_field);
        }
    }
}
