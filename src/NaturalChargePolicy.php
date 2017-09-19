<?php
namespace Commissions;

use \Datetime;

class NaturalChargePolicy extends ChargePolicy
{
    const FREE_AMOUNT = 1000;
    const FREE_LIMIT = 3;

    protected function cashOut()
    {
        $service = $this->currentService;
        $currentTime = new DateTime($service['date']);
        $weekDay = $currentTime->format('w') > 0 ? $currentTime->format('w') : 7;
        $prevWeek = (new DateTime($service['date']))->modify("-$weekDay day");

        $servicesThisWeek = [];
        foreach ($this->serviceTracker as $serviceItem) {
            $itemDate = new DateTime($serviceItem['date']);
            if ($serviceItem['name'] == 'cash_out' && $itemDate <= $currentTime && $itemDate > $prevWeek) {
                $servicesThisWeek[] = $serviceItem;
            }
        }

        if (count($servicesThisWeek) >= self::FREE_LIMIT) {
            // free week limit is over
            return $service['cash']->countPercent(parent::CASH_OUT_RATE);
        } else {
            $weekAmount = 0;
            foreach ($servicesThisWeek as $item) {
                $weekAmount += $item['cash']->exchange()->getAmount();
            }
            $freeAmount = self::FREE_AMOUNT - $weekAmount;

            if ($freeAmount > 0) {
                $chargeAble = $service['cash']->exchange()->getAmount() - $freeAmount;
                if ($chargeAble > 0) {
                    return (new Money($chargeAble, 'eur'))->exchange($service['cash']->getCode())->countPercent(parent::CASH_OUT_RATE);
                } else {
                    return new Money(0, $this->currentService['cash']->getCode());
                }
            } else {
                // no free week amount left
                return $service['cash']->countPercent(parent::CASH_OUT_RATE);
            }
        }
    }
}
