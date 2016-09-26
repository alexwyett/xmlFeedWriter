<?php

// Include composer
require_once 'vendor/autoload.php';
require 'config.php';
require 'common.php';
require 'XmlTemplateInterface.php';
require 'XmlTemplate.php';
require 'XmlCollection.php';
require 'PropertyXml.php';

$brandcode = getBrandcode();

if ($brandcode 
    && array_key_exists($brandcode, $brands) 
    && in_array($brandcode, $feed['brands'])
) {
    $brand = $brands[$brandcode];
} else {
    do404('Brand not recognised');
}

// Memory usage
$start = memory_get_usage(true);

// Connect to the api
\tabs\api\client\ApiClient::factory(
    sprintf('http://%s.api.carltonsoftware.co.uk', strtolower($brandcode)),
    APIKEY,
    APISECRET
);

$apiInfo = \tabs\api\utility\Utility::getApiInformation();