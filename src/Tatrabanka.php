<?php

namespace SudoAgency\TatraBanka;

class Tatrabanka
{

    public function test()
    {
        return "URL:" . $this->getTatraPayURL(95.99, "http://tatrabanka.dev", "", "", "", "Platba za služby");
    }

    public function getTatraPayURL($amount, $returnUrl, $variableSymbol = "", $specificSymbol = "", $constantSymbol = "", $paymentReference = "")
    {

        $url = "https://moja.tatrabanka.sk/cgi-bin/e-commerce/start/e-commerce.jsp";

        $query = "?PT=TatraPay&MID=" . config('tatrabanka.merchant-id');
        $query .= "&AMT=" . number_format($amount, 2, '.', '');
        $query .= "&CURR=978";

        $signature = config('tatrabanka.merchant-id') . number_format($amount, 2, '.', '') . '978';

        if ($variableSymbol !== "")
        {
            $query .= "&VS=" . $variableSymbol;
            $query .= "&SS=" . $specificSymbol;
            $query .= "&CS=" . $constantSymbol;
            $signature .= $variableSymbol . $specificSymbol . $constantSymbol;
        }
        else
        {
            $query .= "&REF=" . $paymentReference;
            $signature .= $paymentReference;
        }

        $query .= "&RURL=" . $returnUrl;
        
        $signature .= $returnUrl;
        
        $signature = $this->getSignature($signature);

        $query .= "&SIGN=" . $signature;

        if (config('tatrabanka.notification-number'))
        {
            $query .= '&RSMS=' . config('tatrabanka.notification-number');
        }

        if (config('tatrabanka.notification-email'))
        {
            $query .= '&REM=' . config('tatrabanka.notification-email');
        }
        
        return $url . $query;
    }

    private function getSignature($str)
    {
        $signature = sha1($str, true);
        $signature = substr($signature, 0, 16);
    
        $encryption_key = config('tatrabanka.merchant-key');
        
        if (strlen($encryption_key) === 64)
        {
            $encryption_key = pack('H*', $encryption_key);
        }

        define('AES_256_ECB', 'aes-256-ecb');
        $encrypted = openssl_encrypt($signature, AES_256_ECB, $encryption_key, OPENSSL_RAW_DATA);
        $signature = substr(bin2hex($encrypted), 0, 32);
        
        return $signature;
    }

}