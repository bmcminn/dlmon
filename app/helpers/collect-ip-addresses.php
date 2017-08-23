<?php

/**
 * Captures all possible IP addresses for the request origin
 * @sauce: http://itman.in/en/how-to-get-client-ip-address-in-php/
 * @return array Collection of the The "real" IP address of the request origin
 */
function getIpAddress() {
    return [
        'HTTP_CLIENT_IP'        => !empty($_SERVER['HTTP_CLIENT_IP'])       ? $_SERVER['HTTP_CLIENT_IP']        : null
    ,   'HTTP_X_FORWARDED_FOR'  => !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']  : null
    ,   'REMOTE_ADDR'           => !empty($_SERVER['REMOTE_ADDR'])          ? $_SERVER['REMOTE_ADDR']           : null
    ];
}
