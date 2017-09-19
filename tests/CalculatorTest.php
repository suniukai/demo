<?php
use PHPUnit\Framework\TestCase;
use Commissions\Calculator;
use Commissions\Config;

class CalculatorTest extends TestCase {
    public function setUp()
    {
        Config::init('config/currency.json');
    }

    public function testOutput()
    {
        $expectedResult =
            "0.60\n" .
            "0.00\n" .
            "3.00\n" .
            "0.06\n" .
            "0.90\n" .
            "0\n" .
            "0.70\n" .
            "0.30\n" .
            "0.30\n" .
            "5.00\n" .
            "0.00\n" .
            "0.00\n" .
            "8728";
        $result = implode("\n", Calculator::solve(file('input.csv')));
        $this->assertEquals($result, $expectedResult);
    }
}
