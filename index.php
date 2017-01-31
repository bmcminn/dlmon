<?php

require "vendor/autoload.php";


use Gbox\JSON as JSON;


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
define('LOGS_DIR',      __DIR__.'/logs');
define('DATA_DIR',      __DIR__.'/data');
define('CONTENT_DIR',   __DIR__.'/content');
define('VIEWS_DIR',     __DIR__.'/views');



//
// DEFINE BASE MODEL
//
model('page', [
    'title'     => isset($_ENV['APP_TITLE']) ? $_ENV['APP_TITLE'] : 'Title Here'
]);


//
// INIT DB INSTANCE
//
$CLIENT = new MongoLite\Client(DATA_DIR);
$DB     = $CLIENT->files;


//
// GET FILES COLLECTION DATA
//
$FILES  = $DB->files;


//
// INIT TEMPLATE ENGINE
//
$TPL    = new Mustache_Engine;


//
// INIT APP ROUTER
//
$ROUTER = new \Bramus\Router\Router();


// initialize route handler for media
$ROUTER->get('/media/(.*)', function($filename) use ($FILES, $TPL) {

    info($filename);

    if (!file_exists(CONTENT_DIR.DS.$filename)) {
        debug('file doesn\'t exist');
        return;
    }


    // query the $FILES collection for our file
    $file = $FILES->findOne([
            'name' => $filename
        ]);
    info(JSON::stringify($file));

    if ($file->count()) {
        info(JSON::stringify($file));

    } else {
        debug("no file found, creating record");

    }


    // $template = get_template('index');

    // $model = array_replace_recursive(model(), [
    //     'page' => [
    //         'title' => 'Waffles!!'
    //     ]
    // ]);

    // echo $TPL->render($template, $model);

});


// initialize home route handler
$ROUTER->get('/', function() use ($TPL) {
    $template = get_template('index');

    $model = array_replace_recursive(model(), [
        'page' => [
            'title' => 'Waffles!!'
        ]
    ]);

    echo $TPL->render($template, $model);
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
