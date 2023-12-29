<?php
class WC_Insurance_Workplace_Violence extends IWC_Insurance_Entry
{

    private $wv_class;
    private $wv_hazard_factor;
    private $wv_risk_margin;
    private $wv_limit_factor;
    private $wv_base_premium;


    private const TREND_FACTOR = 1.05;
    private const EXPECTED_LOSS_RATIO = 0.65;
    private const DEDUCTIBLE_FACTOR = 1.225;
    public function __construct()
    {
        require(WC_INSURANCE_DIR . 'data/wv_data.php');
        $this->wv_class = &$wv_class;
        $this->wv_hazard_factor = &$wv_hazard_factor;
        $this->wv_risk_margin = &$wv_risk_margin;
        $this->wv_limit_factor = &$wv_limit_factor;
        $this->wv_base_premium = &$wv_base_premium;
    }

    public function get_slug(): string
    {
        return "workplace_violence";
    }

    function get_name(): string
    {
        return "Workplace violence";
    }

    function renderForm()
    {
?>
        <label>Limit: </label>

        <div id="wv_limit" class="error"></div>
        <select name="wv_limit" \>
            <option> Select a limit</option>
            <? 
            foreach ($this->wv_risk_margin as $limit => $risk) {
                echo '<option value=' . $limit . '>' . $limit . '</option>';
            } ?>
        </select>
        <label>SIC Class: </label>

        <div id="wv_sic_class" class="error"></div>
        <select name="wv_sic_class" \>
            <option>Select an SIC Class</option>
            <?php
            foreach ($this->wv_class as $id => $this->data) {
                echo '<option value=' . $id . '>' . $this->data['industry_title'] . '</option>';
            } ?>
        </select>
        <label>Effective Date: </label>

        <div id="wv_effective_date" class="error"></div>
        <input type="date" name="wv_effective_date" \ />
        <label>Total employees: </label>

        <div id="wv_total_employees" class="error"></div>
        <input type="number" name="wv_total_employees" \ />
    <?php
    }

    function validate()
    {
        $errors = [];

        if ($this->wv_limit_factor[intval($this->data["wv_limit"])] == null)
            $errors["wv_limit"] = "Please select a valid limit";

        if ($this->wv_class[intval($this->data["wv_sic_class"])] == null)
            $errors["wv_sic_class"] = "Please select a valid SIC class from the list";

        if ($this->data["wv_effective_date"] == null) {
            $errors["wv_effective_date"] = "Please send a valid date";
        } else {
            $effective_year = date("Y", strtotime((string)$this->data["wv_effective_date"]));
            $current_date = intval(date("Y"));

            if ($effective_year == false)
                $errors["wv_effective_date"] = "Please send a valid date";
            if ($effective_year < $current_date)
                $errors["wv_effective_date"] = "Date cannot be before current date";
        }
        $total_employees = intval($this->data["wv_total_employees"]);

        if ($total_employees < 1)
            $errors["wv_total_employees"] = "The employees has to be a number or a string containing a number that is greater than zero";
        // TEST if date is valid and is after current date
        // Check if total_employees is integer and is greater than 0 

        return $errors;
    }




    function calculatePremium()
    {
        // 1. Look up [Risk Margin]
        $risk_margin = $this->wv_risk_margin[intval($this->data["wv_limit"])];
        // 2. Look up [Trend Factor], [ELR], and [Deductible Factor]
        // 3. Look up WV Class
        $wv_class = $this->wv_class[intval($this->data["wv_sic_class"])]["wv_class"];
        // 4. Look up [Hazard Factor] (based on WV Class)
        $hazard_factor = $this->wv_hazard_factor[$wv_class];
        // 5. Look up [Limit Factor]
        $limit_factor = $this->wv_limit_factor[intval($this->data["wv_limit"])];
        // 6. Look up [Base Premium].  (Based on Total Employee Count.  Interpolate between Base Premiums as needed)
        // For example, if 51 Total Employees, then Base Premium = 5,399 x (70-51) / (70-50) + 6,919 x (51-50) / (70-50)
        $total_employees = intval($this->data["wv_total_employees"]);

        $base_premium = null;
        for ($i = 0; $i < count($this->wv_base_premium) - 1; $i++) {
            //$i +1 high
            if (
                $total_employees === $this->wv_base_premium[$i]["max"] ||
                ($i === 0 && $total_employees <= $this->wv_base_premium[$i]["max"])
            ) {
                $base_premium = $this->wv_base_premium[$i]["premium"];
                break;
            }
            $hi = $this->wv_base_premium[$i + 1];
            $lo = $this->wv_base_premium[$i];

            if ($total_employees < $lo["max"] || $total_employees > $hi["max"])
                continue;

            $premium_max_diff = $hi["max"] - $lo["max"];
            $base_premium = $lo["premium"] * ($hi["max"] - $total_employees) / $premium_max_diff + $hi["premium"] *
                ($total_employees - $lo["max"]) / $premium_max_diff;
            break;
        }
        if ($base_premium == null) {
            $last = $this->wv_base_premium[count($this->wv_base_premium) - 1];
            $base_premium = $last["premium"] + ($total_employees - $last['max']) * 8;
        }

        // 8. Calculate [TBP]:
        // [Base Premium] x {[Trend Factor] ^ [(Year of Policy Effective Date) - (2021)]}
        $effective_year = intval(date("Y", strtotime((string)$this->data["wv_effective_date"])));
        $tbp = $base_premium * ($this::TREND_FACTOR ** ($effective_year - 2020));
        // 9. Calculate [Expected Losses]
        // [TBP] x [Limit Factor] x [Deductible Factor] x [ELR] x [Hazard Factor]
        $expected_loss = $tbp * $limit_factor * $this::DEDUCTIBLE_FACTOR * $this::EXPECTED_LOSS_RATIO * $hazard_factor;
        // [Expected Losses] x [Risk Margin]

        // 10. Calculate [Expected Losses w/Risk Margin]
        $expected_loss_with_risk = $expected_loss * $risk_margin;
        $this->premium = round($expected_loss_with_risk / (1 - WC_Insurance::get_variable_expense_percentage()));
    }

    function renderHTMLTable()
    {
    ?>

        <table style="margin:10px">
            <tbody>
                <tr>
                    <td align="LEFT">
                        <strong>TYPE OF INSURANCE:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->get_name() ?>
                    </td>
                </tr>

                <tr>
                    <td align="LEFT">
                        <strong>LIMIT:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->data["wv_limit"] . ' $' ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>SIC CLASS:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->wv_class[intval($this->data["wv_sic_class"])]["industry_title"] ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>EFFECTIVE DATE:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->data["wv_effective_date"] ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>TOTAL EMPLOYEES:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->data["wv_total_employees"] . ' Employees' ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>PREMIUM:</strong>
                    </td>
                    <td align="right">
                        <?php echo  $this->premium . ' $' ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
    }
}
