<?php
namespace Commissions;

abstract class ChargePolicy
{
    const MAX_IN_CHARGE = 5;
    const CASH_IN_RATE = 0.03;
    const CASH_OUT_RATE = 0.3;
    protected $serviceTracker;
    protected $currentService;

    public function charge($serviceTracker)
    {
        $this->serviceTracker = $serviceTracker;
        $this->currentService = array_pop($this->serviceTracker);
        switch($this->currentService['name']) {
            case 'cash_in':
                $charge = $this->cashIn();
                break;
            case 'cash_out':
                $charge = $this->cashOut();
                break;
            default:
                $charge = null;
                break;
        }
        return $charge;
    }

    protected function cashIn()
    {
        $charge = $this->currentService['cash']->countPercent(self::CASH_IN_RATE);
        if ($charge->exchange()->getAmount() > self::MAX_IN_CHARGE) {
            return new Money(self::MAX_IN_CHARGE, $this->currentService['cash']->getCode());
        } else {
            return $charge;
        }
    }

    abstract protected function cashOut();
}
