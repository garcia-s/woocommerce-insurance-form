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

    echo '<div id="insurance-form">';
    echo '<label>Limit: </label>';
    echo '<select name="limit">';
    echo '<option value=""> Select a limit</option>';
    foreach ($wv_risk_margin as $limit => $risk) {
        echo '<option value=' . $limit . '>' . $limit . '</option>';
    }
    echo "</select>";
    echo '<label>SIC CLASS: </label>';
    echo '<select name="sic_class">';
    echo '<option value=""> Select a limit</option>';
    foreach ($wv_class as $id => $data) {
        echo '<option value=' . $id . '>' . $data['industry_title'] . '</option>';
    }
    echo "</select>";
    echo '<label>Effective Date: </label>';
    echo '<input type="date" name="effective_year/>';
    echo '<label>Total employees: </label>';
    echo '<input type="number" name="employee_count"/>';
    echo '</div>';
}
