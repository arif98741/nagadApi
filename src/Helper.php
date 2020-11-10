<?php


namespace NagadApi;


use NagadApi\lib\Key;

class Helper extends Key
{
    /**
     * Helper constructor.
     */
    public function __construct()
    {
    }

    /**
     * Generate Random String | reference stackoverflow.com
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 40)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Generate Encryption to Public Key
     * @param $data
     * @return string
     */
    function EncryptDataWithPublicKey($data)
    {
        //$pgPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjBH1pFNSSRKPuMcNxmU5jZ1x8K9LPFM4XSu11m7uCfLUSE4SEjL30w3ockFvwAcuJffCUwtSpbjr34cSTD7EFG1Jqk9Gg0fQCKvPaU54jjMJoP2toR9fGmQV7y9fz31UVxSk97AqWZZLJBT2lmv76AgpVV0k0xtb/0VIv8pd/j6TIz9SFfsTQOugHkhyRzzhvZisiKzOAAWNX8RMpG+iqQi4p9W9VrmmiCfFDmLFnMrwhncnMsvlXB8QSJCq2irrx3HG0SJJCbS5+atz+E1iqO8QaPJ05snxv82Mf4NlZ4gZK0Pq/VvJ20lSkR+0nk+s/v3BgIyle78wjZP1vWLU4wIDAQAB";
        $public_key = "-----BEGIN PUBLIC KEY-----\n" . $this->getPgPublicKey() . "\n-----END PUBLIC KEY-----";
        $keyResource = openssl_get_publickey($public_key);
        openssl_public_encrypt($data, $crypttext, $keyResource);
        return base64_encode($crypttext);
    }

    /**
     * Generate Signature
     * @param $data
     * @return string
     */
    public function SignatureGenerate($data)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" . $this->getMerchantPrivateKey() . "\n-----END RSA PRIVATE KEY-----";
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }


    /**
     * HTTP Post Method Request
     * @param $PostURL
     * @param $PostData
     * @return mixed
     */
    public function HttpPostMethod($PostURL, $PostData)
    {
        $url = curl_init($PostURL);
        $postToken = json_encode($PostData);
        $header = array(
            'Content-Type:application/json',
            'X-KM-Api-Version:v-0.2.0',
            'X-KM-IP-V4:' . $this->getClientIP(),
            'X-KM-Client-Type:PC_WEB'
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $postToken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, 0);

        $resultData = curl_exec($url);
        $response = json_decode($resultData, true, 512);
        curl_close($url);
        return $response;
    }

    /**
     * Http Get Method
     * @param $url
     * @return bool|string
     */
    public function HttpGetMethod($url)
    {
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }


    /**
     * Get Client IP | Example : Public IP: 123.93.86.3,987.39.33
     * (these example ips are for just no purpose)
     * @return mixed|string
     */
    public function getClientIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN IP';
        }
        return $ipaddress;
    }

    /**
     * @param $cryptoText
     * @return mixed
     */
    public function DecryptDataWithPrivateKey($cryptoText)
    {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . $this->getMerchantPrivateKey() . "\n-----END RSA PRIVATE KEY-----";
        openssl_private_decrypt(base64_decode($cryptoText), $plain_text, $private_key);
        return $plain_text;
    }





    /**
     * Generate Random Invoice For Testing Purpose
     * You Can also use it for making production
     * @param $prefix
     * @param int $length
     * @param bool $capitalize
     * @return string
     */
    public static function generateInvoiceTest($prefix, $length = 20, $capitalize = false)
    {
        $invoice = $prefix . self::generateRandomString($length);
        if ($capitalize === true) {
            $invoice = strtoupper($invoice);
        }
        return $invoice;
    }

    /**
     * @param $data
     */
    public static function errorLog($data)
    {
        if (!file_exists('logs/nagadApi') && !mkdir('logs', 0775) && !is_dir('logs')) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', 'logs'));
        }

        if (!file_exists('logs/nagadApi/error.log')) {

            $logFile = "logs/error.log";
            $fh = fopen($logFile, 'w+') or die("can't open file");
            fclose($fh);
            chmod($logFile, 0755);
        }
        $date = '=====================' . date('Y-m-d H:i:s') . '=============================================\n';
        file_put_contents('logs/nagadApi/error.log', print_r($date, true), FILE_APPEND);
        file_put_contents('logs/nagadApi/error.log', PHP_EOL . print_r($data, true), FILE_APPEND);
        $string = '=====================' . date('Y-m-d H:i:s') . '=============================================' . PHP_EOL;
        file_put_contents('logs/nagadApi/error.log', print_r($string, true), FILE_APPEND);
    }

    /**
     * Generate Server Details And Return Response
     * @return array
     */
    public static function serverDetails(): array
    {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'],
            'port' => $_SERVER['REMOTE_PORT'],
            'user agent' => $_SERVER['HTTP_USER_AGENT']
        ];
    }

    public function  test()
    {
        $FIELD_enableCountdownTimer = '';
        $product = '';
       if(isset($FIELD_enableCountdownTimer) && date('Y-m-d') > $product.specific_prices.to){

       }

    }
}