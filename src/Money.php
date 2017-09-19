<?php
namespace Commissions;

class Money
{
    protected $code;
    protected $amount;
    protected $currencies;

    public function __construct($amount, $code = 'eur')
    {
        $this->code = strtolower($code);
        try {
            $this->currencies = Config::currencies();
            $this->amount = $amount * $this->currencies[$this->code]['units'];
        } catch(\Exception $e) {
           throw $e;
        }
    }

    public function exchange($to = 'eur')
    {
        $to = strtolower($to);
        if ($to == 'eur') {
            return new Money($this->amount / $this->currencies[$this->code]['units'] / $this->currencies[$this->code]['rate'], $to);
        } elseif ($this->code == 'eur') {
            return new Money($this->amount * $this->currencies[$to]['rate'] / $this->currencies[$this->code]['units'], $to);
        } else {
            return $this->exchange()->exchange($to);
        }
    }

    public function countPercent($percent)
    {
        return new Money(ceil($this->amount * $percent / 100) / $this->currencies[$this->code]['units'], $this->code);
    }

    public function getAmount()
    {
        return $this->amount / $this->currencies[$this->code]['units'];
    }

    public function getCode()
    {
        return $this->code;
    }

    public function __toString()
    {
        $prec = log10($this->currencies[$this->code]['units']);
        $format = "%.{$prec}f";
        return sprintf($format, $this->getAmount());
    }
}
