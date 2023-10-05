<?php
class WC_Insurance_Workplace_Violence extends IWC_Insurance_Entry
{
    private float $premium = 0;
    function renderForm()
    {
        require(WC_INSURANCE_DIR . '/workplace_violence/wv_data.php');
?>
        <label>Limit: </label>
        <select name="limit">
            <option> Select a limit</option>
            <?php
            foreach ($wv_risk_margin as $limit => $risk) {
                echo '<option value=' . $limit . '>' . $limit . '</option>';
            } ?>
        </select>
        <label>SIC CLASS: </label>
        <select name="sic_class">
            <option>Select an SIC Class</option>
            <?php
            foreach ($wv_class as $id => $data) {
                echo '<option value=' . $id . '>' . $data['industry_title'] . '</option>';
            } ?>
        </select>
        <label>Effective Date: </label>
        <input type="date" name="effective_year" />
        <label>Total employees: </label>
        <input type=" number" name="employee_count" />
<?php
    }

    function validate(&$data)
    {
    }


    public function get_slug(): string
    {
        return "workplace_violence";
    }

    function get_name(): string
    {
        return "Workplace violence";
    }


    function calculatePremium(&$data)
    {
        // global $limit_and_risk;
        // // Get risk_margin
        // $risk_margin = $limit_and_risk[$limit];
        // // Calculate Expected losses
        // $expected_losses = $covered_employees * 0.01 * $limit;
        // // Return expected loss with risk management
        // return $expected_losses * $risk_margin;
    }

    function get_premium(): float
    {
        return $this->premium;
    }
}
