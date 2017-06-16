<?php

session_start();

define('ROOT_DIR',      __DIR__ . "/..");

define('APP_DIR',       ROOT_DIR . "/app");

define('VIEWS_DIR',     ROOT_DIR . "/resources/views");

define('TEMP_DIR',      ROOT_DIR . "/__temp");
define('TEMP_VIEWS',    TEMP_DIR . "/views");


$isProd = isset($_ENV['production']) ? $_ENV['production'] : false;
define('IS_PRODUCTION', $isProd);

require ROOT_DIR . '/vendor/autoload.php';

require APP_DIR . '/helpers.php';



$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);



$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(VIEWS_DIR, [
        // 'cache'         => ROOT_DIR . '/_temp/views'
        'cache'   => false
        // 'cache' => IS_PRODUCTION ? TEMP_VIEWS : false
        // 'auto_reload'   => true
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    // $view->addExtension( new \Slim\Views\TwigExtension(
    //     // $container->router
    //     $container['router']
    // ,   $container->request->getUri()
    // ));

    return $view;
};



require ROOT_DIR . "/app/routes.php";


// // Create Slim app
// $app = new \Slim\App();

// // Fetch DI Container
// $container = $app->getContainer();

// // Register Twig View helper
// $container['view'] = function ($c) {
//     $view = new \Slim\Views\Twig('path/to/templates', [
//         'cache' => 'path/to/cache'
//     ]);

//     // Instantiate and add Slim specific extension
//     $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
//     $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

//     return $view;
// };


// // Run app
// $app->run();
