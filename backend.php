<?php



add_action('woocommerce_product_options_general_product_data', 'custom_product_field');
add_action('woocommerce_process_product_meta', 'save_custom_product_field');

// Define the custom field
function custom_product_field()
{
    global $woocommerce, $post;

    echo '<div class="options_group">';

    woocommerce_wp_radio(array(
        'id' => INSURANCE_TYPE_FIELD,
        'label' => __('Insurance form type', 'woocommerce'),
        'options' => array(
            'none' => __("None", 'woocommerce'),
            LOSS_OF_KEY_PERSON => __('Loss of key person', 'woocommerce'),
            WORKPLACE_VIOLENCE => __('Workplace violence', 'woocommerce'),
            CYBER => __('Cyber Attacks', 'woocommerce'),
        ),
        'desc_tip' => 'true',
        'description' => __('Select an form option if needed', 'woocommerce')
    ));

    echo '</div>';
}


function save_custom_product_field($post_id)
{
    $custom_field = $_POST['_insurance_form_type'];
    if (!empty($custom_field)) {
        update_post_meta($post_id, INSURANCE_TYPE_FIELD, esc_attr($custom_field));
    }
}
