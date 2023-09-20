<?php
require(WC_INSURANCE_DIR . '/loss_of_key_person/limits_and_risk.php');
function load_loss_of_key_person_form()
{
    global $limit_and_risk;
    echo '<div id="insurance-form">';
    echo '<label>Limit: </label>';
    echo '<select name="limit">';
    echo '<option value=""> Select a limit</option>';
    foreach ($limit_and_risk as $limit => $risk) {
        echo '<option value=' . $limit . '>' . $limit . '</option>';
    }

    echo "</select>";
    echo '</div>';
}


function validate_loss_of_key_form(): bool
{
    $limit = intval($_POST['limit']);
    global $limit_and_risk;
    if ($limit === 0) {
        wc_add_notice(__("No limit was selected"), "error");
        return false;
    }
    if (!array_key_exists($limit, $limit_and_risk)) {
        wc_add_notice(__("Incorrect limit selected"), "error");
        return false;
    }
    return true;
}

function save_loss_of_key_data($cart_item_data)
{
    $limit = intval($_POST['limit']);
    global $limit_and_risk;
    $cart_item_data['limit'] == $limit;
    $cart_item_data['risk'] === $limit_and_risk[$limit];
    return $cart_item_data;
}
