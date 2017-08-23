<?php

// Init the user session
session_start();

// Load app system paths config
require __DIR__ . '/config/paths.config.php';

// Load vendor libraries
require ROOT_DIR . '/vendor/autoload.php';

// Load env config
require APP_DIR . '/env.php';

// Determine if we're currently in production
define('IS_PRODUCTION', getenv('production'));

// Capture and filter the request URI for later processing
define('REQUEST_URI', preg_replace('/\?*+/', '', filter_var($_SERVER['REQUEST_URI'], FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE)));

// Define routes for system
require CONFIGS_DIR . '/routes.config.php';

// Load views library
require APP_DIR . '/twig.php';

// Load helper functions
require APP_DIR . '/helpers.php';

// Define base model instance
$model = [
    'app'           => require(CONFIGS_DIR . '/app.config.php')
,   'routes'        => ROUTES
,   'current_route' => $_SERVER['REQUEST_URI']
,   'user'          => isset($_SESSION['user']) ? $_SESSION['user'] : null
];

// setup DB instance
$db = require APP_DIR . '/db.php';

// Load our startup script
require APP_DIR . '/startup.php';

// Load route handlers
require APP_DIR . '/routes.php';
