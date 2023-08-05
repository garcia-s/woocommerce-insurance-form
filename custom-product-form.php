<?php


function custom_product_form()
{
    global $product;
    $product_id = $product->get_id();
    $selected_pdf = wp_get_attachment_url(get_post_meta($product_id, '_editable_pdf', true));

    echo '<embed src="' .$selected_pdf . '" type="application/pdf" width="100%" height="500px">';
}

add_action('woocommerce_before_add_to_cart_button', 'custom_product_form');
