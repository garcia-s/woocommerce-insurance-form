<?php


class WC_Insurance_Data_Store extends WC_Data_Store_WP

{

    public function read(WC_Product $product)
    {

        $instance = WC_Insurance::get();
        $entry = $instance->get_by_id($product->get_id());
        if ($entry === null)
            throw new Exception("Invalid entry in the storage of data");

        $product->set_name($entry->get_name() . ' Policy');
        $product->set_sold_individually(true);
        //TODO: Implement this with some real sku like the policy number 
        $product->set_sku($entry->get_slug());
        $product->set_price($entry->get_premium());
    }

    public function create(&$data)
    {
        throw new Exception("You cannot ---- in this data store");
    }

    public function update(&$data)
    {
        throw new Exception("You cannot ---- in this data store");
    }

    public function delete(&$data, $args = array())
    {
        throw new Exception("You cannot ---- in this data store");
    }

    public function add_meta(&$object, $meta)
    {
        throw new Exception("You cannot ---- in this data store");
    }

    public function read_meta(&$data)
    {
        return array();
    }

    public function update_meta(&$object, $meta)
    {
        throw new Exception("You cannot ---- in this data store");
    }

    public function delete_meta(&$data, $meta)
    {
        throw new Exception("You cannot ---- in this data store");
    }
}
