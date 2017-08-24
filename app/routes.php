<?php

// Create a Router
// --------------------------------------------------

use Webmozart\PathUtil\Path;

$db     = require 'db.php';
$router = new \Bramus\Router\Router();



// Custom 404 Handler
$router->set404(function() use ($twig, $model) {
    header('HTTP/1.1 404 Not Found');
    echo $twig->render('404.twig', $model);
});



// Glob all the route files
$routesGlob = Path::join(ROUTES_DIR, '/**');

foreach (glob($routesGlob) as $filepath) {
    require($filepath);
}



// Run it!
// --------------------------------------------------

$router->run();
