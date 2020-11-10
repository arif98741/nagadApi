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

namespace NagadApi;


class RequestHandler
{
    public $response;

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
        $this->helper = new Helper();
    }

    /**
     * @param string $accountNumber
     * @return array
     */
    public function fire($accountNumber = '')
    {
        $PostURL = $this->base->getBaseUrl() . "remote-payment-gateway-1.0/api/dfs/check-out/initialize/"
            . $this->base->getMerchantID() .
            "/" . $this->base->getInvoice();
        $SensitiveData = array(
            'merchantId' => $this->base->getMerchantID(),
            'datetime' => Date('YmdHis'),
            'orderId' => $this->base->getInvoice(),
            'challenge' => Helper::generateRandomString(40)
        );

        $PostData = array(
            'accountNumber' => $accountNumber, //optional
            'dateTime' => Date('YmdHis'),
            'sensitiveData' => $this->helper->EncryptDataWithPublicKey(json_encode($SensitiveData)),
            'signature' => $this->helper->SignatureGenerate(json_encode($SensitiveData))
        );


        $Result_Data = $this->helper->HttpPostMethod($PostURL, $PostData);

        if ($Result_Data === NULL) {
            return $this->response = [
                'status' => 'error',
                'response' => [
                    'code' => 102,
                    'message' => 'NULL Response. Check your internet connection',
                ],
                'request' => [
                    'environment' => $this->base->environment,
                    'time' => [
                        'request time' => date('Y-m-d H:i:s'),
                        'timezone' => $this->base->getTimezone()
                    ],
                    'url' => $PostURL,
                    'SensitiveData' => $SensitiveData,
                    'PostData' => $PostData,
                ],
                'server' => Helper::serverDetails()
            ];
        }

        if (array_key_exists('sensitiveData', $Result_Data) && array_key_exists('signature', $Result_Data)) {

            if (!empty($Result_Data['sensitiveData']) && !empty($Result_Data['signature'])) {
                $PlainResponse = json_decode($this->helper->DecryptDataWithPrivateKey($Result_Data['sensitiveData']), true);
                if (isset($PlainResponse['paymentReferenceId']) && isset($PlainResponse['challenge'])) {

                    $paymentReferenceId = $PlainResponse['paymentReferenceId'];
                    $challenge = $PlainResponse['challenge'];

                    $SensitiveDataOrder = array(
                        'merchantId' => $this->base->getMerchantID(),
                        'orderId' => $this->base->getInvoice(),
                        'currencyCode' => '050',
                        'amount' => $this->base->getAmount(),
                        'challenge' => $challenge
                    );

                    $PostDataOrder = array(
                        'sensitiveData' => $this->helper->EncryptDataWithPublicKey(json_encode($SensitiveDataOrder)),
                        'signature' => $this->helper->SignatureGenerate(json_encode($SensitiveDataOrder)),
                        'merchantCallbackURL' => $this->base->merchantCallback,

                    );
                    $OrderSubmitUrl = $this->base->getBaseUrl() . "remote-payment-gateway-1.0/api/dfs/check-out/complete/"
                        . $paymentReferenceId;

                    $Result_Data_Order = $this->helper->HttpPostMethod($OrderSubmitUrl, $PostDataOrder);
                    if ($Result_Data_Order['status'] == "Success") {
                        $url = json_encode($Result_Data_Order['callBackUrl']);
                        echo "<script>window.open($url, '_self')</script>";
                    } else {
                        echo json_encode($Result_Data_Order);
                    }

                }
            }
        } else {
            return $this->response = [
                'status' => 'error',
                'response' => [
                    'code' => 101,
                    'message' => $Result_Data['message'],
                ],
                'request' => [
                    'environment' => $this->base->environment,
                    'time' => [
                        'request time' => date('Y-m-d H:i:s'),
                        'timezone' => $this->base->getTimezone()
                    ],
                    'url' => $PostURL,
                    'SensitiveData' => $SensitiveData,
                    'PostData' => $PostData,
                ],
                'server' => Helper::serverDetails()
            ];
        }

    }
}