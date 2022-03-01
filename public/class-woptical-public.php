<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://robertochoaweb.com/
 * @since      1.0.0
 *
 * @package    Woptical
 * @subpackage Woptical/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woptical
 * @subpackage Woptical/public
 * @author     Robert Ochoa <ochoa.robert1@gmail.com>
 */
class Woptical_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woptical_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woptical_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woptical-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woptical_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woptical_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woptical-public.js', array( 'jquery' ), $this->version, false);
    }

    public function custom_optical_selections()
    {
        ob_start();
        $activate_functions = get_post_meta(get_the_ID(), 'activate_optical_functions', true);
        $selected_functions = get_post_meta(get_the_ID(), 'selected_optical_terms', true);
        if ($activate_functions == 'yes') {
            ?>
<section class="custom-optical-options-container">
    <?php $i = 1; ?>
    <?php foreach ($selected_functions as $item) { ?>
    <?php $class = ($i == 1) ? 'show' : ''; ?>
    <div class="custom-optical-options-item-content custom-optical-options-item-<?php echo $i; ?> <?php echo $class; ?>">
        <header id="heading<?php echo $i; ?>" class="custom-option-title" data-accordion="<?php echo $i; ?>">
            <?php $attribute_label_name = wc_attribute_label($item); ?>
            <h3><?php echo $i; ?>.- <?php echo $attribute_label_name; ?> <span class="selected selected-hidden"><?php _e('Selected:', 'woptical'); ?></span></h3>
        </header>
        <div class="custom-options-content">
            <?php $terms = get_terms(array('taxonomy' => $item, 'hide_empty' => false)); ?>
            <?php if (!empty($terms)) : ?>
            <?php foreach ($terms as $term) { ?>
            <label for="<?php echo $term->slug; ?>" class="custom-option-item" data-label="<?php echo $term->name; ?>">
                <?php $image = get_term_meta($term->term_id, 'category_image_id', true); ?>
                <?php echo wp_get_attachment_image($image, 'thumbnail', array('class' => 'img-fluid')); ?>
                <h4><?php echo $term->name; ?></h4>
                <input type="radio" name="<?php echo $item; ?>" id="<?php echo $term->slug; ?>" value="<?php echo $term->term_id; ?>">
            </label>
            <?php } ?>
            <?php endif; ?>
        </div>
    </div>
    <?php $i++; } ?>

    <div class="custom-optical-options-item-content custom-optical-options-item-4">
        <header id="heading4" class="custom-option-title" data-accordion="4">
            <h3>4.- <?php _e('Formula', 'woptical'); ?></h3>
        </header>
        <div class="custom-options-content">
            <div class="custom-optical-tab-container">
                <ul class="custom-optical-tab-buttons">
                    <li>
                        <a id="far-tab" class="tab-link tab-active" href="#"><?php _e('Far', 'woptical'); ?></a>
                    </li>
                    <li>
                        <a id="close-tab" class="tab-link" href="#"><?php _e('Close', 'woptical'); ?></a>
                    </li>
                </ul>
                <?php $term_sphere = get_terms(array( 'taxonomy' => 'pa_esfera', 'hide_empty' => false )); ?>
                <?php foreach ($term_sphere as $term) {
                $arr_sphere[$term->term_id] = $term->name;
            } ?>
                <?php $term_cilinder = get_terms(array( 'taxonomy' => 'pa_cilindro', 'hide_empty' => false )); ?>
                <?php foreach ($term_cilinder as $term) {
                $arr_cilinder[$term->term_id] = $term->name;
            } ?>

                <div class="custom-optical-tab-content">
                    <div class="custom-optical-tab-item" data-button="far-tab">
                        <table class="custom-optical-table">
                            <tr>
                                <th></th>
                                <th><?php _e('Sph', 'woptical'); ?></th>
                                <th><?php _e('Cil', 'woptical'); ?></th>
                                <th><?php _e('Axis', 'woptical'); ?></th>
                                <th><?php _e('DP', 'woptical'); ?></th>
                            </tr>
                            <tr>
                                <td><?php _e('LE', 'woptical')?></td>
                                <td>
                                    <select name="le_far_sphere" id="le_far_sphere">
                                        <?php foreach ($arr_sphere as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="le_far_cilinder" id="le_far_cilinder">
                                        <?php foreach ($arr_cilinder as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="le_far_axis" id="le_far_axis" />
                                </td>
                                <td>
                                    <input type="number" name="le_far_dp" id="le_far_dp" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('RE', 'woptical')?></td>
                                <td>
                                    <select name="re_far_sphere" id="re_far_sphere">
                                        <?php foreach ($arr_sphere as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="re_far_cilinder" id="re_far_cilinder">
                                        <?php foreach ($arr_cilinder as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="re_far_axis" id="re_far_axis" />
                                </td>
                                <td>
                                    <input type="number" name="re_far_dp" id="re_far_dp" />
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="custom-optical-tab-item tab-hidden" data-button="close-tab">
                        <table class="custom-optical-table">
                            <tr>
                                <th></th>
                                <th><?php _e('Sph', 'woptical'); ?></th>
                                <th><?php _e('Cil', 'woptical'); ?></th>
                                <th><?php _e('Axis', 'woptical'); ?></th>
                                <th><?php _e('DP', 'woptical'); ?></th>
                            </tr>
                            <tr>
                                <td><?php _e('LE', 'woptical')?></td>
                                <td>
                                    <select name="le_close_sphere" id="le_close_sphere">
                                        <?php foreach ($arr_sphere as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="le_close_cilinder" id="le_close_cilinder">
                                        <?php foreach ($arr_cilinder as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="le_close_axis" id="le_close_axis" />
                                </td>
                                <td>
                                    <input type="number" name="le_close_dp" id="le_close_dp" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('RE', 'woptical')?></td>
                                <td>
                                    <select name="re_close_sphere" id="re_close_sphere">
                                        <?php foreach ($arr_sphere as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="re_close_cilinder" id="re_close_cilinder">
                                        <?php foreach ($arr_cilinder as $key => $value) { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="re_close_axis" id="re_close_axis" />
                                </td>
                                <td>
                                    <input type="number" name="re_close_dp" id="re_close_dp" />
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
        }
        $content = ob_get_clean();
        echo $content;
    }
}