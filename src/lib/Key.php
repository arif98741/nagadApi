<?php
/*
 *
 * -------------------------------------------------------------
 * Copyright (c) 2020
 * -All Rights Preserved By Ariful Islam
 * -If you have any query then knock me at
 * arif98741@gmail.com
 * See my profile @ https://github.com/arif98741
 * ----------------------------------------------------------------
 */

namespace Xenon\NagadApi\lib;

/**
 * Class Key
 * This class is using for generating data from environment variable .env
 * This any change in .env file will affect array vale of $_ENV; Array value of
 * .env can be easily find using generateEnv of Key object
 * @package Xenon\NagadApi\lib
 */
class Key
{
    /**
     * @var mixed
     */
    protected $appEnv;
    /**
     * @var mixed
     */
    private $appAccount;
    /**
     * @var mixed
     */
    private $appMerchantID;
    /**
     * @var mixed
     */
    private $merchantPrivateKey;
    /**
     * @var mixed
     */
    private $pgPublicKey;
    /**
     * @var string
     */
    private $currencyCode = '050';

    /**
     * @var mixed|string
     */
    private $timeZone = 'Asia/Dhaka';

    /**
     * Key constructor.
     * @param $config
     * @since v1.3.1
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
        if (array_key_exists('NAGAD_APP_TIMEZONE', $envData)) {
            $this->timeZone = $envData['NAGAD_APP_TIMEZONE'];
        }

    }

    /**
     * Return all data inside .env file as array
     * @param $config
     * @return array
     * @since v1.3.1
     */
    private function generateEnv($config)
    {
        return $config;
    }

    /**
     * @return $this
     * @since v1.3.1
     */
    public function getVariables()
    {
        return $this;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getAppEnv()
    {
        return $this->appEnv;
    }

    /**
     * @param mixed $appEnv
     * @since v1.3.1
     */
    public function setAppEnv($appEnv)
    {
        $this->appEnv = $appEnv;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getAppAccount()
    {
        return $this->appAccount;
    }

    /**
     * @param mixed $appAccount
     * @since v1.3.1
     */
    public function setAppAccount($appAccount)
    {
        $this->appAccount = $appAccount;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getAppMerchantID()
    {
        return $this->appMerchantID;
    }

    /**
     * @param mixed $appMerchantID
     * @since v1.3.1
     */
    public function setAppMerchantID($appMerchantID)
    {
        $this->appMerchantID = $appMerchantID;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getMerchantPrivateKey()
    {
        return $this->merchantPrivateKey;
    }

    /**
     * @param mixed $merchantPrivateKey
     * @since v1.3.1
     */
    public function setMerchantPrivateKey($merchantPrivateKey)
    {
        $this->merchantPrivateKey = $merchantPrivateKey;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getPgPublicKey()
    {
        return $this->pgPublicKey;
    }

    /**
     * @param mixed $pgPublicKey
     * @since v1.3.1
     */
    public function setPgPublicKey($pgPublicKey)
    {
        $this->pgPublicKey = $pgPublicKey;
    }

    /**
     * @return string
     * @since v1.3.1
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param mixed $timeZone
     * @since v1.3.1
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }

}