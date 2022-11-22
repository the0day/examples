<?php

use App\Helpers\Infura;
use App\Helpers\Onboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Web3;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    die('index');
    $accounts = [
        [
            'wallet'  => "2fe2476a71466461a0e7bad5693baf6ea2ab5c83",
            'private' => "c2d81234235e4c315e0a9b31c961b9cbe50a9629800e892407baa02835d64c00",
            'public'  => "1fc5575b69bf598899af1ee0685863e3dc54ddcab82a64e770c776edfce1065b4372019b33870e2f516e8cfe2701d936cb0cc5a86067a0faca2273672e54d998"
        ],
        [
            'wallet'  => "61afaf43a474c59619d56f0de41a5a4f3a2e8840",
            'private' => "69d017175b3c84d73cc83291c9d6b778bd686d87d4df10073d486dbba024d479",
            'public'  => "e23ff022c6391dbf95292b12d28d521e6f764523a2c889e000e8128d501bde349766f083e2175bb971e65e5ba140f29e96dea047c6f1f28c11e312c15765a92d"
        ]
    ];

    $infura = Infura::init('aa8707f23079488790fb12794e336edf');

    //$gasPrice = "0x12a05f200";

    //$wei = Utils::toWei('11', 'ether');
    //$wei = new BigNumber('10000000000000000000000');
    /*list($bnq, $bnr) = Utils::toEther($gasPrice, 'kwei');
    dd([
        $bnq->toString().' ETH',
        $bnr->toString()
    ]);

    list($bnq, $bnr) = Utils::toWei('0x3E871B540C000', 'wei');
    dd([
        $bnq->toString(),
        $bnr->toString()
    ]);*/

    $abi = json_decode(Storage::get('pool.json'));
    $provider = new HttpProvider(new HttpRequestManager('https://goerli.infura.io/v3/aa8707f23079488790fb12794e336edf', 10000));
    $web3 = new Web3($provider);
    $contract = new Contract($provider, $abi);

    Onboard::execute($contract, $web3);
});
