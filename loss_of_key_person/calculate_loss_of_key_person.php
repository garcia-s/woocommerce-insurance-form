<?php

require_once("./limit_and_risk.php");

function calculate_loss_of_key_person_losses(int &$limit, int &$covered_employees): float
{
    global $limit_and_risk;
    // Get risk_margin
    $risk_margin = $limit_and_risk[$limit];
    // Calculate Expected losses
    $expected_losses = $covered_employees * 0.01 * $limit;
    // Return expected loss with risk management
    return $expected_losses * $risk_margin;
}
