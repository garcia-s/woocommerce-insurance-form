<?php
require("cyber_risk_margin.php");
require("cyber_risk_margin.php");


abstract class ICyberInsuranceRequestError
{
}
class CyberInsuranceRequest
{


    public readonly float $projectedGrossRevenue;
    public readonly int $limit;
    public readonly int $totalEmployees;
    public readonly int $yearOfEffectivePolicy;
    public readonly int $primarySICCode;
    public readonly int $cyberclass;

    private function __construct()
    {
    }

    // public static function new(): CyberInsuranceRequest {
    //   
    // }

    function calculateExpectedLoss(int $limit,): float
    {
        // 1. Look up [Risk Margin]
        // 2. Look up [Trend Factor], [ELR], and [Deductible Factor]
        // 3. Look up WV Class
        // 4. Look up [Hazard Factor] (based on WV Class)
        // 5. Look up [Limit Factor]
        // 6. Look up [Base Premium].  (Based on Total Employee Count.  Interpolate between Base Premiums as needed)
        // For example, if 51 Total Employees, then Base Premium = 5,399 x (70-51) / (70-50) + 6,919 x (51-50) / (70-50)
        // 7. Calculate [TBP]:
        // [Base Premium] x {[Trend Factor] ^ [(Year of Policy Effective Date) - (2020)]}
        // 8. Calculate [Expected Losses]
        // [TBP] x [Limit Factor] x [Deductible Factor] x [ELR] x [Hazard Factor]
        // 9. Calculate [Expected Losses w/Risk Margin]
        // [Expected Losses] x [Risk Margin]
        //
    }
}
