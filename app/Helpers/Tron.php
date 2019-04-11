<?php

namespace App\Helpers;

use App\User;
use App\TronWallet;
use App\Settings;


class Tron
{


    /** transaction create */
    public static function transfer($fromAddress, $fromPKey, $toAddress, $tAmount)
    {

        $tronApi = Settings::where('key', 'tron_api_url')->first()->value;
        $tranferApi = $tronApi.'/api/v1/wallet/transaction/'.$fromAddress;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $tranferApi);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        $data = [
            'privateKey' => $fromPKey,
            'token' => 'TRX',
            'toAddress' =>  $toAddress,
            'amount' =>  $tAmount,
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($curl);
        $resJson = json_decode($response);
        curl_close($curl);

        $resdata = [
            'success' => isset($resJson->result->result) && $resJson->result->result == true ? true : false,
            'data' => $resJson
        ];
       
        return $resdata;
    }





    /** get market price only usd */
    public static function marketPrice()
    {
        $tronApi = Settings::where('key', 'tron_api_url')->first()->value;
        $createAddressApi = $tronApi.'/api/v1/wallet/getMarketPrice';
        
        $curl = curl_init();        
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $createAddressApi
        ]);
        $response = curl_exec($curl);
        $resJson = json_decode($response);
        curl_close($curl);


        $collection = collect($resJson);
        $pairObject = $collection->where("pair", 'TRX/USDT')->first();
       
        return $pairObject->price;

    }





    public static function createdAddress()
    {
        //create address
        $tronApi = Settings::where('key', 'tron_api_url')->first()->value;
        $createAddressApi = $tronApi.'/api/v1/wallet/createAddress';
        
        $curl = curl_init();        
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $createAddressApi
        ]);
        $response = curl_exec($curl);
        $resJson = json_decode($response);
        curl_close($curl);

        return $resJson;
    }


    public static function getAdminInfo()
    {
        return [
            'address_baes58' => Settings::where('key', 'tron_address_base58')->first()->value,
            'address_hex' => Settings::where('key', 'tron_address_hex')->first()->value,
            'private_key' => Settings::where('key', 'tron_private_key')->first()->value
        ];
    }


    public static function getAdminBalance()
    {
        $adminAddress = Settings::where('key', 'tron_address_base58')->first()->value;
        $balance = self::getBalanceCurl($adminAddress);
        return $balance;
    }



    public static function getBalanceCurl($addressbase58)
    {
        //balance address
        $tronApi = Settings::where('key', 'tron_api_url')->first()->value;
        $createAddressApi = $tronApi.'/api/v1/wallet/getBalance/'.$addressbase58;
        
        $curl = curl_init();        
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $createAddressApi
        ]);
        $response = curl_exec($curl);
        $resJson = json_decode($response);
        curl_close($curl);

        return $resJson;

    }




    public static function getUserBalance($userId)
    {
        $wallet = self::getUserWallet($userId);
        $balance = self::getBalanceCurl($wallet->address_base58);
        return $balance;
    }




    public static function getUserWallet($userId)
    {
        $user = User::find($userId);
        $wallet = TronWallet::where("user_id", $user->id)->first();

        /** if user tron wallet not presend then create new address and wallet */
        if(!$wallet) {

            $response = self::createdAddress();

            $wallet = new TronWallet;
            $wallet->user_id = $userId;
            $wallet->private_key = $response->privateKey;
            $wallet->public_key = $response->publicKey;
            $wallet->address_base58 = $response->address->base58;
            $wallet->address_hex = $response->address->hex;
            $wallet->save();

        }
        
        return $wallet;        

    }


}