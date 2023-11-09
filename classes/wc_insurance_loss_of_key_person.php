<?php




class WC_Insurance_Loss_Of_Key_Person extends IWC_Insurance_Entry
{
    /**
     * A map containing the limits and risks
     * @var array<int, float> $limits_and_risk 
     */
    private $limits_and_risk;
    function __construct()
    {
        require(WC_INSURANCE_DIR . '/data/limits_and_risk.php');
        $this->limits_and_risk = $limits_and_risk;
    }

    public function get_slug(): string
    {
        return "loss_of_key_person";
    }

    function get_name(): string
    {
        return "Loss of key person";
    }

    function renderForm()
    {

?>

        <label>Limit: </label>

        <div id="limit" class="error"></div>
        <select name="limit" \>
            <option>Select a limit</option>
            <?php
            foreach ($this->limits_and_risk as $limit => $risk) {
                echo '<option value=' . $limit . '>' . $limit . '</option>';
            }
            ?>
        </select>
        <label>Covered Employees:</label>

        <div id="covered_employees" class="error"></div>
        <input type="number" name="covered_employees" />
    <?php
    }
    /**@param array<string,any> $this->data
     * @returns array<string,string>
     **/
    function validate()
    {
        $errors = [];
        if ($this->data["limit"] == null || $this->limits_and_risk[intval($this->data["limit"])] == null)
            $errors["limit"] = "Invalid limit provided";

        if ($this->data["covered_employees"] == null  || intval($this->data["covered_employees"]) < 1)
            $errors["covered_employees"] = "Invalid employee count";

        return $errors;
    }

    function calculatePremium()
    {
        // Get risk_margin
        $risk_margin = $this->limits_and_risk[intval($this->data["limit"])];
        // Calculate Expected losses
        $expected_losses = intval($this->data["covered_employees"]) * 0.01 * intval($this->data["limit"]);
        // Return expected loss with risk management
        $losses_with_risk = $expected_losses * $risk_margin;
        $this->premium = round($losses_with_risk / (1 - WC_Insurance::get_variable_expense_percentage()));
    }

    function renderHTMLTable()
    {
    ?>

        <table style="margin:10px">
            <tbody>
                <tr>

                    <td align="LEFT">
                        <strong>TYPE OF INSURACE</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->get_name() ?>
                    </td>
                </tr>
                <tr>

                    <td align="LEFT">
                        <strong>LIMIT</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->data["limit"] . ' $' ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>COVERED EMPLOYEES:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->data["covered_employees"] . " Employees" ?>
                    </td>
                </tr>
                <tr>
                    <td align="LEFT">
                        <strong>PREMIUM:</strong>
                    </td>
                    <td align="right">
                        <?php echo $this->premium . " $" ?>
                    </td>
                </tr>
            </tbody>
        </table>
<?php
    }
}
