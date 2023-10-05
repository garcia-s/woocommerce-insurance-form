<?php
require(WC_INSURANCE_DIR . '/workplace_violence/wv_data.php');
// int $limit;
// int $effective_year;
// int $sic_code;
// int $employee_count;
function load_workplace_violence_form()
{

    global $wv_risk_margin;
    global $wv_class;
?>
    <div class="insurance_options">
        <label>Limit: </label>
        <select name="limit">
            <option value=""> Select a limit</option>
            <?php
            foreach ($wv_risk_margin as $limit => $risk) {
                echo '<option value=' . $limit . '>' . $limit . '</option>';
            } ?>
        </select>
        <label>SIC CLASS: </label>
        <select name="sic_class">
            <option value=""> Select a limit</option>
            <?php
            foreach ($wv_class as $id => $data) {
                echo '<option value=' . $id . '>' . $data['industry_title'] . '</option>';
            } ?>
        </select>
        <label>Effective Date: </label>
        <input type="date" name="effective_year" />
        <label>Total employees: </label>
        <input type=" number" name="employee_count" />
    </div>
<?php
}
