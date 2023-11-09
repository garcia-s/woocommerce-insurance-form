<?php

abstract class IWC_Insurance_Entry
{
    protected float $premium;
    protected $data;
    abstract function get_name(): string;
    abstract function get_slug(): string;


    function set_data($data)
    {
        $this->data = $data;
    }

    /**
     * Validates some data and returns an associative array.
     * @param array<string,any> $data 
     * @return array<string,string> An associative array where both keys and values are strings.
     */
    abstract function validate();
    /**
     * Renders the form needed for the extended class 
     */
    abstract function renderForm();

    /**
     * Calculates the premium and saves it to the $premium attribute;
     * @param array<string,any> $data
     */
    abstract function calculatePremium();

    abstract function renderHTMLTable();
    /**
     * Returns the value of the $premium
     */

    function init($data)
    {
        $this->data = $data;
    }
    function get_premium(): float
    {
        return $this->premium ?? 0;
    }
}
