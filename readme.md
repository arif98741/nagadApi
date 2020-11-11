<p align="center" ><img src="https://raw.githubusercontent.com/code4mk/lara-nagad/master/nagad%20payment.png"></p>

# NagadApi

# Installation Process

```bash
composer require arif98741/nagad-api
```

# Stage 1


# env setup
copy all necessary data and replace using nagad provided data

```bash

NAGAD_APP_ENV                     =development
NAGAD_APP_LOG                     =1
NAGAD_APP_ACCOUNT                 =01754545457
NAGAD_APP_MERCHANTID              =683002007104225
NAGAD_APP_MERCHANT_PRIVATE_KEY    =
NAGAD_APP_MERCHANT_PG_PUBLIC_KEY  =
NAGAD_APP_TIMEZONE                =Asia/Dhaka
```

# Usage

```php
use NagadApi\Base;
use NagadApi\Helper;
use NagadApi\RequestHandler;

require 'vendor/autoload.php';

$nagad = new Base([
    'amount' => 100,
    'invoice' => Helper::generateFakeInvoice(15, true),
    'merchantCallback' => 'https://phpdark.com/payment/success/id=4',
]);
$request = new RequestHandler($nagad);
$response = $request->fire();
echo '<pre>';
print_r($response); //see response as array
echo '</pre>';
exit;


```

# Information:
1. Need sandbox details for sandbox testing
2. Need production details for production final
3. Need testing before going to live
4. If you have any question/query then email me @ arif98741@gmail.com


