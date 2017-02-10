<?php

// setup the mime-types the app should filter
$fileExtensions = require('app/config.php');

// serve the requested resource as-is.
if (preg_match("/\.(?:".implode('|', $fileExtensions['CONTENT_FILE_EXTS']).")$/", preg_replace('/\?[\s\S]+/i', '', $_SERVER['REQUEST_URI']))) {
    return false;
}


// Set timezone
date_default_timezone_set("UTC");



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
define('VENDOR_DIR',    __DIR__.DS.'vendor');


require 'vendor/autoload.php';


use Gbox\JSON as JSON;


//
// LOAD ENVIRONMENT CONFIGS
//
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();



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
// DEFINE DB COLLECTION DATA
//
$FILES_COL  = $DB->files;
$REQS       = $DB->reqs;

//
// INIT TEMPLATE ENGINE
//
$TPL    = new Mustache_Engine;


//
// INIT APP ROUTER
//
$ROUTER = new \Bramus\Router\Router();


$MODULE_CONTAINER = [
    'CONFIG'    => $CONFIG
,   'DB'        => $DB
,   'FILES_COL' => $FILES_COL
,   'REQS'      => $REQS
,   'TPL'       => $TPL
,   'ROUTER'    => $ROUTER
];


// initialize route handler for media
$ROUTER->match('GET|HEAD', '/media/(.*)', function($filename) use ($MODULE_CONTAINER) {

    // get data components
    extract($MODULE_CONTAINER);


    // compose file glob path
    $fileExts   = join(',', $CONFIG['CONTENT_FILE_EXTS']);
    $filepath   = CONTENT_DIR.DS.$filename.".{{$fileExts}}";

    $filepath   = preg_replace('/\//', DS, $filepath);


    // glob for file in question
    $file = glob($filepath, GLOB_BRACE);


    // get resulting filepath
    $file = $file[0];

    // print_r($file);

    // print_r(mime_content_type($file));

    $ext = explode('.', $file);
    $ext = array_pop($ext);

    print_r('/content/' . $filename . '.' . $ext);

    header('Content-Type:' . mime_content_type($file));
    header("Content-Disposition: inline; filename=\"/content/{$filename}.{$ext}\"");
    header('Content-length: ' . filesize($file));
    header('X-Pad: avoid browser bug');
    header('X-Powered-By: ');

    if (!file_exists($file)) {
        header('location:/404');
        return;
    }


    // query the $FILES_COL collection for our file
    $filedata = $FILES_COL->findOne([
        'name' => $filename
    ]);


    if (!$filedata) {
        $filedata = [
            'name'  => $filename
        ,   'count' => 1
        ];

        $FILES_COL->insert($filedata);
    }


    // bump the file access count
    $filedata['count'] += 1;


    // update the file record with relavent stats
    $FILES_COL->update(['name' => $filename], $filedata);


    $reqData = [
        'client_ip'     => getClientIP()    // $client_ip
    ,   'filename'      => $filename
    ,   'req_time'      => time()           // $req_time
    ,   'remote_port'   => $_SERVER['REMOTE_PORT']
    ,   'user_agent'    => $_SERVER['HTTP_USER_AGENT']
    ,   'client_device' => getClientDeviceInfo($_SERVER['HTTP_USER_AGENT'])
    ];


    // insert new request record into database
    $REQS->insert($reqData);


    $TEST_DATA = $REQS->findOne([
        'filename'  => $filename
    ]);


    echo file_get_contents($file);

    // if (isset($_ENV['DEBUG']) && $_ENV['DEBUG'] === true) {
    //     get_file($file);

    // } else {
    //     echo "DEBUG";

    // }

});


// // initialize home route handler
// $ROUTER->get('/', function() use ($MODULE_CONTAINER) {

//     // get data components
//     extract($MODULE_CONTAINER);

//     $template = get_template('feed');

//     $model = array_replace_recursive(model(), [
//         'page' => [
//             'title' => 'Waffles!!'
//         ]
//     ,   'domain'    => 'http://127.0.0.1:3005'
//     ]);

//     echo $TPL->render($template, $model);
// });


// app router 404 handler
$ROUTER->set404(function() {
    header('HTTP/1.1 404 Not Found');
    warn('404...');
    echo "404...";
});


//
// DISPATCH APPLICATION
//
$ROUTER->run();
