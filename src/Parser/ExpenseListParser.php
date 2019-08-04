<?php


namespace DnAp\FinancePmApi\Parser;


use PHPHtmlParser\Dom;

class ExpenseListParser
{
    /**
     * @var Dom
     */
    protected $dom;

    public function __construct(string $html)
    {
        $this->dom = new Dom();
        $this->dom->loadStr($html);
    }

    public function parseFirstLine()
    {

    }
}