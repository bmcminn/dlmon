<?php

// setup the mime-types the app should filter
$fileExtensions = require('app/config.php');

// serve the requested resource as-is.
if (preg_match("/\.(?:".implode('|', $fileExtensions['CONTENT_FILE_EXTS']).")$/", preg_replace('/\?[\s\S]+/i', '', $_SERVER['REQUEST_URI']))) {
    return false;
}


// Set timezone
date_default_timezone_set("UTC");


require 'vendor/autoload.php';


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
define('EOL',           PHP_EOL);

define('ROOT_DIR',      __DIR__);
define('LOGS_DIR',      __DIR__.DS.'logs');
define('DATA_DIR',      __DIR__.DS.'data');
define('CONTENT_DIR',   __DIR__.DS.'content');
define('VIEWS_DIR',     __DIR__.DS.'views');



//
// DEFINE BASE MODEL
//
model('page', [
    'title'     => isset($_ENV['APP_TITLE']) ? $_ENV['APP_TITLE'] : 'Title Here'
]);


//
//
//
$CONFIG = require('./app/config.php');


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


$DATA = [
    'CONFIG'    => $CONFIG
,   'DB'        => $DB
,   'FILES'     => $FILES
,   'TPL'       => $TPL
,   'ROUTER'    => $ROUTER
];



// initialize route handler for media
$ROUTER->match('GET|HEAD', '/media/(.*)', function($filename) use ($DATA) {

    // get data components
    extract($DATA);

    print_r($filename);

    // compose file glob path
    // $fileExts   = join(',', $CONFIG['CONTENT_FILE_EXTS']);
    // $filepath   = CONTENT_DIR.DS.$filename.".{{$fileExts}}";
    $filepath   = CONTENT_DIR.DS.$filename;

    $filepath   = preg_replace('/\//', DS, $filepath);

    print_r($filepath);

    // glob for file in question
    $file = glob($filepath);


    // return;

    // // if file does not exist bug out and 404
    // if (!count($file) > 0) {
    //     echo "file doesn't exist... need to redirect to 404";
    //     warn('missing file requested:', $filepath);
    //     header("HTTP/1.0 404 Not Found");
    //     header("Location: /404");
    //     return;
    // }

    // get resulting filepath
    $file = $file[0];


    // query the $FILES collection for our file
    $filedata = $FILES->findOne([
        'name' => $filename
    ]);


    if ($filedata) {
        print_r($filedata);
    } else {

        $filedata = [
            'name'  => $filename
        ,   'count' => 1
        ];

        $FILES->insert($filedata);
    }


    // bump the file access count
    $filedata['count'] += 1;


    // update the file record with relavent stats
    $FILES->update(['name' => $filename], $filedata);


    if (isset($_ENV['DEBUG']) && $_ENV['DEBUG'] === true) {
        get_file($file);

    } else {
        echo "DEBUG";

    }

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
    warn('404...');
    // ... do something special here
});


//
// DISPATCH APPLICATION
//
$ROUTER->run();
