<?php 

class WC_Product_Insurance extends WC_Product_Simple {


    public function get_type()
    {
        return "insurance";
    }


    public function is_purchasable()
    {
        return true;
    }
}
