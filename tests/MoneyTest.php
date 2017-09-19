<?php
use PHPUnit\Framework\TestCase;
use Commissions\Money;
use Commissions\Config;

class MoneyTest extends TestCase
{
    public function setUp()
    {
        Config::init('config/currency.json');
    }

    public function testExchange()
    {
        $this->assertEquals((new Money(1.00, 'EUR'))->exchange('USD'), new Money(1.1497, 'USD'));
        $this->assertEquals((new Money(3.50, 'EUR'))->exchange('USD'), new Money(1.1497 * 3.50, 'USD'));
        $this->assertEquals((new Money(1.00, 'EUR'))->exchange('JPY'), new Money(129.53, 'JPY'));
        $this->assertEquals((new Money(4.75, 'EUR'))->exchange('JPY'), new Money(129.53 * 4.75, 'JPY'));
        $this->assertEquals((new Money(5.67, 'USD'))->exchange('EUR'), new Money(5.67 / 1.1497, 'EUR'));
        $this->assertEquals((new Money(564, 'JPY'))->exchange('EUR'), new Money(564 / 129.53, 'EUR'));
        $this->assertEquals((new Money(0.00, 'USD'))->exchange('EUR'), new Money(0.00, 'EUR'));
    }

    public function testCountPercent()
    {
        $this->assertEquals((new Money(200.00, 'EUR'))->countPercent(0.3), new Money(0.60, 'EUR'));
        $this->assertEquals((new Money(30000, 'JPY'))->countPercent(0.03), new Money(9, 'JPY'));
    }

    public function testGetCode()
    {
        $this->assertEquals((new Money(200.00, 'USD'))->getCode(), 'usd');
    }

    public function testGetAmount()
    {
        $this->assertEquals((new Money(200.00, 'USD'))->getAmount(), 200.00);
    }
}
