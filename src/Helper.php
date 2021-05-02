<?php

namespace NagadApi;


use NagadApi\lib\Key;

class Helper extends Key
{
    /**
     * Helper constructor.
     * @param $config
     * @since v1.3.1
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * Generate Random String | reference stackoverflow.com
     * @param int $length
     * @param string $prefix
     * @param string $suffix
     * @return string
     * @since v1.3.1
     */
    public static function generateRandomString($length = 40, $prefix = '', $suffix = '')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if (!empty($prefix)) {
            $randomString = $prefix . $randomString;
        }
        if (!empty($suffix)) {
            $randomString .= $suffix;
        }
        return $randomString;
    }


    /**
     * Generate Encryption to Public Key
     * @param $data
     * @return string
     * @since v1.3.1
     */
    function EncryptDataWithPublicKey($data)
    {

        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . $this->getPgPublicKey() . "\n-----END PUBLIC KEY-----";
        $keyResource = openssl_get_publickey($publicKey);
        openssl_public_encrypt($data, $cryptoText, $keyResource);
        return base64_encode($cryptoText);
    }

    /**
     * Generate Signature
     * @param $data
     * @return string
     * @since v1.3.1
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
     * @since v1.3.1
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
        $curl_error = curl_error($url);

        if (!empty($curl_error)) {
            return [
                'error' => $curl_error
            ];
        } else {
            $response = json_decode($resultData, true, 512);
            curl_close($url);
            return $response;
        }
    }

    /**
     * Get Client IP | Example : Public IP: 121.23.48.96. 185.96.85.256
     * (above ips are for just example)
     * @return mixed|string
     * @since v1.3.1
     */
    public function getClientIP()
    {
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
     * @since v1.3.1
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
     * @param int $length
     * @param bool $capitalize
     * @param string $prefix
     * @param string $suffix
     * @return string
     * @since v1.3.1
     */
    public static function generateFakeInvoice($length = 20, $capitalize = false, $prefix = '', $suffix = '')
    {
        $invoice = $prefix . self::generateRandomString($length) . $suffix;
        if ($capitalize === true) {
            $invoice = strtoupper($invoice);
        }
        return $invoice;
    }

    /**
     * @param $data
     * @since v1.3.1
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
     * @since v1.3.1
     */
    public static function serverDetails()
    {
        return [
            'base' => $_SERVER['SERVER_ADDR'],
            'ip' => $_SERVER['REMOTE_ADDR'],
            'port' => $_SERVER['REMOTE_PORT'],
            'request_url' => $_SERVER['REQUEST_URI'],
            'user agent' => $_SERVER['HTTP_USER_AGENT'],
        ];
    }

    /**
     * This is for formatting and getting returning response data from url;
     * @param $response
     * @return mixed
     * @since v1.3.1
     */
    public function successResponse($response)
    {
        // $response = 'https://phpdark.com/payment/success/id=4/?merchant=683002007104225&order_id=EBSXGJ5OYQCRO7D&payment_ref_id=MTEyOTAwMjY1NDMxNi42ODMwMDIwMDcxMDQyMjUuRUJTWEdKNU9ZUUNSTzdELmExODVkYWE4MDAyMDEyM2ZlYzRl&status=Success&status_code=00_0000_000&message=Successful%20Transaction&payment_dt=20201129002747&issuer_payment_ref=MTEyOTAwMjY1NDMxNi42ODMwMDIwMDcxMDQyMjUuRUJTWEdKNU9ZUUNSTzdELmExODVkYWE4MDAyMDEyM2ZlYzRl';
        $parts = parse_url($response);
        parse_str($parts['query'], $query);
        return $query;
    }

}