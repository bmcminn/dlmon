<?php


function hashPassword($password, $opts = []) {
    $options = array_replace_recursive([
        'cost' => 12
    ], $opts);

    return password_hash($password, PASSWORD_BCRYPT, $options);
}
