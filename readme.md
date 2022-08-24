<p align="center" ><img style="width: 400px; height: 300px;" src="https://github.com/arif98741/nagadApi/blob/master/file/nagad-logo.png"></p>

# NagadApi

# Installation Process

```bash
composer require xenon/nagad-api
```

# Example Code

```php

<?php

use Xenon\NagadApi\Helper;
use Xenon\NagadApi\Base;

require 'vendor/autoload.php';

//all configuration are used here for demo purpose.
//for use in dev mode use 'development'
//for use in production mode use 'production'
$config = [
    'NAGAD_APP_ENV' => 'development', //development|production
    'NAGAD_APP_LOG' => '1',
    'NAGAD_APP_ACCOUNT' => '016XXXXXXXX', //demo
    'NAGAD_APP_MERCHANTID' => '6800000025', //demo
    'NAGAD_APP_MERCHANT_PRIVATE_KEY' => 'MIIEvFAAxN1qfKiRiCL720FtQfIwPDp9ZqbG2OQbdyZUB8I08irKJ0x/psM4SjXasglHBK5G1DX7BmwcB/PRbC0cHYy3pXDmLI8pZl1NehLzbav0Y4fP4MdnpQnfzZJdpaGVE0oI15lq+KZ0tbllNcS+/4MSwW+afvOw9bazAgMBAAECggEAIkenUsw3GKam9BqWh9I1p0Xmbeo+kYftznqai1pK4McVWW9//+wOJsU4edTR5KXK1KVOQKzDpnf/CU9SchYGPd9YScI3n/HR1HHZW2wHqM6O7na0hYA0UhDXLqhjDWuM3WEOOxdE67/bozbtujo4V4+PM8fjVaTsVDhQ60vfv9CnJJ7dLnhqcoovidOwZTHwG+pQtAwbX0ICgKSrc0elv8ZtfwlEvgIrtSiLAO1/CAf+uReUXyBCZhS4Xl7LroKZGiZ80/JE5mc67V/yImVKHBe0aZwgDHgtHh63/50/cAyuUfKyreAH0VLEwy54UCGramPQqYlIReMEbi6U4GC5AQKBgQDfDnHCH1rBvBWfkxPivl/yNKmENBkVikGWBwHNA3wVQ+xZ1Oqmjw3zuHY0xOH0GtK8l3Jy5dRL4DYlwB1qgd/Cxh0mmOv7/C3SviRk7W6FKqdpJLyaE/bqI9AmRCZBpX2PMje6Mm8QHp6+1QpPnN/SenOvoQg/WWYM1DNXUJsfMwKBgQCdtddE7A5IBvgZX2o9vTLZY/3KVuHgJm9dQNbfvtXw+IQfwssPqjrvoU6hPBWHbCZl6FCl2tRh/QfYR/N7H2PvRFfbbeWHw9+xwFP1pdgMug4cTAt4rkRJRLjEnZCNvSMVHrri+fAgpv296nOhwmY/qw5Smi9rMkRY6BoNCiEKgQKBgAaRnFQFLF0MNu7OHAXPaW/ukRdtmVeDDM9oQWtSMPNHXsx+crKY/+YvhnujWKwhphcbtqkfj5L0dWPDNpqOXJKV1wHt+vUexhKwus2mGF0flnKIPG2lLN5UU6rs0tuYDgyLhAyds5ub6zzfdUBG9Gh0ZrfDXETRUyoJjcGChC71AoGAfmSciL0SWQFU1qjUcXRvCzCK1h25WrYS7E6pppm/xia1ZOrtaLmKEEBbzvZjXqv7PhLoh3OQYJO0NM69QMCQi9JfAxnZKWx+m2tDHozyUIjQBDehve8UBRBRcCnDDwU015lQN9YNb23Fz+3VDB/LaF1D1kmBlUys3//r2OV0Q4ECgYBnpo6ZFmrHvV9IMIGjP7XIlVa1uiMCt41FVyINB9SJnamGGauW/pyENvEVh+ueuthSg37e/l0Xu0nm/XGqyKCqkAfBbL2Uj/j5FyDFrpF27PkANDo99CdqL5A4NQzZ69QRlCQ4wnNCq6GsYy2WEJyU2D+K8EBSQcwLsrI7QL7fvQ==',
    'NAGAD_APP_MERCHANT_PG_PUBLIC_KEY' => 'MIIBIjANBc54jjMJoP2toR9fGmQV7y9fzj6TIz9SFfsTQOugHkhyRzzhvZisiKzOAAWNX8RMpG+iqQi4p9W9VrmmiCfFDmLFnMrwhncnMsvlXB8QSJCq2irrx3HG0SJJCbS5+atz+E1iqO8QaPJ05snxv82Mf4NlZ4gZK0Pq/VvJ20lSkR+0nk+s/v3BgIyle78wjZP1vWLU4wIDAQAB',
    'NAGAD_APP_TIMEZONE' => 'Asia/Dhaka',
];

$nagad = new Base($config, [
    'amount' => 100,
    'invoice' => Helper::generateFakeInvoice(15, true),
    'merchantCallback' => 'https://example.com/payment/success/id=4',
]);
//way 1 - use for website
$status = $nagad->payNow($nagad); //will redirect to payment url of Nagad

//way 2 - useful for rest api or graphQL 
$paymentUrl = $nagad->payNowWithoutRedirection($nagad); //will return payment url like below. You can use that url and do whatever u want to get payment from clients. 
//
`http://sandbox.mynagad.com:10060/check-out/MDYyODAwNTcyNTYxNi42ODMwMDIwMDcxMDQyMjUuOU5PTEFVNkVaWkdUWVRBLmJiZGMyNTE3MTVmZTNiNjIzN2Zk`

