<?php

namespace App\Helpers;

use App\Settings\GeneralSettings;
use Exception;
use GuzzleHttp\Client;
use Log;
use Web3\Utils;

class Infura
{
    private static $client;
    private static int $chainId = 0;

    /**
     * @throws Exception
     */
    public static function init(string $apiKey = null)
    {
        if (!$apiKey) {
            $apiKey = app(GeneralSettings::class)->infura_key;
        }

        if (!$apiKey) {
            throw new Exception('Invalid infura key');
        }

        self::$client = new Client([
            'base_uri' => 'https://goerli.infura.io/v3/' . $apiKey,
            'headers'  => [
                'Content-Type' => 'application/json'
            ]
        ]);

        return new static;
    }

    public static function getClient(): Client
    {
        if (!self::$client) {
            self::init();
        }
        return self::$client;
    }

    public static function getAccounts(array $accounts)
    {
        return self::post('eth_accounts', [$accounts, 'latest']);
    }

    public static function getCurrentBlockId()
    {
        return self::post('eth_blockNumber');
    }

    public function getBlockByHex(string $hex)
    {
        return $this->post('eth_getBlockByNumber', [$hex, false]);
    }

    public static function getBalance(string $address): ?float
    {
        if (!Utils::isZeroPrefixed($address)) {
            $address = '0x' . $address;
        }

        $response = self::post('eth_getBalance', [$address, 'latest']);
        if ($response->hasError()) {
            Log::info("Unable to receive balance for address {$address}. (" . $response->getErrorCode() . ": " . $response->getErrorMessage() . ")");
            return null;
        }

        list($bnq, $bnr) = Utils::fromWei($response->getResponse(), 'ether');

        return $bnq->toString() . '.' . str_pad($bnr->toString(), 18, '0', STR_PAD_LEFT);
    }

    public static function getGasPrice()
    {
        return self::post('eth_gasPrice', []);
    }

    public static function getChainId(): int
    {
        if (static::$chainId == 0) {
            $response = new InfureResponse(
                self::getClient()->post('', [
                    'json' => [
                        'method'  => 'eth_chainId',
                        'jsonrpc' => '2.0',
                        'params'  => [],
                        'id'      => 1
                    ]])
                    ->getBody()
                    ->getContents()
            );
            static::$chainId = hexdec($response->getResponse());
        }
        return static::$chainId;
    }

    public function isContract(string $account): bool
    {
        return $this->post('eth_getCode', [$account, 'latest']) !== '0x';
    }


    public static function getBlockByNumber(string $blockHash, bool $withDetails = false)
    {
        return self::post('eth_getBlockByNumber', [$blockHash, $withDetails]);
    }

    public static function getTransactionCount(string $address)
    {
        return self::post('eth_getTransactionCount', [$address, 'latest']);

    }

    public static function retrieveTransactions(string $blockHash, array $txIds): ?array
    {
        $response = self::getBlockByNumber($blockHash, true);

        if ($response->hasError()) {
            return null;
        }

        $response = $response->getResponse();
        if (isset($response['transactions'])) {
            $transactions = $response['transactions'];
            return array_filter($transactions, function ($v) use ($txIds) {
                return in_array($v['hash'], $txIds);
            });
        }
        return null;
    }

    private static function getData(array $params = []): array
    {
        return [
            'json' => array_merge([
                'jsonrpc' => '2.0',
                'params'  => [],
                'id'      => self::getChainId(),
            ], $params)
        ];
    }

    public static function post(string $method, array $params = []): InfureResponse
    {

        $data['method'] = $method;
        $data['params'] = $params;
        $response = self::getClient()->post('', self::getData($data))
            ->getBody()
            ->getContents();

        return new InfureResponse($response);
    }
}
