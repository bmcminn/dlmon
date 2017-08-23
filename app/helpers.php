<?php

use Webmozart\PathUtil\Path;


require 'config/paths.config.php';

// Glob all the route files
$routesGlob = Path::join(APP_DIR, '/helpers/**');

foreach (glob($routesGlob) as $filepath) {
    require($filepath);
}
