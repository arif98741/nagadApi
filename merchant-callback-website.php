<?php
/**
 * *****************************************************************
 * Copyright 2019.
 * All Rights Reserved to
 * Nagad
 * Redistribution or Using any part of source code or binary
 * can not be done without permission of Nagad
 * *****************************************************************
 *
 * @author - Md Nazmul Hasan Nazim
 * @email - nazmul.nazim@nagad.com.bd
 * @date: 18/11/2019
 * @time: 03:20 PM
 * ****************************************************************
 */

date_default_timezone_set('Asia/Dhaka');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Merchant Call Back Page</title>
</head>

<?php

function HttpGet($url)
{
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $file_contents = curl_exec($ch);
    echo curl_error($ch);
    curl_close($ch);
    return $file_contents;
}

$Query_String = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1]);
$payment_ref_id = substr($Query_String[2], 15);
$url = "http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/verify/payment/" . $payment_ref_id;
$json = HttpGet($url);
$arr = json_decode($json, true);
// echo json_encode($arr[0]);

?>


<body>

<div class="table-responsive-sm">
    <table class="table table-bordered">

        <tr>
            <h3>Your Transaction is <?php echo $arr['status']; ?> </h3>
        </tr>
        <tr><b>Details are given below: </b></tr>
        <tr>
            <td width=100>merchantId</td>
            <td><?php echo $arr['merchantId']; ?></td>
        </tr>

        <tr>
            <td width=100>orderId</td>
            <td><?php echo $arr['orderId']; ?></td>
        </tr>

        <tr>
            <td width=100>paymentRefId</td>
            <td><?php echo $arr['paymentRefId']; ?></td>
        </tr>

        <tr>
            <td width=100>amount</td>
            <td><?php echo $arr['amount']; ?></td>
        </tr>

        <tr>
            <td width=100>clientMobileNo</td>
            <td><?php echo $arr['clientMobileNo']; ?></td>
        </tr>

        <tr>
            <td width=100>merchantMobileNo</td>
            <td><?php echo $arr['merchantMobileNo']; ?></td>
        </tr>

        <tr>
            <td width=100>orderDateTime</td>
            <td><?php echo $arr['orderDateTime']; ?></td>
        </tr>

        <tr>
            <td width=100>issuerPaymentDateTime</td>
            <td><?php echo $arr['issuerPaymentDateTime']; ?></td>
        </tr>

        <tr>
            <td width=100>issuerPaymentRefNo</td>
            <td><?php echo $arr['issuerPaymentRefNo']; ?></td>
        </tr>

        <tr>
            <td width=100>additionalMerchantInfo</td>
            <td><?php echo $arr['additionalMerchantInfo']; ?></td>
        </tr>

        <tr>
            <td width=100>status</td>
            <td><?php echo $arr['status']; ?></td>
        </tr>

        <tr>
            <td width=100>statusCode</td>
            <td><?php echo $arr['statusCode']; ?></td>
        </tr>

    </table>
</div>


</body>

</html>