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

require_once(WC_INSURANCE_DIR . 'helpers.php');
require_once(WC_INSURANCE_DIR . 'admin-menu.php');
require_once(WC_INSURANCE_DIR . 'custom-product-form.php');
/**
require_once(WC_INSURANCE_DIR . 'checkout-handler.php');
require_once(WC_INSURANCE_DIR . 'custom-form-shortcode.php');
 **/

 

