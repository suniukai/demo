<?php
use PHPUnit\Framework\TestCase;
use Commissions\LegalChargePolicy;
use Commissions\Money;
use Commissions\Config;

class LegalChargePolicyTest extends TestCase
{
    protected $chargePolicy;

    protected function setUp()
    {
        Config::init('config/currency.json');
        $this->chargePolicy = new LegalChargePolicy();
    }

    public function testCashIn()
    {
        $serviceTracker = [];
        array_push(
            $serviceTracker,
            [
                'name' => 'cash_in',
                'date' => '2016-01-10',
                'cash' => new Money(1000000.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker);
        $this->assertEquals($charge, new Money(5.00, 'EUR'));
    }

    public function testCashOut()
    {
        $serviceTracker = [];
        array_push(
            $serviceTracker,
            [
                'name' => 'cash_out',
                'date' => '2016-01-06',
                'cash' => new Money(300.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker);
        $this->assertEquals($charge, new Money(0.90, 'EUR'));
    }
}
