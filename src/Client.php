<?php
namespace Commissions;

class Client
{
    protected $type;
    protected $serviceTracker = [];
    protected $chargePolicy;

    public function __construct($type)
    {
        $this->setType($type);
    }

    public function setType($type)
    {
        $this->type = $type;
        switch ($type) {
            case 'legal':
                $this->chargePolicy = new LegalChargePolicy();
                break;
            case 'natural':
            default:
                $this->chargePolicy = new NaturalChargePolicy();
                break;
        }
    }

    public function getPayment($service)
    {
        $this->serviceTracker[] = $service;
        return $this->chargePolicy->charge($this->serviceTracker);
    }

}
