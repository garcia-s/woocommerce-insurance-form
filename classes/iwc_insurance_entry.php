<?php

abstract class IWC_Insurance_Entry
{
    protected float $premium;

    abstract function get_name(): string;
    abstract function get_slug(): string;

    /**
     * Validates some data and returns an associative array.
     * @param array<string,any> $data 
     * @return array<string,string> An associative array where both keys and values are strings.
     */
    abstract function validate($data);


    /**
     * Renders the form needed for the extended class 
     */
    abstract function renderForm();

    /**
     * Calculates the premium and saves it to the $premium attribute;
     * @param array<string,any> $data
     */
    abstract function calculatePremium($data);


    /**
     * Returns the value of the $premium
     */
    function get_premium(): float
    {
        return $this->premium ?? 0;
    }
}
