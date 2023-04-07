<p><img alt="Nagad api php xenon nagad api" style="width: 400px; height: 300px;" src="https://github.com/arif98741/nagadApi/blob/master/file/nagad-logo.png"></p>

# Xenon/NagadApi


## Features

- Easy Installation
- Easy Payment Processing
- Composer based installation
- Verify Payment
- Demo Invoice Code Generate

# Install Using Composer

```bash
composer require xenon/nagad-api
```



# Example Code
```php
<?php

use Xenon\NagadApi\Helper;
use Xenon\NagadApi\Base;

require 'vendor/autoload.php';

/**
 * ==============================================================================
 * all configuration are used here for demo purpose.
 * for use in dev mode use 'development'
 * for use in production mode use 'production'
 * ===============================================================================
 **/
$config = [
    'NAGAD_APP_ENV' => 'development', // development|production
    'NAGAD_APP_LOG' => '1',
    'NAGAD_APP_ACCOUNT' => '016XXXXXXXX', //demo
    'NAGAD_APP_MERCHANTID' => '6800000025', //demo
    'NAGAD_APP_MERCHANT_PRIVATE_KEY' => 'MIIEvFAAxN1qfKiRiCL720FtQfIwPDp9ZqbG2OQbdyZUB8I08irKJ0x/psM4SjXasglHBK5G1DX7BmwcB/PRbC0cHYy3pXDmLI8pZl1NehLzbav0Y4fP4MdnpQnfzZJdpaGVE0oI15l',
    'NAGAD_APP_MERCHANT_PG_PUBLIC_KEY' => 'MIIBIjANBc54jjMJoP2toR9fGmQV7y9fzj',
    'NAGAD_APP_TIMEZONE' => 'Asia/Dhaka',
];

$nagad = new Base($config, [
    'amount' => 10,
    'invoice' => Helper::generateFakeInvoice(15, true),
    'merchantCallback' => 'https://example.com/payment/success/id=4',
]);
```

## Method-1 **:** Use for website
```
$status = $nagad->payNow($nagad); //will redirect to payment page
```

## Method-2 **:** Return Redirection url . You can use this according to need 
```
$paymentUrl = $nagad->payNowWithoutRedirection($nagad); //will return payment url like below. You can use that url and do whatever u want to get payment from clients. 
```


```
http://sandbox.mynagad.com:10060/check-out/MDYyODAwNTcyNTYxNi42ODMwMDIwMDcxMDQyMjUuOU5PTEFVNkVaWkdUWVRBLmJiZGMyNTE3MTVmZTNiNjIzN2Zk
```

### After that use below method for extracting payment response that will return an array

```
$successUrl = 'https://example.com/payment/success/id=4/?merchant=683XXXX225&order_id=CKH060JXXXXXFRA2&payment_ref_id=MXXXXXXXXtIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=&status=Success&status_code=00_0000_000&message=Successful%20Transaction&payment_dt=20211123235008&issuer_payment_ref=MTEyMzIzNDg1NzUwOS42ODMwMDIwMDcxMDQyMjUuQ0tIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=';
$response = Helper::successResponse("$successUrl");

Array
(
    [merchant] => 683XXXX225
    [order_id] => CKH060JXXXXXFRA2
    [payment_ref_id] => MXXXXXXXXtIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=
    [status] => Success
    [status_code] => 00_0000_000
    [message] => Successful Transaction
    [payment_dt] => 20211123235008
    [issuer_payment_ref] => MTEyMzIzNDg1NzUwOXXXXXtIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=
)
```
### For payment verification use below method. You will then get below json as response. 
```
$helper = new Helper($config);
$response = $helper->verifyPayment($response['payment_ref_id']);
```

## Payment Verification Response
```
{
	merchantId: "683XXXX225",
	orderId: "CKH060JXXXXXFRA2",
	paymentRefId: "MXXXXXXXXtIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=",
	amount: "16",
	clientMobileNo: "016****5428",
	merchantMobileNo: "01XXXXXXX10",
	orderDateTime: "2021-11-23 23:48:22.0",
	issuerPaymentDateTime: "2021-11-23 23:50:08.0",
	issuerPaymentRefNo: "000XXXW",
	additionalMerchantInfo: null,
	status: "Success",
	statusCode: "000",
	cancelIssuerDateTime: null,
	cancelIssuerRefNo: null
}
```
## Maintainer
<ul>
    <li><a href="https://github.com/arif98741">Ariful Islam</a></li>
</ul>


### Contributors
<ul>
    <li><a href="https://github.com/tusharkhan">Tushar Khan</a></li>
</ul>



# Important Information:

### Sandbox Environment
1. Need sandbox details for sandbox testing. Check your email that you have got from nagad authority
2. Use sandbox details such as pgpublickey, privatekey, merchant-id for sandbox testing
3. You need to register a mobile number for sandbox testing. Contact with your account manager for doing this
4. You should test environment before going to live


### Live Environment
1. Need production details for production final. You will get through email
2. Your server ip/domain and callback url should be whitelisted before running in production. You can contact with nagad team using mail or other system

Login to your **Nagad Merchant Panel**

`   https://auth.mynagad.com:10900/authentication-service-provider-1.0/login
`

**Step 1:**
**_In the Merchant Portal, Go to Merchant Integration Details under Merchant Management Menu.
You will get the Merchant ID which is your Merchant ID for Integration._**

Then, Click on “Key Generate” and 
Download the Merchant Private Key and Merchant Public Key.

**Step 2:**
_**_Go to Merchant Integration under Merchant Management Menu.
Put your Call Back URL and Upload the Merchant Public Key which you have downloaded in Step 1. Add and Submit!**__

**Step 3:**
_**_Usage Production Details .
You have to use public key and private key that you have downloaded!**__


3. If you have any question/query then email me **arif98741@gmail.com**
4. Do Code, Be Crazy
<br>
### If you find any kind of issue or bug you are highly encouraged to report. For reporting use [issues option](https://github.com/arif98741/nagadApi/issues)
For can also add pull request. For pull request you should use **dev** branch. Because our **master** branch is serving at this moment for usage around community. We dont want to messup


## License

[MIT](https://choosealicense.com/licenses/mit/)

