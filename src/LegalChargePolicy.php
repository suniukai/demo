<?php
namespace Commissions;

class LegalChargePolicy extends ChargePolicy
{
    const MIN_OUT_CHARGE = 0.50;

    protected function cashOut()
    {
        $charge = $this->currentService['cash']->countPercent(parent::CASH_OUT_RATE);
        if ($charge->exchange()->getAmount() < self::MIN_OUT_CHARGE) {
            return new Money(self::MIN_OUT_CHARGE, $this->currentService['cash']->getCode());
        } else {
            return $charge;
        }
    }
}
