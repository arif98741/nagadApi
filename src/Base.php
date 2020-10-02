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
     * @var Helper
     */
    private $helperObject;

    /**
     * CallBack Url for merchant
     */
    private $merchantCallback;


    /**
     * Base constructor
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->invoice = $data['invoice'];
        $this->merchantID = $data['merchantID'];
        $this->merchanCallback = $data['merchantCallback'];
        if (array_key_exists('time_zone', $data)) {
            date_default_timezone_set($data['time_zone']);
        } else {
            date_default_timezone_set($this->timezone);
        }
        $this->helperObject = new Helper();
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
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

    /**
     * @param string $base_url
     * @return Base
     */
    public function setBaseUrl(string $base_url): Base
    {
        $this->base_url = $base_url;
        return $this;
    }

}