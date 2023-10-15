<?php

use PHPUnit\Framework\TestCase;


final class CyberRequestTest extends TestCase
{
   function testcalculateTotal()
    {

        $data = [
            "cyber_limit" => "100000",
            "cyber_projected_revenue" => "5000000",
            "cyber_total_employees" => "10",
            "cyber_sic_class" => "73",
            "cyber_class_description" => "5411",
            "cyber_effective_date" => "2023-10-15"
      ];
        $entry = new WC_Insurance_Cyber();
        $entry->calculatePremium($data);
        $this->assertTrue(false);
    }       
}

//reputation rol  0.035
//taper 1,78
//
//EL1=2076,66666
//
//employee_factor 0.631 o 0.709
//
//rf1 0.973 
//rf2 0.936 
//rf3 2500
//
//rating_mod 2250,57466
//
