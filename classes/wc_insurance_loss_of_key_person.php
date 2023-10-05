<?php


class WC_Insurance_Loss_Of_Key_Person extends IWC_Insurance_Entry
{
    /**
     * A map containing the limits and risks
     * @var array<int, float> $limits_and_risk 
     */
    private float $premium = 0;
    private $limits_and_risk;
    function __construct()
    {
        require(WC_INSURANCE_DIR . '/loss_of_key_person/limits_and_risk.php');
        $this->limits_and_risk = $limits_and_risk;
    }


    function renderForm()
    {

?>

        <label>Limit: </label>
        <select name="limit">
            <option>Select a limit</option>
            <?php
            foreach ($this->limits_and_risk as $limit => $risk) {
                echo '<option value=' . $limit . '>' . $limit . '</option>';
            }
            ?>
        </select>
        <label>Covered Employees:</label>
        <input type="number" name="covered_employees" />
<?php
    }

    function validate(&$data)
    {
    }


    public function get_slug(): string
    {
        return "loss_of_key_person";
    }

    function get_name(): string
    {
        return "Loss of key person";
    }

    function calculatePremium(&$data)
    {
        global $limit_and_risk;
        // Get risk_margin
        $risk_margin = $this->limits_and_risk[$data["limit"]];
        // Calculate Expected losses
        $expected_losses = $data["covered_employees"] * 0.01 * $data["limit"];
        // Return expected loss with risk management
        $losses_with_risk = $expected_losses * $risk_margin;

        $this->premium = $losses_with_risk / (1 - WC_Insurance::get_variable_expense_percentage());
    }

    function get_premium(): float
    {
        return $this->premium;
    }
}
