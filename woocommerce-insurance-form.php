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
require_once(WC_INSURANCE_DIR . 'autoload.php');


$instance = WC_Insurance::get();


$instance->register_entry(new WC_Insurance_Loss_Of_Key_Person());
$instance->register_entry(new WC_Insurance_Workplace_Violence());

$instance2 = WC_Insurance::get();



