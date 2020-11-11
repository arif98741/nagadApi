Example NagadApi Payment Request
Setting and attaching in your project you should run
<pre>
                composer require arif98741/nagad-api
</pre>

<pre>

<?php

use NagadApi\Base;
use NagadApi\Helper;
use NagadApi\RequestHandler;

require 'vendor/autoload.php';

$nagad = new Base([
    'amount' => 100,
    'invoice' => Helper::generateFakeInvoice('', 15, true),
    'merchantCallback' => 'https://phpdark.com/payment/success/id=4',
]);

$request = new RequestHandler($nagad);
$respnose = $request->fire();
echo '<pre>';
print_r($respnose);
echo '</pre>';
exit;
</pre>