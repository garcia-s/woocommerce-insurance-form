<?php


class WC_Insurance_Cyber extends IWC_Insurance_Entry
{

    private $blc1;
    private $blc2;
    private $blc3;
    private $employee_factor;
    private $cyber_factor;
    private $ilf;
    private $reputation_rol;
    private $reputation_class;
    private $reputation_taper_factor;
    private $rf_factor;
    private $risk_margin;


    private const TREND_FACTOR = 1.05;
    private const SE_FACTOR = 0.90;
    private const CRF = 1 / 3;

    private const DEDUCTIBLE = ["High" => 0.02, "Low" => 0.021];
    public function __construct()
    {
        require(WC_INSURANCE_DIR . "/data/cyber_data.php");

        $this->blc1 = $cyber_blc1;
        $this->blc2 = $cyber_blc2;
        $this->blc3 = $cyber_blc3;
        $this->employee_factor = $cyber_employee_factor;
        $this->cyber_factor = $cyber_factor;
        $this->ilf = $cyber_ilf;
        $this->reputation_rol = $cyber_reputation_rol;
        $this->reputation_class = $cyber_reputation_class;
        $this->reputation_taper_factor = $cyber_reputation_taper_factor;
        $this->rf_factor = $cyber_rf_factor;
        $this->risk_margin = $cyber_risk_margin;
    }

    public function get_slug(): string
    {
        return "cyber";
    }

    function get_name(): string
    {
        return "First Party Cyber and Data";
    }

    function renderForm()
    {
?>
        <label>Limit: </label>


        <div id="cyber_limit" class="error"></div>
        <select name="cyber_limit" \>
            <option> Select a limit</option>
            <? 
            foreach ($this->risk_margin as $limit => $risk) {
                echo '<option value=' . $limit . '>' . $limit . '</option>';
            } ?>
        </select>

        <label>Projected gross revenue: </label>

        <div id="cyber_projected_revenue" class="error"></div>
        <input type="number" name="cyber_projected_revenue" \ />
        <label>Total employees: </label>

        <div id="cyber_total_employees" class="error"></div>
        <input type="number" name="cyber_total_employees" \ />

        <label>Primary SIC Class: </label>

        <div id="cyber_sic_class" class="error"></div>
        <select name="cyber_sic_class" \>
            <option>Select an SIC Class</option>
            <?php
            foreach ($this->reputation_class as $id => $data) {
                echo '<option value=' . $id . '>' . $data['industry_title'] . '</option>';
            } ?>
        </select>

        <label>Class Description:</label>

        <div id="cyber_class_description" class="error"></div>
        <select name="cyber_class_description" \>
            <option>Select class description</option>
            <?php
            foreach ($this->cyber_factor as $id => $data) {
                echo '<option value=' . $id . '>' . $id . ':   ' . $data['class_description'] . '</option>';
            } ?>
        </select>

        <label>Effective Date: </label>

        <div id="cyber_effective_date" class="error"></div>
        <input type="date" name="cyber_effective_date" \ />
<?php
    }

    function validate($data)
    {
        $errors = [];

        if ($this->risk_margin[intval($data["cyber_limit"])] == null)
            $errors["cyber_limit"] = "Please select a valid limit";
        //
        if (intval($data["cyber_projected_revenue"]) <= 1)
            $errors["cyber_projected_revenue"] = "Projected gross revenue cannot be 1";

        if (intval($data["cyber_total_employees"]) < 2)
            $errors["cyber_total_employees"] = "The employees has to be a number or a string containing a number that is greater than zero";

        if ($this->reputation_class[intval($data["cyber_sic_class"])] == null)
            $errors["cyber_sic_class"] = "please select a valid SIC class from the list";

        if ($this->cyber_factor[intval($data["cyber_class_description"])] == null)
            $errors["cyber_class_description"] = "please select a valid class description from the list";

        if ($data["cyber_effective_date"] == null) {
            $errors["cyber_effective_date"] = "Please send a valid date";
        } else {
            $effective_year = intval(date("Y", strtotime((string)$data["cyber_effective_date"])));
            $current_date = intval(date("Y"));

            if ($effective_year == false)
                $errors["cyber_effective_date"] = "Please send a valid date";
            if ($effective_year < $current_date)
                $errors["cyber_effective_date"] = "Date cannot be before current date";
        }


        return $errors;
    }




