<?php

session_start();


define('ROOT_DIR',      __DIR__ . "/..");

define('VIEWS_DIR',     ROOT_DIR . "/resources/views");

define('TEMP_DIR',      ROOT_DIR . "/__temp");
define('TEMP_VIEWS',    TEMP_DIR . "/views");


$isProd = isset($_ENV['production']) ? $_ENV['production'] : false;
define('IS_PRODUCTION', $isProd);

require ROOT_DIR . '/vendor/autoload.php';




$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);



$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(VIEWS_DIR, [
        // 'cache' => IS_PRODUCTION ? TEMP_VIEWS : false
        'cache' => false
    ]);

    $view->addExtension( new \Slim\Views\TwigExtension(
        $container->router
    ,   $container->request->getUri()
    ));
};



require ROOT_DIR . "/app/routes.php";
