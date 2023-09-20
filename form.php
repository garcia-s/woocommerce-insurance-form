<?php


function add_custom_form($product_id)
{
    //TODO: If the product accessed is 
    global $product;
    $insurance_type = get_post_meta($product->get_id(), INSURANCE_TYPE_FIELD, true);
    if ($insurance_type === WORKPLACE_VIOLENCE) return load_workplace_violence_form();
    if ($insurance_type === LOSS_OF_KEY_PERSON) return load_loss_of_key_person_form();
    if ($insurance_type === CYBER) return load_cyber_form();
}


/**
 *@function custom_form_add_to_cart_validation
 * Validates if your product has an insurance type, and responds with an error if your form information is not valid
 */
function custom_form_add_to_cart_validation($passed, $product_id, $quantity)
{
    $insurance_type = get_post_meta($product_id, INSURANCE_TYPE_FIELD, true);
    if ($insurance_type === 'none' || $insurance_type === null)
        return $passed;

    if ($insurance_type === LOSS_OF_KEY_PERSON) return validate_loss_of_key_form();
    wc_add_notice(__("Complete the form in the product page to add to the cart"), "error");
    return false;
}



function add_custom_option_to_cart_item_data($cart_item_data, $product_id)
{
    $insurance_type = get_post_meta($product_id, INSURANCE_TYPE_FIELD, true);
    if ($insurance_type === 'none' || $insurance_type === null)
        return $cart_item_data;

    if ($insurance_type === LOSS_OF_KEY_PERSON) return save_loss_of_key_data($cart_item_data);
}
/**
 * @function custom_price_based_on_field
 * Might not be necesary
 */
function custom_price_based_on_field($price)
{    //TODO: Modify the price based on calculation 
    return $price;
}



add_action('woocommerce_before_add_to_cart_button', 'add_custom_form');
add_filter('woocommerce_add_to_cart_validation', 'custom_form_add_to_cart_validation', 10, 3);
add_filter('woocommerce_add_cart_item', 'add_custom_option_to_cart_item_data', 10, 3);
add_filter('woocommerce_product_get_price', 'custom_price_based_on_field', 10, 2);
