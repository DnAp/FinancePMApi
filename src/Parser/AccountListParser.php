<?php


namespace DnAp\FinancePmApi\Parser;


use DnAp\FinancePmApi\Dto\Account;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;

class AccountListParser
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

    /**
     * @return Account[]
     */
    public function parse(): array
    {
        $elements = $this->dom->find('.Content .ContentLine');
        $accounts = [];
        /** @var AbstractNode $element */
        foreach ($elements as $element) {
            $account = new Account();
            /** @var AbstractNode $a */
            $a = $element->find('.ContentLineName a');
            $account->name = $a->text;
            parse_str(parse_url($a->href, PHP_URL_QUERY), $prams);
            $account->id = (int)$prams['p_accountId'];

            /** @var AbstractNode $div */
            $div = $element->find('.ContentBalance');
            [$balance, $currency] = explode(' ', $div->text, 2);
            $account->balance = (float)$balance;
            $account->currencyCode = $currency;

            /** @var AbstractNode $img */
            $img = $element->find('> img');
            preg_match('|/([a-z0-9]+)\.|', $img->src, $matches);
            $account->icon = $matches[1];
            $accounts[] = $account;
        }
        return $accounts;
    }
}