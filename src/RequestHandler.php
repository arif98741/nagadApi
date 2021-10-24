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
use NagadApi\Exception\ExceptionHandler;

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
     * @since v1.6.0
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
     * @since v1.6.0
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


        try {
            $publicSignature = $this->helper->EncryptDataWithPublicKey(json_encode($sensitiveData));
        } catch (Exception $e) {
            // return $this->showResponse($e->getMessage(), $sensitiveData, []);
            throw new ExceptionHandler($e->getMessage());

        }

        try {
            $signature = $this->helper->SignatureGenerate(json_encode($sensitiveData));
        } catch (Exception $e) {
            throw new ExceptionHandler($e->getMessage());

        }

        $postData = array(
            'accountNumber' => $this->base->keyObject->getAppAccount(), //optional
            'dateTime' => Date('YmdHis'),
            'sensitiveData' => $publicSignature,
            'signature' => $signature
        );

        $resultData = $this->helper->HttpPostMethod($postUrl, $postData);
        $this->initUrl = $postUrl;


        if (is_array($resultData) && array_key_exists('reason', $resultData)) {

            throw new ExceptionHandler($resultData['reason'] . ', ' . $resultData['message']);

        } else if (is_array($resultData) && array_key_exists('error', $resultData)) {

            $this->showResponse($resultData, $sensitiveData, $postData);
            return $this->response;
        }


        //check existence of sensitiveData and signature
        if (array_key_exists('sensitiveData', $resultData) && array_key_exists('signature', $resultData)) {

            if (!empty($resultData['sensitiveData']) && !empty($resultData['signature'])) {
                $plainResponse = json_decode($this->helper->DecryptDataWithPrivateKey($resultData['sensitiveData']), true);
                if (isset($plainResponse['paymentReferenceId']) && isset($plainResponse['challenge'])) {

                    $paymentReferenceId = $plainResponse['paymentReferenceId'];
                    $challenge = $plainResponse['challenge'];

                    $sensitiveDataOrder = array(
                        'merchantId' => $this->base->getMerchantID(),
                        'orderId' => $this->base->getInvoice(),
                        'currencyCode' => $this->base->keyObject->getCurrencyCode(),
                        'amount' => $this->base->getAmount(),
                        'challenge' => $challenge
                    );

                    $postDataOrder = array(
                        'sensitiveData' => $this->helper->EncryptDataWithPublicKey(json_encode($sensitiveDataOrder)),
                        'signature' => $this->helper->SignatureGenerate(json_encode($sensitiveDataOrder)),
                        'merchantCallbackURL' => $this->base->merchantCallback,

                    );
                    $OrderSubmitUrl = $this->base->getBaseUrl() . "api/dfs/check-out/complete/"
                        . $paymentReferenceId;

                    $resultDataOrder = $this->helper->HttpPostMethod($OrderSubmitUrl, $postDataOrder);

                    if (array_key_exists('status', $resultDataOrder)) {

                        if ($resultDataOrder['status'] == "Success") {
                            $url = json_encode($resultDataOrder['callBackUrl']);
                            echo "<script>window.open($url, '_self')</script>";
                            exit;
                        } else {
                            echo json_encode($resultDataOrder);
                        }

                    } else {
                        return $resultDataOrder;
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
     * @return void
     * @since v1.8.4.4
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