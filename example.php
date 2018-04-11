<?php


require_once 'vendor/autoload.php';


$api = new SD\Root\RootInsurance('https://sandbox.root.co.za', 'your_api_key', true);

echo $api->generateGadgetQuote('iPhone 6s 64GB LTE');
