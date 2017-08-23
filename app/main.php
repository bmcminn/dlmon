<?php

session_start();



define('ROOT_DIR',      __DIR__  . "/");
define('CONTENT_DIR',   ROOT_DIR . getenv('CONTENT_DIR'));
define('APP_DIR',       ROOT_DIR . "/app");
define('TEMP_VIEWS',    APP_DIR  . "/views");
define('VIEWS_DIR',     ROOT_DIR . "/resources/views");
define('TEMP_DIR',      ROOT_DIR . "/__temp");
define('LOGS_DIR',      ROOT_DIR . "/__logs");


$isProd = getenv('production') || false;

define('IS_PRODUCTION', $isProd);

require ROOT_DIR . '/vendor/autoload.php';










require ROOT_DIR . "/app/routes.php";
