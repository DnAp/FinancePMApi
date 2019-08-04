<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$client = new \DnAp\FinancePmApi\FinancePM($argv[1], $argv[2]);
echo "Expense categories: \n";
$categories = $client->getExpenseCategoryList();
foreach ($categories as $category) {
    echo '  ' . $category . "\n";
}
echo "Income categories: \n";
$categories = $client->getIncomeCategoryList();
foreach ($categories as $category) {
    echo '  ' . $category . "\n";
}
