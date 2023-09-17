<?php

declare(strict_types=1);

interface IWorkplaceViolenceFailure
{
    public string $type;
    public string $message;
}



class InvalidLimitFailure extends IWorkplaceViolenceFailure
{
    public string $type = "limit_not_existent";
    public string $message = "The selected limit does not exist in the list of limits";
}



class WorkplaceViolenceRequest
{
    final const TREND_FACTOR = 1.05;
    final const EXPECTED_LOSS_RATIO = 0.65;
    final const DEDUCTIBLE_FACTOR = 1.225;

    private $wv_class;
    private $wv_hazard_factor;
    private $wv_risk_margin;
    private $wv_limit_factor;
    private $wv_base_premium;

    private int $limit;
    private int $effective_year;
    private int $sic_code;
    private int $employee_count;


    private function __construct(
        int $limit,
        int $sic_code,
        int $employee_count,
        int $effective_year,
    ) {
        require('./wv_data.php');
        
        $this->wv_class = &$wv_class;
        $this->wv_hazard_factor = &$wv_hazard_factor;
        $this->wv_risk_margin = &$wv_risk_margin;
        $this->wv_limit_factor = &$wv_limit_factor;
        $this->wv_base_premium = &$wv_base_premium;

        $this->limit = $limit;
        $this->sic_code = $sic_code;
        $this->$employee_count = $employee_count;
        $this->effective_year = $effective_year;
    }

    public static function new(int $limit, int $sic_code, int $employee_count, int $effective_year): WorkPlaceViolenceRequest|IWorkplaceViolenceFailure
    {
        return new WorkPlaceViolenceRequest(
            $limit,
            $sic_code,
            $employee_count,
            $effective_year,
        );
    }

    public function calculate_losses(): float
    {
        // 1. Look up [Risk Margin]
        $risk_margin = $this->wv_risk_margin[$this->limit];
        // 2. Look up [Trend Factor], [ELR], and [Deductible Factor]
        // 3. Look up WV Class
        $wv_class = $this->wv_class[$this->sic_code]["wv_class"];
        // 4. Look up [Hazard Factor] (based on WV Class)
        $hazard_factor = $this->wv_hazard_factor[$wv_class];
        // 5. Look up [Limit Factor]
        $limit_factor = $this->wv_limit_factor[$this->limit];
        // 6. Look up [Base Premium].  (Based on Total Employee Count.  Interpolate between Base Premiums as needed)
        // For example, if 51 Total Employees, then Base Premium = 5,399 x (70-51) / (70-50) + 6,919 x (51-50) / (70-50)
        $base_premium = $this->_getBasePremium();
        // 7. Calculate [TBP]:
        // [Base Premium] x {[Trend Factor] ^ [(Year of Policy Effective Date) - (2020)]}
        $tbp = $base_premium * ($this::TREND_FACTOR) ^ ($this->effective_year - 2020);
        // 8. Calculate [Expected Losses]
        // [TBP] x [Limit Factor] x [Deductible Factor] x [ELR] x [Hazard Factor]
        $expected_loss = $tbp * $limit_factor * $this::DEDUCTIBLE_FACTOR * $this::EXPECTED_LOSS_RATIO * $hazard_factor;
        // 9. Calculate [Expected Losses w/Risk Margin]
        // [Expected Losses] x [Risk Margin]
        $expected_loss_with_risk = $expected_loss * $risk_margin;

        return $expected_loss_with_risk;
    }

    private function _getBasePremium()
    {

        $last = count($this->wv_base_premium) - 1;
        foreach ($this->wv_base_premium as $key => $premium) {
            if ($this->employee_count < $key) return $premium;
        }
        return $this->wv_base_premium[$last];
    }
}
