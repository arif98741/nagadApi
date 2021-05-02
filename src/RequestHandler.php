<?php
/*
 *
 *  * -------------------------------------------------------------
 *  * Copyright (c) 2020
 *  * -created by Ariful Islam
 *  * -All Rights Preserved By Ariful Islam
 *  * -If you have any query then knock me at
 *  * arif98741@gmail.com
 *  * See my profile @https://github.com/arif98741
 *  * ----------------------------------------------------------------
 *
 */

namespace NagadApi;

use Exception;

/**
 * This is the main performer that means request handler for entire nagadApi
 * This class is doing extra-ordinary job according to request type.
 * It also generates api response for viewing in monitor screen.
 * Class RequestHandler
 * @package NagadApi
 */
class RequestHandler
{
    public $response;

    /**
     * @var
     */
    private $apiUrl = 'api/dfs/check-out/initialize/';

    /**
     * @var
     */
    private $initUrl;

    /**
     * @var
     */
    private $helper;

    /**
     * for using
     * @var
     */
    private $base;

    /**
     * RequestHandler constructor.
     * @param Base $base
     */
    public function __construct(Base $base)
    {
        $this->base = $base;
        $this->helper = new Helper($this->base->keyObject);
    }

    /**
     * Fire request to nagad api
     * @return array
     * @throws Exception
     */
    public function sendRequest()
    {
        $postUrl = $this->base->getBaseUrl() . $this->apiUrl
            . $this->base->getMerchantID() .
            "/" . $this->base->getInvoice();

        $sensitiveData = array(
            'merchantId' => $this->base->keyObject->getAppMerchantID(),
            'datetime' => Date('YmdHis'),
            'orderId' => $this->base->getInvoice(),
            'challenge' => Helper::generateRandomString(40, 'you', 'me')
        );

        $postData = array(
            'accountNumber' => $this->base->keyObject->getAppAccount(), //optional
            'dateTime' => Date('YmdHis'),
            'sensitiveData' => $this->helper->EncryptDataWithPublicKey(json_encode($sensitiveData)),
            'signature' => $this->helper->SignatureGenerate(json_encode($sensitiveData))
        );

        $resultData = $this->helper->HttpPostMethod($postUrl, $postData);
        $this->initUrl = $postUrl;

        if (is_array($resultData) && array_key_exists('reason', $resultData)) {
            $this->showResponse($resultData, $sensitiveData, $postData);
            return $this->response;
        } else if (is_array($resultData) && array_key_exists('error', $resultData)) {
            $this->showResponse($resultData, $sensitiveData, $postData);
            return $this->response;
        }

        //check existence of sensitiveData and signature
        if (array_key_exists('sensitiveData', $resultData) && array_key_exists('signature', $resultData)) {

            if (!empty($resultData['sensitiveData']) && !empty($resultData['signature'])) {
                $PlainResponse = json_decode($this->helper->DecryptDataWithPrivateKey($resultData['sensitiveData']), true);
                if (isset($PlainResponse['paymentReferenceId']) && isset($PlainResponse['challenge'])) {

                    $paymentReferenceId = $PlainResponse['paymentReferenceId'];
                    $challenge = $PlainResponse['challenge'];

                    $SensitiveDataOrder = array(
                        'merchantId' => $this->base->getMerchantID(),
                        'orderId' => $this->base->getInvoice(),
                        'currencyCode' => $this->base->keyObject->getCurrencyCode(),
                        'amount' => $this->base->getAmount(),
                        'challenge' => $challenge
                    );

                    $PostDataOrder = array(
                        'sensitiveData' => $this->helper->EncryptDataWithPublicKey(json_encode($SensitiveDataOrder)),
                        'signature' => $this->helper->SignatureGenerate(json_encode($SensitiveDataOrder)),
                        'merchantCallbackURL' => $this->base->merchantCallback,

                    );
                    $OrderSubmitUrl = $this->base->getBaseUrl() . "api/dfs/check-out/complete/"
                        . $paymentReferenceId;

                    $Result_Data_Order = $this->helper->HttpPostMethod($OrderSubmitUrl, $PostDataOrder);

                    if (array_key_exists('status', $Result_Data_Order)) {

                        if ($Result_Data_Order['status'] == "Success") {
                            $url = json_encode($Result_Data_Order['callBackUrl']);
                            echo "<script>window.open($url, '_self')</script>";
                            exit;
                        } else {
                            echo json_encode($Result_Data_Order);
                        }

                    } else {
                        return $Result_Data_Order;
                    }

                }
            }
        } else {
            $this->showResponse($resultData['message'], [], []);
        }

    }

    /**
     * @param $resultData
     * @param $sensitiveData
     * @param $postData
     * @return array
     */
    private function showResponse($resultData, $sensitiveData, $postData)
    {
        $this->response = [
            'status' => 'error',
            'response' => $resultData,
            'request' => [
                'environment' => $this->base->environment,
                'time' => [
                    'request time' => date('Y-m-d H:i:s'),
                    'timezone' => $this->base->getTimezone()
                ],
                'url' => [
                    'base_url' => $this->base->getBaseUrl(),
                    'api_url' => $this->apiUrl,
                    'request_url' => $this->base->getBaseUrl() . $this->apiUrl,
                ],
                'data' => [
                    'sensitiveData' => $sensitiveData,
                    'postData' => $postData
                ],

            ],
            'server' => Helper::serverDetails()
        ];
    }

}