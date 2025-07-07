<?php

namespace App\Services;

use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;

class TronService
{
    protected $tron;

    public function __construct()
    {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');

        try {
            $this->tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
            throw new \Exception('Failed to connect to Tron network: ' . $e->getMessage());
        }
    }

    public function createWallet(): array
    {
        $account = $this->tron->createAccount();

        return [
            'private_key' => $account->getPrivateKey(),
            'public_address' => $account->getAddress(true),
        ];
    }

    public function getTrxBalance(string $address): float
    {
        $this->tron->setAddress($address);
        $balance = $this->tron->getBalance(null, true);

        return $balance;
    }

    public function sendTrx(string $privateKey, string $toAddress, float $amount, string $from): array
    {
        $this->tron->setAddress($from);
        $this->tron->setPrivateKey($privateKey);

            $transfer = $this->tron->send($toAddress, $amount);
            return [
                'result' => $transfer['result'],
                'txid' => $transfer['txid'],
                'txID' => $transfer['txID'],
            ];
    }
}
