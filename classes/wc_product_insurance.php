<?php

class WC_Product_Insurance extends WC_Product_Simple
{


    public function get_type()
    {
        return "insurance";
    }


    public function is_purchasable()
    {
        return true;
    }

    public function add_to_cart_url()
    {
        return "";
    }

    public function add_to_cart_text()
    {
        return "";
    }

    public function add_to_cart_description()
    {
    }

    public function is_visible()
    {
        return true;
    }


    public function exists()
    {
        return true;
    }
}
