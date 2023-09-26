<?php

/**
 * Plugin Name: Insurance Form Aggregator
 * Plugin URI: github.com/garcia-s/learnpress_registration_approval
 * Description: Adds Custom Forms to woocommerce
 * Version: 1.0.0
 * Author: Juan Garcia
 * Author URI: github.com/garcia-s
 * License: [License]
 **/


define('WC_INSURANCE_DIR', plugin_dir_path(__FILE__));
define('INSURANCE_URL', plugin_dir_url(__FILE__));

define("INSURANCE_TYPE_FIELD", "_insurance_form_type");
define("LOSS_OF_KEY_PERSON", 'loss_of_key_person');
define("WORKPLACE_VIOLENCE", 'workplace_violence');
define("CYBER", 'cyber');

require_once(WC_INSURANCE_DIR . 'api.php');
require_once(WC_INSURANCE_DIR . 'helpers.php');
require_once(WC_INSURANCE_DIR . 'backend.php');
require_once(WC_INSURANCE_DIR . './loss_of_key_person/loss_of_key_person_module.php');
require_once(WC_INSURANCE_DIR . './workplace_violence/workplace_violence_module.php');
require_once(WC_INSURANCE_DIR . './cyber/cyber_module.php');
require_once(WC_INSURANCE_DIR . './form.php');



register_activation_hook(__FILE__, 'create_invisible_products_on_activation');
register_deactivation_hook(__FILE__, 'remove_products_on_deactivation');

function my_custom_purchaseable_check($is_purchasable, $product) {
    // Modify the $is_purchasable variable as needed
    return true;
}
add_filter('woocommerce_is_purchasable', 'my_custom_purchaseable_check', 10, 2);

function create_invisible_products_on_activation()
{
    // Check if the products already exist
    $existing_products = get_posts(array(
        'post_type' => 'product',
        'post_status' => 'private',
        'post_title' => 'Insurance Coverage',
        'posts_per_page' => -1,
    ));

    // If no products exist, create them as drafts
    if (empty($existing_products)) {
        $product_data = array(
            'post_title' => 'Insurance Coverage',
            'post_content' => 'content',
            'post_status' => 'private',
            'post_type' => 'product',
            'post_author' => 2,
        );
        $product_id = wp_insert_post($product_data);

        // Add product meta data like price, SKU, etc.
        // update_post_meta($product_id, '_price', 20.99);
        update_post_meta($product_id, '_sku', 'insurance-product-sku');
        // Add more product meta data as needed
    }
}

function remove_products_on_deactivation()
{
    $existing_products = get_posts(array(
        'post_title' => 'Insurance Coverage',
        'post_type' => 'product',
        'post_status' => 'private',
        'posts_per_page' => 0,
    ));

    foreach ($existing_products as $product) {
        wp_delete_post($product->ID, true);
    }
}
