<?php
require('constants.php');
require(__DIR__ . '/../autoload.php');

use PHPUnit\Framework\TestCase;


final class WorkplaceViolenceRequestTest extends TestCase
{

    private $validData = [
        "wv_limit" => "25000",
        "wv_effective_date" => "2023-07-22",
        "wv_sic_class" => "1",
        "wv_total_employees" => "30"
    ];

    function testEffectiveDateForNull()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_effective_date" => null]));
        $errors = $instance->validate();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_effective_date"]);
    }


    function testEffectiveDateForPreviousDate()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_effective_date" => "2020-07-22"]));
        $errors = $instance->validate();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_effective_date"]);
    }

    function testSICClassNull()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_sic_class" => null]));
        $errors = $instance->validate();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_sic_class"]);
    }

    function testSICClassNotExistent()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_sic_class" => "333"]));
        $errors = $instance->validate();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_sic_class"]);
    }

    function testSICClassInteger()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_sic_class" => 333]));
        $errors = $instance->validate();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors["wv_sic_class"]);
    }

    function testTotalEmployeesNegative()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_total_employees" => -1]));
        $errors = $instance->validate();
        $this->assertnotempty($errors);
        $this->assertnotnull($errors["wv_total_employees"]);
    }

    function testTotalEmployeesNull()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init(array_merge($this->validData, ["wv_total_employees" => null]));
        $errors = $instance->validate();
        $this->assertnotempty($errors);
        $this->assertnotnull($errors["wv_total_employees"]);
    }

    function testWholeValidation()
    {
        $instance = new WC_Insurance_Workplace_Violence($this->validData);
        $instance->init($this->validData);
        $errors = $instance->validate();
        $this->assertNotNull($errors);
        $this->assertEmpty($errors);
    }

    function testCalculations()
    {
        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init($this->validData);
        $instance->calculatePremium();
        $this->assertTrue($instance->get_premium() == 26);
    }

    function testNicksExample()
    {

        $nicksData = [
            "wv_limit" => "100000",
            "wv_effective_date" => "2023-07-22",
            "wv_sic_class" => "73",
            "wv_total_employees" => "30"
        ];

        $instance = new WC_Insurance_Workplace_Violence();
        $instance->init($nicksData);
        $instance->calculatePremium();
        var_dump($instance->get_premium());
        $this->assertTrue($instance->get_premium() == 122);
    }
}
