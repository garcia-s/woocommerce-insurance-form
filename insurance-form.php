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
require_once(WC_INSURANCE_DIR . './loss_of_key_person/loss_of_key_person_module.php');
require_once(WC_INSURANCE_DIR . './workplace_violence/workplace_violence_module.php');
require_once(WC_INSURANCE_DIR . './cyber/cyber_module.php');
require_once(WC_INSURANCE_DIR . './form.php');

function insurance_form_function()
{
    wp_enqueue_script('insurance_script', INSURANCE_URL . 'assets/js/insurance-form.js', array('jquery'), '1.0', true);
    wp_enqueue_style('insurance_styles', INSURANCE_URL . 'assets/css/insurance-form.css', array(), '1.0', 'all');
    return renderForm();
}

function register_insurance_endpoint()
{
    register_rest_route('insurance/v1', '/get_premium', array(
        'methods' => 'POST',
        'callback' => 'get_premium_callback',
        'permission_callback' => '__return true'
    ));

    register_rest_route('insurance/v1', '/submit', array(
        'methods' => 'POST',
        'callback' => 'process_insurance_request_callback',
        'permission_callback' => '__return true'
    ));

    register_rest_route('insurance/v1', '/generate_pdf', array(
        'methods' => 'POST',
        'callback' => 'generate_pdf_callback',
        'permission_callback' => '__return true'
    ));
}

function generate_pdf_callback()
{
}

function get_premium_callback()
{
}


function process_insurance_request_callback()
{
}

add_shortcode('insurance_form_shortcode', 'insurance_form_function');
add_action('rest_api_init', 'register_insurance_endpoint');
