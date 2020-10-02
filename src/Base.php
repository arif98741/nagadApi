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
    private $timezone = 'Asia/Dhaka';
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
     * CallBack Url for merchant
     */
    public $merchantCallback;


    /**
     * Base constructor
     * @param array $data
     * @param string $environment
     */
    public function __construct(array $data, $environment = 'development')
    {

        $this->amount = $data['amount'];
        $this->invoice = $data['invoice'];
        $this->merchantID = $data['merchantID'];
        $this->merchantCallback = $data['merchantCallback'];
        $this->setTimeZone($data);
        /**
         * Before activating production environment be confirm that your system is ok and out of bug
         * it is highly recommended to test your environment using development environment
         */
        if ($environment == 'production') {
            $this->base_url = 'https://payment.mynagad.com:30000/';
            $this->environment = $environment;
        }

    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @param $data
     */
    public function setTimeZone($data)
    {
        if (array_key_exists('time_zone', $data)) {
            date_default_timezone_set($data['time_zone']);
        } else {
            date_default_timezone_set($this->timezone);
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
    public function getBaseUrl(): string
    {
        return $this->base_url;
    }


}