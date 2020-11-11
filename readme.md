For using nagad-api in your application just follow below code.
1. Copy all your data from .env.example to .env and change necessary details. <br>
    NAGAD_APP_ENV                     = development  // dev: development, live: production <br>
    NAGAD_APP_LOG                     = 1 //it will generate error log<br>
    NAGAD_APP_ACCOUNT                 = //get from nagad. it is usually mobile no<br>
    NAGAD_APP_MERCHANTID              = //get from nagad<br>
    NAGAD_APP_MERCHANT_PRIVATE_KEY    = //get from nagad<br>
    NAGAD_APP_MERCHANT_PG_PUBLIC_KEY  = //get from nagad<br>
    NAGAD_APP_TIMEZONE                = Asia/Dhaka

<pre>

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
print_r($response);
echo '</pre>';
exit;
</pre>
