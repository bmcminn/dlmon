<?php

use Webmozart\PathUtil\Path;

// get .env file path
$envPath = Path::join(ROOT_DIR . '/.env');

// load .env config
$Loader = new josegonzalez\Dotenv\Loader($envPath);

// Parse the .env file
$Loader->parse();

// Send the parsed .env file to the $_ENV variable
$Loader->toEnv();
