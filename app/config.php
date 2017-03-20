<?php

$config = [
    'server' => [
        'timezone'  => isset($_ENV['SERVER_TIMEZONE']) ? $_ENV['SERVER_TIMEZONE'] : 'UTC'
    ]

,   'db' => [
        'host'      => isset($_ENV['SERVER_TIMEZONE']) ? $_ENV['DB_HOST'] : 'localhost'
    ,   'user'      => isset($_ENV['SERVER_TIMEZONE']) ? $_ENV['DB_USER'] : 'user'
    ,   'pass'      => isset($_ENV['SERVER_TIMEZONE']) ? $_ENV['DB_PASS'] : 'password'
    ,   'dbname'    => isset($_ENV['SERVER_TIMEZONE']) ? $_ENV['DB_NAME'] : 'dbname'
    ]

,   'CONTENT_FILE_EXTS' => [ 'jpeg', 'jpg', 'png', 'gif', 'mp*', 'avi' ]
];

return $config;
