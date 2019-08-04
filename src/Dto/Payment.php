<?php


namespace DnAp\FinancePmApi\Dto;


class Payment
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $title;
    /**
     * @var int
     */
    public $accountId;
    /**
     * @var float
     */
    public $amount;
    /**
     * @var \DateTime
     */
    public $date;
    /**
     * @var int
     */
    public $categoryId;
    /**
     * @var string
     */
    public $description;
}