//after that use below method for extracting payment response that will return an array
$response = Helper::successResponse('https://example.com/payment/success/id=4/?merchant=683XXXX225&order_id=CKH060JXXXXXFRA2&payment_ref_id=MXXXXXXXXtIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=&status=Success&status_code=00_0000_000&message=Successful%20Transaction&payment_dt=20211123235008&issuer_payment_ref=MTEyMzIzNDg1NzUwOS42ODMwMDIwMDcxMDQyMjUuQ0tIMDYwSjFRSlBRMUZSQTIuMTg0NTE2Yzc3ZmEzNmEwZTJlZjk=');
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


//For payment verification use below method. You will then get below json as response. 
$helper = new Helper($config);
$response = $helper->verifyPayment($response['payment_ref_id']);

## Payment verification Response
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
### Maintainer
<ul>
    <li><a href="https://github.com/arif98741">Ariful Islam</a></li>
</ul>


### Contributors
<ul>
    <li><a href="https://github.com/tusharkhan">Tushar Khan</a></li>
</ul>



# Information:

### Sandbox
1. Need sandbox details for sandbox testing. Check your email that you have got from nagad authority
2. Use sandbox details such as pgpublickey, privatekey, merchant-id for sandbox testing
3. You need to register a mobile number for sandbox testing. Contact with your account manager for doing this
4. You should test environment before going to live


### Live
1. Need production details for production final. You will get through email
2. Your server ip/domain should be whitelisted before running in production

Login to your nagad merchant panel

`   https://auth.mynagad.com:10900/authentication-service-provider-1.0/login
`

**Step 1:**
In the Merchant Portal, Go to Merchant Integration Details under Merchant Management Menu.
You will get the Merchant ID which is your Merchant ID for Integration.

Then, Click on “Key Generate” and 
Download the Merchant Private Key and Merchant Public Key.

**Step 2:**
Go to Merchant Integration under Merchant Management Menu.
Put your Call Back URL and Upload the Merchant Public Key which you have downloaded in Step 1. Add and Submit!

3. If you have any question/query then email me arif98741@gmail.com
4. Do Code, Be Crazy
<br>
### If you find any kind of issues or bug you are highly encouraged to report. For reporting use issues.
For can also push pull request. For pull request you should use dev branch. Because our master branch is serving at this moment for usage.



