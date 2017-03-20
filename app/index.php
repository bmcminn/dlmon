<?php

//
// DEFINE GLOBAL CONSTANTS
//

define('DS',            DIRECTORY_SEPARATOR);
define('ROOT_DIR',      __DIR__.DS.'..');
define('LOGS_DIR',      ROOT_DIR.DS.'logs');
define('APP_DIR',       ROOT_DIR.DS.'app');
define('CONTENT_DIR',   ROOT_DIR.DS.'content');
define('DATA_DIR',      ROOT_DIR.DS.'data');
define('VIEWS_DIR',     ROOT_DIR.DS.'views');


require '../ini.php';
require '../vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//
// ENSURE REQUIRED DIRECTORIES EXIST
//

$req_dirs = [ LOGS_DIR, CONTENT_DIR, DATA_DIR ]
if (!is_dir(LOGS_DIR)) { mkdir(LOGS_DIR); }


//
// Load environment configs
//
$dotenv = new Dotenv\Dotenv(__DIR__.DS.'..');
$dotenv->load();


//
// LOAD
//

$config = require './config.php';

// Set timezone
date_default_timezone_set($config['server']['timezone']);


error_log(json_encode($config));

$app = new \Slim\App(['settings' => $config]);

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->run();
