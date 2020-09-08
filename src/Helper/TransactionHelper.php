<?php


namespace App\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TransactionHelper
{
    const SUCCESS_CODE = [201, 200];
    /**
     * @var ContainerInterface
     */
    protected $containerInterface;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var HttpClientInterface
     */
    protected $client;

    public function __construct(ContainerInterface $containerInterface, LoggerInterface $logger, HttpClientInterface $client)
    {
        $this->containerInterface = $containerInterface;
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     *  build the head of request
     */
    public function getWsseHeader()
    {

        $this->logger->info("build header of request to send");
        $login = $this->containerInterface->getParameter("api.login");
        $password = $this->containerInterface->getParameter("api.password");
        $nonce = "";                    // The nonce
        $nonce64 = "";                    // The nonce with a Base64 encoding
        $date = "";                    // The date of the request, in  ISO 8601 format
        $digest = "";                    // The password digest needed to authenticate you
        $header = "";                    // The final header to put in your request

        $chars = "0123456789abcdef";
        for ($i = 0; $i < 32; $i++) {
            $nonce .= $chars[rand(0, 15)];
        }
        $nonce64 = base64_encode($nonce);

        $date = gmdate('c');
        $date = substr($date, 0, 19) . "Z";

        $digest = base64_encode(sha1($nonce . $date . $password, true));

        $header = sprintf('UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"', $login, $digest, $nonce64, $date);
        return ['X-WSSE' => $header,
            'Content-Type' => 'application/json',
            'buffer' => false
        ];

    }

    /**
     * fetch  wallet's list
     */
    public function fetchWallets(): array
    {

        $this->logger->info("fetch list of wallets");
        $response = $this->client->request(
            'GET',
            $this->containerInterface->getParameter('api.base') . $this->containerInterface->getParameter('api.wallets') . "?" . $this->containerInterface->getParameter("api.sort"),
            [
                'headers' => $this->getWsseHeader()
            ]
        );
        $result["code"] = $response->getStatusCode();
        if (!in_array($result["code"], self::SUCCESS_CODE)) {
            $result["message"] = $response->getInfo();
            $result['data'] = null ;
            $this->logger->error($response->getStatusCode() . " : " . $response->getInfo('debug'));
        } else {
            $result['data'] = $response->toArray();
            $this->logger->info($response->getStatusCode() . " : success to get wallets");
        }
        return $result;
    }

    /**
     * get detail of wallet
     */
    public function findWalletById(string $id) :array
    {
        $this->logger->info("fetch wallet by id");
        $response = $this->client->request(
            'GET',
            $this->containerInterface->getParameter('api.base') . $this->containerInterface->getParameter('api.wallets') . $this->containerInterface->getParameter('api.param') . $id,
            [
                'headers' => $this->getWsseHeader()
            ]
        );
        $result["code"] = $response->getStatusCode();
        if (!in_array($result["code"], self::SUCCESS_CODE)) {
            $result["message"] = $response->getInfo();
            $result['data'] = null ;
            $this->logger->error($response->getStatusCode() . " : " . $response->getInfo('debug'));
        } else {
            $result['data'] = $response->toArray();
            $this->logger->info($response->getStatusCode() . " : success to get details wallet");
        }
        return $result;
    }


    /**
     * fetch financial movements list
     */
    public function getfinancialMovementsByWalletId(string $walletId): array
    {
        $this->logger->info("fetch list of financial Movements ");
        $response = $this->client->request(
            'GET',
            $this->containerInterface->getParameter('api.base') . $this->containerInterface->getParameter('api.financialMovements') . $this->containerInterface->getParameter('api.financialMovements.walletId') .  $walletId,
            [
                'headers' => $this->getWsseHeader()
            ]
        );
        $result["code"] = $response->getStatusCode();
        if (!in_array($result["code"], self::SUCCESS_CODE)) {
            $result["message"] = $response->getInfo();
            $result['data'] = null ;
            $this->logger->error($response->getStatusCode() . " : " . $response->getInfo('debug'));
        } else {
            $result['data'] = $response->toArray();
            $this->logger->info($response->getStatusCode() . " : success to get financial Movements By WalletId");
        }
        return $result;
    }

    /**
     * get detail of financial Movement
     */
    public function findfinancialMovementsById(string $id) : array
    {
        $this->logger->info("fetch financial Movement by id");
        $response = $this->client->request(
            'GET',
            $this->containerInterface->getParameter('api.base') . $this->containerInterface->getParameter('api.financialMovements') . $this->containerInterface->getParameter('api.param') . $id,
            [
                'headers' => $this->getWsseHeader()
            ]
        );
        $result["code"] = $response->getStatusCode();
        if (!in_array($result["code"], self::SUCCESS_CODE)) {
            $result["message"] = $response->getInfo();
            $result['data'] = null ;
            $this->logger->error($response->getStatusCode() . " : " . $response->getInfo('debug'));
        } else {
            $result['data'] = $response->toArray();
            $this->logger->info($response->getStatusCode() . " : success to get details financial Movement");
        }
        return $result;
    }

    /**
     * get detail of financial Movement
     */
    public function findfinancialMovements() : array
    {
        $this->logger->info("fetch all financial Movements ");
        $response = $this->client->request(
            'GET',
            $this->containerInterface->getParameter('api.base') . $this->containerInterface->getParameter('api.financialMovements'),
            [
                'headers' => $this->getWsseHeader()
            ]
        );
        $result["code"] = $response->getStatusCode();
        if (!in_array($result["code"], self::SUCCESS_CODE)) {
            $result["message"] = $response->getInfo();
            $result['data'] = null ;
            $this->logger->error($response->getStatusCode() . " : " . $response->getInfo('debug'));
        } else {
            $result['data'] = $response->toArray();
            $this->logger->info($response->getStatusCode() . " : success to get all financial Movements");
        }
        return $result;
    }
}
