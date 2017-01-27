<?php

require "vendor/autoload.php";


//
// LOAD ENVIRONMENT CONFIGS
//
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


//
// DEFINE APP CONSTANTS
//
define('DS',            DIRECTORY_SEPARATOR);
define('ROOT_DIR',      __DIR__);
define('DATA_DIR',      __DIR__.'/data');
define('CONTENT_DIR',   __DIR__.'/content');


//
// INIT DB INSTANCE
//
$CLIENT = new MongoLite\Client(DATA_DIR);
$DB     = $CLIENT->files;


//
// GET FILES COLLECTION DATA
//
$FILES  = $DB->files;


// $entry = [
//     "path"  => CONTENT_DIR.DS.'filename.mp3'
// ,   "count" => 0
// ];

// $FILES->insert($entry);
$data = $FILES->findOne(["count" => 0]);

print_r($data);


//
// INIT APP ROUTER
//
$ROUTER = new \Bramus\Router\Router();


// initialize route handler for content
$ROUTER->get('/content/(\w+)', function($path) {
    echo $path;
});




// initialize home route handler
$ROUTER->get('/', function() {
    echo "";
});

// app router 404 handler
$ROUTER->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo "404...";
    // ... do something special here
});


//
// DISPATCH APPLICATION
//
$ROUTER->run();
