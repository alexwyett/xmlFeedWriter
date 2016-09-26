<?php

/**
 * Return the brandcode from the url of the domain.
 * 
 * @return string
 */
function getBrandcode()
{
    $brandCode = 'zz';
    
    if (filter_input(INPUT_GET, 'brandcode') 
        && strlen(filter_input(INPUT_GET, 'brandcode')) == 2
    ) {
        $brandCode = filter_input(INPUT_GET, 'brandcode');
    }
    
    if (!empty($_SERVER) && isset($_SERVER['HTTP_HOST'])) {
        $urlComp = explode('.', $_SERVER['HTTP_HOST']);
        $brandCode = (strlen($urlComp[0]) == 2) ? $urlComp[0] : $brandCode;
    }
    
    return strtoupper($brandCode);
}

/**
 * Return the post body
 * 
 * @return string
 */
function detectRequestBody() {
    $rawInput = fopen('php://input', 'r');
    $tempStream = fopen('php://temp', 'r+');
    stream_copy_to_stream($rawInput, $tempStream);
    rewind($tempStream);

    return $tempStream;
}

/**
 * Output a 404 header
 * 
 * @return void
 */
function do404($message = 'Not Found')
{
    header("HTTP/1.1 404 File Not Found");
    die($message);
}

/**
 * Function to test is a value is less than 1 mile
 *
 * @param mixed $val Value to test
 *
 * @return string
 */
function lessThanOne($val)
{
    return boolToStr(($val < 1));
}

/**
 * Function to return the string representation of a boolean
 *
 * @param boolean $bool Bool to test
 *
 * @return string
 */
function boolToStr($bool)
{
    return ($bool) ? 'True' : 'False';
}