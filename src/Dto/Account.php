<?php


namespace DnAp\FinancePmApi\Dto;


class Account
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var float
     */
    public $balance;
    /**
     * @var string
     */
    public $currencyCode;
    /**
     * @var string
     */
    public $icon;

    public function __toString()
    {
        return $this->id . "\t" . $this->name . "\t" . $this->balance . ' ' . $this->currencyCode . "\t" . $this->icon;
    }
}