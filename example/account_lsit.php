<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$client = new \DnAp\FinancePmApi\FinancePM($argv[1], $argv[2]);
$accounts = $client->getAccountList();
foreach ($accounts as $account) {
    echo $account->__toString() . "\n";
}
