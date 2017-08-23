<?php

// Define twig extension
!defined('VIEWS_EXT') ? define('VIEWS_EXT', $_ENV['VIEWS_EXT'] || '.twig') : null;

use Webmozart\PathUtil\Path;

require 'config/paths.config.php';

$loader = new Twig_Loader_Filesystem(VIEWS_DIR);
$twig = new Twig_Environment($loader, array(
    'cache'         => VIEWS_DIR . '/__cache'
,   'auto_reload'   => true
));



// ----------------------------------------------------------------------

$twig->addFilter(new Twig_SimpleFilter('asset', function($str) {
    return ROUTES['static'] . "/${str}";
}));


// ----------------------------------------------------------------------

// TODO: hookup parsedown library here

// init Parsedown instance
$Parsedown = new Parsedown();

$twig->addFilter(new Twig_SimpleFilter('md', function($str) use ($Parsedown) {
    return $parsedown->text($str);
}));


// ----------------------------------------------------------------------
$twig->addFilter(new Twig_SimpleFilter('embed', function($str) {
    $filepath = Path::join(__DIR__, '../static', $str);

    $content = $str;
    if (file_exists($filepath)) {
        $content = file_get_contents($filepath);
    }

    return $content;
}, ['is_safe' => ['html', 'js']]));


return $twig;
