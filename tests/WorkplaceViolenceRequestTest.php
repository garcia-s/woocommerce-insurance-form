<?php
require('constants.php');
require(__DIR__ . '/../autoload.php');

use PHPUnit\Framework\TestCase;


final class WorkplaceViolenceRequestTest extends TestCase
{

    private $validData = [
        "wv_limit" => "100000",
        "wv_effective_date" => "2023-07-22",
        "wv_sic_class" => "73",
        "wv_total_employees" => "10"
    ];

    function testLimitForNull()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_limit" => null]));
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_limit"]);
    }

    function testLimitForNumeric()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_limit" => 50000]));
        $this->assertEmpty($errors);
        $this->assertNull($errors["wv_limit"]);
    }

    function testEffectiveDateForNull()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_effective_date" => null]));
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_effective_date"]);
    }


    function testEffectiveDateForPreviousDate()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_effective_date" => "2020-07-22"]));
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_effective_date"]);
    }

    function testSICClassNull()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_sic_class" => null]));
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_sic_class"]);
    }

    function testSICClassNotExistent()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_sic_class" => "333"]));
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_sic_class"]);
    }

    function testSICClassInteger()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_sic_class" => 333]));
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_sic_class"]);
    }

    function testTotalEmployeesNegative()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_total_employees" => -1]));
        $this->assertnotempty($errors);
        $this->assertnotnull($errors["wv_total_employees"]);
    }

    function testTotalEmployeesNull()
    {
        $instance = new wc_insurance_workplace_violence();
        $errors = $instance->validate(array_merge($this->validData, ["wv_total_employees" => null]));
        $this->assertnotempty($errors);
        $this->assertnotnull($errors["wv_total_employees"]);
    }

    function testWholeValidation()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $errors = $instance->validate($this->validData);
        $this->assertNotNull($errors);
        $this->assertEmpty($errors);
    }


    function testCalculations()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $errors = $instance->calculatePremium($this->validData);
        $this->assertTrue($instance->get_premium() == 116);
    }
}
