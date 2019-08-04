<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$client = new \DnAp\FinancePmApi\FinancePM($argv[1], $argv[2]);

$accounts = $client->getAccountList();
$categories = $client->getIncomeCategoryList();

$payment = new \DnAp\FinancePmApi\Dto\Payment();

$payment->accountId = reset($accounts)->id;
$payment->categoryId = reset($categories)->id;
$payment->amount = 700;
$payment->date = new DateTime('2 day');
$payment->title = 'Salary bonus';
$payment->description = 'Finance PM Api test
See more on github;)';

$client->saveIncome($payment);
