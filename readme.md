<p align="center" ><img style="width: 400px; height: 300px;" src="https://github.com/arif98741/nagadApi/blob/master/file/nagad-logo.png"></p>

# NagadApi

# Installation Process

```bash
composer require arif98741/nagad-api
```
# .env setup
copy all necessary data to your <strong>.env</strong> file and replace using provided 
credentials by Nagad. Below given data are dummy and those have
no meaning at all. 

```bash

NAGAD_APP_ENV                     =development
NAGAD_APP_LOG                     =1
NAGAD_APP_ACCOUNT                 =01754545457
NAGAD_APP_MERCHANTID              =683002007104225
NAGAD_APP_MERCHANT_PRIVATE_KEY    =
NAGAD_APP_MERCHANT_PG_PUBLIC_KEY  =
NAGAD_APP_TIMEZONE                =Asia/Dhaka
```

# Example Code

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
//you will be redirected to your marchantCallbackUrl after successful payment process



//get help from below method for extracting response data from url.  Put your response string to below method successReponse() aftermaking object of Helper class

##example
$helper = new Helper();
$response = 'https://phpdark.com/payment/success/id=4/?merchant=683002007104225&order_id=EBSXGJ5OYQCRO7D&payment_ref_id=MTEyOTAwMjY1NDMxNi42ODMwMDIwMDcxMDQyMjUuRUJTWEdKNU9ZUUNSTzdELmExODVkYWE4MDAyMDEyM2ZlYzRl&status=Success&status_code=00_0000_000&message=Successful%20Transaction&payment_dt=20201129002747&issuer_payment_ref=MTEyOTAwMjY1NDMxNi42ODMwMDIwMDcxMDQyMjUuRUJTWEdKNU9ZUUNSTzdELmExODVkYWE4MDAyMDEyM2ZlYzRl';
$responseArray = $helper->successResponse($response);
Array
(
    [merchant] => 6878544664000
    [order_id] => EBSXGJ5GHTDRCRO7D
    [payment_ref_id] => MTEyOTAwMjY1NDMxNi42ODGJYSLKJYYYFGFMwMDIwMDcxMDQyMjUuRUJTWEdKNU9ZUUNSTzdELmExODVkYWE4MDAyMDEyM2ZlYzRl
    [status] => Success
    [status_code] => 00_0000_000
    [message] => Successful Transaction
    [payment_dt] => 20201129002747
    [issuer_payment_ref] => MTEyOTAwMjY1NDMxNi42ODMwMDIwMDcxMDQ874HDGFHGLewhfyMjUuRUJTWEdKNU9ZUUNSTzdELmExODVkYWE4MDAyMDEyM2ZlYzRl
)
```
#####Maintainer
<ul>
    <li><a href="https://github.com/arif98741">Ariful Islam</a></li>
</ul>


#####Contributors
<ul>
    <li><a href="https://github.com/tusharkhan">Tushar Khan</a></li>
</ul>



# Information:
1. Need sandbox details for sandbox testing
2. Need production details for production final
3. Need testing before going to live
4. If you have any question/query then email me arif98741@gmail.com
5. Do Code, Be Crazy
<br>
##//If you find any kind of issues or bug you are highly encuraged to report. For reporting use issues.
For can also push pull request. For pull request you should use dev branch. Because our master branch is serving at this moment for usage.



