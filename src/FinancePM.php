<?php


namespace DnAp\FinancePmApi;


use DnAp\FinancePmApi\Dto\Account;
use DnAp\FinancePmApi\Dto\Category;
use DnAp\FinancePmApi\Dto\Payment;
use DnAp\FinancePmApi\Exception\InvalidCredentialException;
use DnAp\FinancePmApi\Exception\UnknownException;
use DnAp\FinancePmApi\Parser\AccountListParser;
use DnAp\FinancePmApi\Parser\CategoryListParser;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class FinancePM
{
    protected const URL = 'http://finance.uramaks.com';
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;
    /**
     * @var Client
     */
    private $guzzle;
    /** @var bool */
    private $authorized = false;

    public function __construct(string $login, string $password, Client $guzzle = null)
    {
        $this->login = $login;
        $this->password = $password;
        if ($guzzle === null) {
            $guzzle = $this->createGuzzleClient();
        }
        $this->guzzle = $guzzle;
    }

    protected function createGuzzleClient(): Client
    {
        return new Client([
            'cookies' => new CookieJar(),
        ]);
    }

    /**
     * @return Category[]
     */
    public function getExpenseCategoryList(): array
    {
        $this->login();
        $response = $this->guzzle->get(self::URL . '/expenseCategories/list.html');
        $parser = new CategoryListParser($response->getBody()->__toString());
        return $parser->parse();
    }

    private function login()
    {
        if ($this->authorized) {
            return;
        }
        $response = $this->guzzle->request(
            'POST',
            self::URL . '/loginchecker.html',
            [
                'form_params' => [
                    'j_username' => $this->login,
                    'j_password' => $this->password,
                ],
                'allow_redirects' => false,
            ]
        );
        if ($response->getStatusCode() != 302) {
            throw new UnknownException('Invalid http code: ' . $response->getStatusCode());
        }
        if ($response->getHeaderLine('Location') !== self::URL . '/') {
            throw new InvalidCredentialException();
        }
        $this->authorized = true;
    }

    /**
     * @return Category[]
     */
    public function getIncomeCategoryList(): array
    {
        $this->login();
        $response = $this->guzzle->get(self::URL . '/incomeCategories/list.html');
        $parser = new CategoryListParser($response->getBody()->__toString());
        return $parser->parse();
    }

    /**
     * @return Account[]
     */
    public function getAccountList(): array
    {
        $this->login();
        $response = $this->guzzle->get(self::URL . '/accounts.html');
        $parser = new AccountListParser($response->getBody()->__toString());
        return $parser->parse();
    }

    public function saveExpense(Payment $payment): void
    {
        $this->savePayment($payment, self::URL . '/saveExpense.html');
    }

    public function saveIncome(Payment $payment): void
    {
        $this->savePayment($payment, self::URL . '/saveIncome.html');
    }

    private function savePayment(Payment $expense, string $url): void
    {
        $this->login();

        $this->guzzle->request(
            'POST',
            $url,
            [
                'form_params' => [
                    'p_id' => $expense->id ?? '',
                    'p_name' => $expense->title,
                    'p_accountId' => $expense->accountId,
                    'p_sum' => $expense->amount,
                    'p_date' => $expense->date->format('d-m-Y'),
                    //'p_category' => 'Food',
                    'p_categoryId' => $expense->categoryId,
                    'p_desc' => $expense->description,
                ],
                'allow_redirects' => false,
            ]
        );
        // I can not get id from response
    }
}