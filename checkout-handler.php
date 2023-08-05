<?php

add_action('template_redirect', 'custom_checkout_redirect');

function custom_checkout_redirect()
{
    $page_id = get_option('coi_form_page');
    if (!isset($page_id) || !is_checkout()) {
        return;
    }
    $page_url = get_permalink(intval($page_id));
    $cart = WC()->cart;
    $cart_items = $cart->get_cart();
    wp_safe_redirect($page_url);
}
