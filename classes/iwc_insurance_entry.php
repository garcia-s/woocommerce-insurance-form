<?php

abstract class IWC_Insurance_Entry
{
    abstract function get_name(): string;
    abstract function get_slug(): string;

    /**
     * Validates some data and returns an associative array.
     * @param array<string,any> $data 
     * @return array<string,string> An associative array where both keys and values are strings.
     */
    abstract function validate(&$data);
    abstract function renderForm();
    abstract function calculatePremium(&$data);


    abstract function get_premium(): float;
}
