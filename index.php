<?php

require "vendor/autoload.php";

// load environment config
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


// $DB = SQLite3:open($_ENV['DB_NAME']);

class DB extends SQLite3 {
    function __construct($dbFilePath) {
        $this->open($dbFilePath);
    }
}


$DB = new DB($_ENV['DB_NAME']);
if (!$DB) {
    echo "db connection failed";
} else {
    echo "db connection success!";
}


$ROUTER = new \Bramus\Router\Router();


$ROUTER->get('/', function() {
    echo "patnserjskle";
});


$ROUTER->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo "404...";
    // ... do something special here
});


$ROUTER->run();
