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


require_once(WC_INSURANCE_DIR . 'helpers.php');
require_once(WC_INSURANCE_DIR . 'backend.php');
require_once(WC_INSURANCE_DIR . 'form.php');
require_once(WC_INSURANCE_DIR . './loss_of_key_person/loss_of_key_person_module.php');
require_once(WC_INSURANCE_DIR . './workplace_violence/workplace_violence_module.php');
require_once(WC_INSURANCE_DIR . './cyber/cyber_module.php');

add_action('woocommerce_before_add_to_cart_button', 'add_custom_form');
add_filter('woocommerce_add_to_cart_validation', 'custom_form_add_to_cart_validation', 10, 3);
add_filter('woocommerce_add_cart_item', 'add_custom_option_to_cart_item_data', 10, 3);
add_filter('woocommerce_product_get_price', 'custom_price_based_on_field', 10, 2);
