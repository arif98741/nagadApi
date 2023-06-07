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

namespace Xenon\NagadApi;


use Exception;
use Xenon\NagadApi\Exception\NagadPaymentException;
use Xenon\NagadApi\lib\Key;

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
    private $base_url = 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/';
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
     * @param $config
     * @param $params
     * @throws NagadPaymentException
     * @since v1.3.2
     */
    public function __construct($config, $params)
    {
        $this->checkParams($config, $params);
        $this->keyObject = new Key($config);
        $this->amount = $params['amount'];
        $this->invoice = $params['invoice'];
        $this->merchantID = $this->keyObject->getAppMerchantID();
        $this->merchantCallback = $params['merchantCallback'];
        $this->setTimeZone($this->keyObject->getTimeZone());
        date_default_timezone_set($this->timezone);

        /**
         * Before activating production environment be confirm that your system is ok and out of bug
         * it is highly recommended to Test your environment using development environment
         * your ip,domain and callback_url should be whitelisted in Nagad end
         */
        if ($this->keyObject->getAppEnv() == 'production') {
            $this->base_url = 'https://api.mynagad.com/api/dfs/';
            $this->environment = $this->keyObject->getAppEnv();
        }

    }

    /**
     * Final Send Request to Nagad
     * @param Base $base
     * @return array
     * @throws Exception
     * @since v1.6.0
     */
    public function payNow(Base $base)
    {
        return (new RequestHandler($base))->sendRequest();
    }

    /**
     * Final Send Request to Nagad and Get Redirection Url
     * @param Base $base
     * @return string
     * @throws Exception
     * @since v1.6.0
     */
    public function payNowWithoutRedirection(Base $base)
    {
        return (new RequestHandler($base))->sendRequest(false);
    }

    /**
     * @return string
     * @since v1.3.1
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @param $timeZone
     * @since v1.3.1
     */
    public function setTimeZone($timeZone)
    {
        if (!empty($timeZone)) {
            $this->timezone = $timeZone;
        } else {
            $this->timezone = 'Asia/Dhaka';
        }

    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return mixed
     * @since v1.3.1
     */
    public function getMerchantID()
    {
        return $this->merchantID;
    }

    /**
     * @return string
     * @since v1.3.1
     */
    public function getBaseUrl(): string
    {
        return $this->base_url;
    }

    /**
     * @throws NagadPaymentException
     */
    private function checkParams($config, $params)
    {
        if (!is_array($config)) {
            throw new NagadPaymentException("Configuration should be array.");
        }

        if (!is_array($params)) {
            throw new NagadPaymentException("Params should be array.");
        }

        if (!array_key_exists('amount', $params)) {
            throw new NagadPaymentException("Array key amount missing. Check configuration array format from github repository's readme.md file");
        }
        if (!array_key_exists('invoice', $params)) {
            throw new NagadPaymentException("Array key invoice missing. Check configuration array format from github repository's readme.md file");
        }
        if (!array_key_exists('merchantCallback', $params)) {
            throw new NagadPaymentException("Array key merchantCallback missing. Check configuration array format from github repository's readme.md file");
        }
    }

}