    function calculatePremium($data)
    {
        // 2. Look up [Risk Margin]
        $limit = intval($data["cyber_limit"]);
        $risk_margin = $this->risk_margin[$limit];
        // 3. Look up [Trend Factor], [SE Factor], and [CRF]
        // HARD CODED AS CONSTS;
        // 4. Look up Reputation Class
        $reputation_class = $this->reputation_class[$data["cyber_sic_class"]];
        // 5. Look up [Reputation ROL] (based on Reputation Class and Projected Gross revenue in millions)
        $reputation_rol_class = $this->reputation_rol[$reputation_class["reputation_class"]];
        $reputation_rol = null;
        $projected_revenue = intval($data["cyber_projected_revenue"]);
        $rol_keys = array_keys($reputation_rol_class);

        for ($i = 0; $i < count($rol_keys) - 1; $i++) {
            if ($projected_revenue / 1_000_000 <= $rol_keys[$i] || $projected_revenue / 1_000_000 > $rol_keys[$i + 1])
                continue;

            $reputation_rol  = $reputation_rol_class[$rol_keys[$i]];
            break;
        }

        if ($reputation_rol == null)
            $reputation_rol = $reputation_class[500];

        // 6. Look up [Reputation Taper Factor] (based on Projected Gross Revenue and Limit)
        $reputation_taper_limit = $this->reputation_taper_factor[$limit];
        $reputation_taper = null;
        foreach ($reputation_taper_limit as $max => $value) {
            if (intval($max) > 1 || $projected_revenue < $max)
                $reputation_taper = $value;
        }

        if ($reputation_taper == null)
            $reputation_taper = $reputation_taper_limit["more"];

        // 7. Calculate [EL 1]:
        $el1 = $reputation_rol * $reputation_taper * self::CRF * $limit;
        // 8. Look up [Cyber Factor] and ILF Risk
        $cyber_factor = $this->cyber_factor[intval($data["cyber_class_description"])];


        // 9. Look up [Employee Factor] (based on Projected Gross Revenue divided by Total Employee Count.  Do not interpolate.  Select Revenue Per Employee Immediately below)
        $revenue_per_employee = $projected_revenue / intval($data["cyber_total_employees"]);
        $employee_factor = null;

        for ($i = 0; $i < count($this->employee_factor) - 1; $i++) {

            $lo =  $this->employee_factor[$i]["rev_per_employee"];
            $hi = $this->employee_factor[$i + 1]["rev_per_employee"];
            if ($revenue_per_employee < $lo || $revenue_per_employee >= $hi)
                continue;

            $employee_factor = $this->employee_factor[$i]["employee_factor"];
            break;
        }

        if ($employee_factor == null)
            $employee_factor = $this->employee_factor[count($this->employee_factor) - 1]["employee_factor"];

        // 10. Look up [RF 1], [RF 2], and [RF 3] (based on Projected Gross Revenue.  Do not interpolate.  Select Revenue Immediately below)
        $rf_factor = null;
        for ($i = 0; $i < count($this->rf_factor) - 1; $i++) {
            if (
                $projected_revenue <= $this->rf_factor[$i]["revenue"] ||
                $projected_revenue > $this->rf_factor[$i + 1]["revenue"]
            )
                continue;

            $rf_factor = $this->rf_factor[$i];
            break;
        }


        if ($rf_factor == null)
            $rf_factor = $this->rf_factor[count($this->rf_factor) - 1];
        // 10. Look up [Deductible Factor] (Based on ILF Risk)

        $deductible_factor = self::DEDUCTIBLE[$cyber_factor["ilf_risk"]];

        //11. Look up [ILF1], [ILF 2], and [ILF 3] (based on Limit and ILF Risk)
        $ilf = $this->ilf[$cyber_factor["ilf_risk"]][$limit];

        // 12. Calculate Rating Mod:
        $rating_mod = $employee_factor * $rf_factor["rf1"] * $rf_factor["rf2"] * $rf_factor["rf3"] * self::SE_FACTOR;

        $ildf1 = $ilf["ilf1"] + $deductible_factor; // [ILF 1] + [Deductible Factor]
        $ildf2 = $ilf["ilf2"] + $deductible_factor;
        $ildf3 = $ilf["ilf3"] + $deductible_factor;

        $blc11 = null;
        $blc12 = null;
        $blc13 = null;

        // 14. Look up [BLC 1.1], [BLC 1.2], and [BLC 1.3]
        for ($i = 0; $i < count($this->blc1) - 1; $i++) {
            if (
                $projected_revenue < $this->blc1[$i]["high"] ||
                $projected_revenue >=  $this->blc1[$i + 1]["high"]
            )
                continue;

            $blc11 = $projected_revenue - $this->blc1[$i - 1]["high"];
            $blc12 = $this->blc1[$i]["blc1_2"];
            $blc13 = $this->blc1[$i + 1]["blc1_3"];
        }

        if ($blc11 == null) {
            $last = count($this->blc1) - 1;
            $blc11 = $projected_revenue - $this->blc1[$last - 1]["high"];
            $blc12 = $this->blc1[$last]["blc1_2"];
            $blc13 = $this->blc1[$last]["blc1_3"];
        }



        // 15. Look up [BLC 2.1], [BLC 2.2], and [BLC 3.3]
        $blc21 = null;
        $blc22 = null;
        $blc23 = null;


        for ($i = 0; $i < count($this->blc2) - 1; $i++) {
            if (
                $projected_revenue < $this->blc2[$i]["high"] ||
                $projected_revenue >=  $this->blc2[$i + 1]["high"]
            )
                continue;

            $blc21 = $projected_revenue - $this->blc2[$i - 1]["high"];
            $blc22 = $this->blc2[$i]["blc2_2"];
            $blc23 = $this->blc2[$i + 1]["blc2_3"];
        }

        if ($blc21 == null) {
            $last = count($this->blc2) + 1;
            $blc21 = $projected_revenue - $this->blc2[$last - 1]["high"];
            $blc22 = $this->blc2[$last]["blc2_2"];
            $blc23 = $this->blc2[$last]["blc2_3"];
        }


        // 16. Look up [BLC 3.1], [BLC 3.2], and [BLC 3.3]
        $blc31 = null;
        $blc32 = null;
        $blc33 = null;

        for ($i = 0; $i < count($this->blc3) - 1; $i++) {
            if (
                $projected_revenue < $this->blc3[$i]["high"] ||
                $projected_revenue >=  $this->blc3[$i + 1]["high"]
            )
                continue;

            $blc31 = $projected_revenue - $this->blc3[$i - 1]["high"];
            $blc32 = $this->blc3[$i]["blc3_2"];
            $blc33 = $this->blc3[$i + 1]["blc3_3"];
        }

        if ($blc31 == null) {
            $last = count($this->blc3) + 1;
            $blc31 = $projected_revenue - $this->blc3[$last - 1]["high"];
            $blc32 = $this->blc3[$last]["blc3_2"];
            $blc33 = $this->blc3[$last]["blc3_3"];
        }
        // 17. Calculate [Base Loss Cost 1], [Base Loss Cost 2], and [Base Loss Cost 3]:
        $base_loss_cost1 = $blc12 + $blc13 * ($blc11 / 100_000);
        $base_loss_cost2 = $blc22 + $blc23 * ($blc21 / 100_000);
        $base_loss_cost3 = $blc32 + $blc33 * ($blc31 / 100_000);

        $effective_year = intval(date("Y", strtotime((string)$data["cyber_effective_date"])));
        // 18. Calculate [Loss Cost 1], [Loss Cost 2], and [Loss Cost 3]:
        $loss_cost1 = $base_loss_cost1 * (self::TREND_FACTOR ** ($effective_year - 2020));
        $loss_cost2 = $base_loss_cost2 * (self::TREND_FACTOR ** ($effective_year - 2020));
        $loss_cost3 = $base_loss_cost3 * (self::TREND_FACTOR ** ($effective_year - 2020));

        // 19. Calculate [EL 1], [EL 2], and [EL 3]:
        $el2 = $loss_cost1 * $cyber_factor["cyber_factor"] * $rating_mod * $ildf1;
        $el3 = $loss_cost2 * $cyber_factor["cyber_factor"] * $rating_mod * $ildf2;
        $el4 = $loss_cost3 * $cyber_factor["cyber_factor"] * $rating_mod * $ildf3;

        $expected_loss = $el1 + $el2 + $el3 + $el4;
        $expected_loss_with_risk = $expected_loss * $risk_margin;

        $this->premium = round($expected_loss_with_risk / (1 - WC_Insurance::get_variable_expense_percentage()));
    }
}
