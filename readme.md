Example NagadApi Payment Request
<pre>

use NagadApi\Base;
use NagadApi\Helper;

require 'vendor/autoload.php';

$nagad = new Base([
    'amount' => 100,
    'invoice' => Helper::generateInvoiceTest('', 15, true),
    'merchantID' => '683002007104225',
    'merchantCallback' => 'https://phpdark.com/id=4',
    'time_zone' => 'Asia/Dhaka',
], 'development');


$Request = new \NagadApi\RequestHandler($nagad);
$response = $Request->fire('12345678901');
</pre>