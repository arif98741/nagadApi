<?php
/*
 *
 *  * -------------------------------------------------------------
 *  * Copyright (c) 2020
 *  * -created by Ariful Islam
 *  * -All Rights Preserved By
 *  *     Ariful Islam
 *  *    www.phpdark.com
 *  * -If you have any query then knock me at
 *  * arif98741@gmail.com
 *  * See my profile @ https://github.com/arif98741
 *  * ----------------------------------------------------------------
 *
 */

namespace NagadApi\lib;

use Dotenv\Dotenv;


/**
 * Class Key
 * This class is using for generating data from environment variable .env
 * This any change in .env file will affect array vale of $_ENV; Array value of
 * .env can be easily find using generateEnv of Key object
 * @package NagadApi\lib
 */
class Key
{
    private $appEnv;
    private $appAccount;
    private $appMerchantID;
    private $merchantPrivateKey;
    private $pgPublicKey;
    private $currencyCode = '050';

    private $timeZone;

    //private $merchantID = '';

    /**
     * Key constructor.
     * @param $config
     */
    public function __construct($config)
    {
        if (is_object($config)) {

            $data['NAGAD_APP_ENV'] = $config->appEnv;
            $data['NAGAD_APP_ACCOUNT'] = $config->appAccount;
            $data['NAGAD_APP_MERCHANTID'] = $config->appMerchantID;
            $data['NAGAD_APP_MERCHANT_PRIVATE_KEY'] = $config->merchantPrivateKey;
            $data['NAGAD_APP_MERCHANT_PG_PUBLIC_KEY'] = $config->pgPublicKey;
            $data['NAGAD_APP_TIMEZONE'] = $config->timeZone;
            $envData = self::generateEnv($data);

        } else {

            $envData = self::generateEnv($config);
        }

        $this->appEnv = $envData['NAGAD_APP_ENV'];
        $this->appAccount = $envData['NAGAD_APP_ACCOUNT'];
        $this->appMerchantID = $envData['NAGAD_APP_MERCHANTID'];
        $this->merchantPrivateKey = $envData['NAGAD_APP_MERCHANT_PRIVATE_KEY'];
        $this->pgPublicKey = $envData['NAGAD_APP_MERCHANT_PG_PUBLIC_KEY'];
        $this->timeZone = $envData['NAGAD_APP_TIMEZONE'];

    }

    /**
     * Return all data inside .env file as array
     * @param $config
     * @return array
     */
    private function generateEnv($config)
    {
        return $config;
    }

    public function getVariables()
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppEnv()
    {
        return $this->appEnv;
    }

    /**
     * @param mixed $appEnv
     */
    public function setAppEnv($appEnv)
    {
        $this->appEnv = $appEnv;
    }

    /**
     * @return mixed
     */
    public function getAppAccount()
    {
        return $this->appAccount;
    }

    /**
     * @param mixed $appAccount
     */
    public function setAppAccount($appAccount)
    {
        $this->appAccount = $appAccount;
    }

    /**
     * @return mixed
     */
    public function getAppMerchantID()
    {
        return $this->appMerchantID;
    }

    /**
     * @param mixed $appMerchantID
     */
    public function setAppMerchantID($appMerchantID)
    {
        $this->appMerchantID = $appMerchantID;
    }

    /**
     * @return mixed
     */
    public function getMerchantPrivateKey()
    {
        return $this->merchantPrivateKey;
    }

    /**
     * @param mixed $merchantPrivateKey
     */
    public function setMerchantPrivateKey($merchantPrivateKey)
    {
        $this->merchantPrivateKey = $merchantPrivateKey;
    }

    /**
     * @return mixed
     */
    public function getPgPublicKey()
    {
        return $this->pgPublicKey;
    }

    /**
     * @param mixed $pgPublicKey
     */
    public function setPgPublicKey($pgPublicKey)
    {
        $this->pgPublicKey = $pgPublicKey;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param mixed $timeZone
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }

}