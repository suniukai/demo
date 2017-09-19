<?php
use PHPUnit\Framework\TestCase;
use Commissions\NaturalChargePolicy;
use Commissions\Money;
use Commissions\Config;

class NaturalChargePolicyTest extends TestCase
{
    protected $chargePolicy;

    protected function setUp()
    {
        Config::init('config/currency.json');
        $this->chargePolicy = new NaturalChargePolicy();
    }

    public function testCashIn()
    {
        $serviceTracker = [];
        array_push(
            $serviceTracker,
            [
                'name' => 'cash_in',
                'date' => '2016-01-05',
                'cash' => new Money(200.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker);
        $this->assertEquals($charge, new Money(0.06, 'EUR'));
    }

    public function testCashOut()
    {
        // User 1
        $serviceTracker1 = [];
        array_push(
            $serviceTracker1,
            [
                'name' => 'cash_out',
                'date' => '2015-01-01',
                'cash' => new Money(1200.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker1);
        $this->assertEquals($charge, new Money(0.60, 'EUR'));

        array_push(
            $serviceTracker1,
            [
                'name' => 'cash_out',
                'date' => '2015-12-31',
                'cash' => new Money(1000.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker1);
        $this->assertEquals($charge, new Money(0.00, 'EUR'));

        array_push(
            $serviceTracker1,
            [
                'name' => 'cash_out',
                'date' => '2016-01-01',
                'cash' => new Money(1000.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker1);
        $this->assertEquals($charge, new Money(3.00, 'EUR'));

        // User 2
        $serviceTracker2 = [];
        array_push(
            $serviceTracker2,
            [
                'name' => 'cash_out',
                'date' => '2016-01-06',
                'cash' => new Money(30000, 'JPY')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker2);
        $this->assertEquals($charge, new Money(0, 'JPY'));

        array_push(
            $serviceTracker2,
            [
                'name' => 'cash_out',
                'date' => '2016-01-07',
                'cash' => new Money(1000.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker2);
        $this->assertEquals($charge, new Money(0.70, 'EUR'));

        array_push(
            $serviceTracker2,
            [
                'name' => 'cash_out',
                'date' => '2016-01-07',
                'cash' => new Money(100.00, 'USD')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker2);
        $this->assertEquals($charge, new Money(0.30, 'USD'));

        array_push(
            $serviceTracker2,
            [
                'name' => 'cash_out',
                'date' => '2016-01-10',
                'cash' => new Money(100.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker2);
        $this->assertEquals($charge, new Money(0.30, 'EUR'));

        // User 4
        $serviceTracker4 = [];
        array_push(
            $serviceTracker4,
            [
                'name' => 'cash_out',
                'date' => '2016-01-10',
                'cash' => new Money(1000.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker4);
        $this->assertEquals($charge, new Money(0.00, 'EUR'));

        // User 2
        array_push(
            $serviceTracker2,
            [
                'name' => 'cash_out',
                'date' => '2016-02-15',
                'cash' => new Money(300.00, 'EUR')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker2);
        $this->assertEquals($charge, new Money(0.00, 'EUR'));

        array_push(
            $serviceTracker2,
            [
                'name' => 'cash_out',
                'date' => '2016-02-19',
                'cash' => new Money(3000000, 'JPY')
            ]
        );
        $charge = $this->chargePolicy->charge($serviceTracker2);
        $this->assertEquals($charge, new Money(8728, 'JPY'));
    }
}
