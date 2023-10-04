<?php


add_action('rest_api_init', 'register_insurance_endpoint');

function register_insurance_endpoint()
{

    register_rest_route('insurance/v1', '/submit', array(
        'methods' => 'POST',
        'callback' => 'process_insurance_request_callback',
        'permission_callback' => "__return_true"
    ));

    register_rest_route('insurance/v1', '/get_premium', array(
        'methods' => 'POST',
        'callback' => 'get_premium_callback',
        'permission_callback' => "__return_true"

    ));

    register_rest_route('insurance/v1', '/generate_pdf', array(
        'methods' => 'POST',
        'callback' => 'generate_pdf_callback',
        'permission_callback' => "__return_true"
    ));
}

add_filter('woocommerce_data_stores', 'set_insurance_data_store');

function set_insurance_data_store($stores)
{
    $stores['product-insurance'] = 'WC_Insurance_Data_Store';
    return $stores;
}

add_filter("woocommerce_product_class", "add_woocommerce_class_for_product", 10, 2);


function add_woocommerce_class_for_product($_, $type)
{
    if ($type == "insurance") return "WC_Product_Insurance";
}


add_filter("woocommerce_product_type_query", "change_product_type", 10, 2);

function change_product_type($type, $productId)
{
    if (isset(WC_Insurance_Data_Store::$fakeProducts[$productId]))
        return "insurance";
}


function process_insurance_request_callback($request)
{
    $errors = [];
    $sanitizedData = [];
    foreach ($request->get_json_params() as $key => $value) {
        $sanitizedData[$key] = sanitize_text_field($value);
    }

    if (!isset($sanitizedData["contact_name"]) || $sanitizedData["contact_name"] == "")
        $errors["contact_name"] = "The contact name cannot be empty";

    if (!isset($sanitizedData["contact_phone"]) || $sanitizedData["contact_phone"] == "")
        $errors["contact_phone"] = "The phone number cannot be empty";

    if (!isset($sanitizedData["contact_email"]) || $sanitizedData["contact_email"] == "")
        $errors["contact_email"] = "The email cannot be empty";

    if (!isset($sanitizedData["fax"]) || $sanitizedData["fax"] == "")
        $errors["fax"] = "The email cannot be empty";

    if (!empty($errors))
        return new WP_REST_Response($errors, 400);

    $cart = &WC()->cart;

    if (is_null($cart)) {
        wc_load_cart();
    }
    WC()->cart->add_to_cart(
        1000000,
        1,
        null,
        null,
        array("price" => 444.3, "data" => $sanitizedData)
    );

    return new WP_REST_Response(WC()->cart, 200);
}


function generate_pdf_callback()
{
}

function get_premium_callback()
{
}
