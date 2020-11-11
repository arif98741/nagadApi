<?php
/*
 *
 *  * -------------------------------------------------------------
 *  * Copyright (c) 2020
 *  * -created by Ariful Islam
 *  * -All Rights Preserved By
 *  *    Ariful Islam
 *  *    www.phpdark.com
 *  * -If you have any query then knock me at
 *  * arif98741@gmail.com
 *  * See my profile @ https://github.com/arif98741
 *  * ----------------------------------------------------------------
 *
 */

namespace NagadApi;


use NagadApi\lib\Key;

/**
 * Class Base
 * This is the decision maker where request will go, generate url and also
 * decide the environment according to .env data
 * @package NagadApi
 */
class Base
{

    /**
     * environment
     */
    public $environment = 'development';

    /**
     * @var string
     */
    private $base_url = 'http://sandbox.mynagad.com:10080/';
    /**
     * @var string
     */
    private $timezone;
    /**
     * @var mixed
     */
    private $amount;
    /**
     * @var mixed
     */
    private $invoice;
    /**
     * @var mixed
     */
    private $merchantID;

    /**
     * public key object
     */
    public $keyObject;

    /**
     * CallBack Url for merchant
     */
    public $merchantCallback;


    /**
     * Base constructor
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->keyObject = new Key();

        $this->amount = $params['amount'];
        $this->invoice = $params['invoice'];
        $this->merchantID = $this->keyObject->getAppMerchantID();
        $this->merchantCallback = $params['merchantCallback'];
        $this->setTimeZone($this->keyObject->getTimeZone());

        /**
         * Before activating production environment be confirm that your system is ok and out of bug
         * it is highly recommended to test your environment using development environment
         */
        if ($this->keyObject->getAppEnv() == 'production') {
            $this->base_url = 'https://payment.mynagad.com:30000/';
            $this->environment = $this->keyObject->getAppEnv();
        }

    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param $timeZone
     */
    public function setTimeZone($timeZone)
    {
        if (!empty($timeZone)) {
            date_default_timezone_set($timeZone);
        } else {
            date_default_timezone_set('Asia/Dhaka');
        }
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return mixed
     */
    public function getMerchantID()
    {
        return $this->merchantID;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function getVariables()
    {
        return $this;
    }

}