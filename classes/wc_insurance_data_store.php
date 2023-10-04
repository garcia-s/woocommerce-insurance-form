<?php


class WC_Insurance_Data_Store extends WC_Data_Store_WP
// implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface
{

    public static $fakeProducts = [
        1000000 => [],
        1000001 => [],
        1000002 => [],
    ];


    public function read(&$data)
    {
    }
    public function create(&$data)
    {
    }
    public function update(&$data)
    {
    }
    public function delete(&$data, $args = array())
    {
    }

    public function add_meta(&$object, $meta)
    {
    }

    public function read_meta(&$data)
    {
    }
    public function update_meta(&$object, $meta)
    {
    }
    public function delete_meta(&$data, $meta)
    {
    }
}
