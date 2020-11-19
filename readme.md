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
echo '<pre>';
print_r($response); //see response as array
echo '</pre>';

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



