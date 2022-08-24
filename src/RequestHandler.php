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

use Carbon\Carbon;
use Exception;
use Xenon\NagadApi\Exception\ExceptionHandler;

/**
 * This is the main performer that means request handler for entire nagadApi
 * This class is doing extraordinary job according to request type.
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
    private $apiUrl = 'check-out/initialize/';

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
     * @return mixed
     * @throws Exception
     * @since v1.6.0
     */
    public function sendRequest(bool $redirection = true)
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

        if (!is_array($resultData)) {
            throw new ExceptionHandler("Failed to generate nagad payment url as it is returning null response. Please be confirm you have whitelisted your server ip or server fix other server related issue");
        }

        if (array_key_exists('error', $resultData)) {
            throw new ExceptionHandler($resultData['error']);
        }

        if (array_key_exists('reason', $resultData)) {

            throw new ExceptionHandler($resultData['reason'] . ', ' . $resultData['message']);

        }


        //check existence of sensitiveData and signature
        if (array_key_exists('sensitiveData', $resultData) && array_key_exists('signature', $resultData)) {

            if (!empty($resultData['sensitiveData']) && !empty($resultData['signature'])) {
                $plainResponse = json_decode($this->helper->DecryptDataWithPrivateKey($resultData['sensitiveData']), true);
                if (isset($plainResponse['paymentReferenceId'], $plainResponse['challenge'])) {

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
                    $OrderSubmitUrl = $this->base->getBaseUrl() . "check-out/complete/"
                        . $paymentReferenceId;

                    $resultDataOrder = $this->helper->HttpPostMethod($OrderSubmitUrl, $postDataOrder);

                    if (array_key_exists('status', $resultDataOrder)) {

                        if ($resultDataOrder['status'] == "Success" && $redirection) {
                            $url = json_encode($resultDataOrder['callBackUrl']);
                            echo "<script>window.open($url, '_self')</script>";
                            exit;
                        }

                        if ($resultDataOrder['status'] == "Success" && !$redirection) {

                            return $resultDataOrder['callBackUrl'];
                        }

                        echo json_encode($resultDataOrder);

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
                    'request time' => Carbon::now(),
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