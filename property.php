<?php

// Include dependancies and connect to the api
require_once 'connect.php';

$snsFullMessage = json_decode(detectRequestBody());
if ($snsFullMessage && property_exists($snsFullMessage, 'SubscribeURL')) {
    echo file_get_contents($snsFullMessage->SubscribeURL);
    die();
} else {
    if ($snsFullMessage->updatename == 'PropertyUpdate') {
        list($propref, $brand) = explode($snsFullMessage->updatedid);

        $property = tabs\api\property\Property::getProperty($propref, $brandcode);
        if ($property) {
            $fileName = 'cache/' . $property->getId();
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            header("HTTP/1.1 200 OK");
            die();
        }
    } else if ($snsFullMessage->updatename == 'PropertyDelete') {
        $fileName = 'cache/' . $snsFullMessage->updatedid;
        if (file_exists($fileName)) {
            unlink($fileName);
            header("HTTP/1.1 200 OK");
            die();
        }
    }
}

do404();