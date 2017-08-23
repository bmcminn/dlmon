<?php


$router->get('/content/(.*)', function($filepath) use ($model, $db) {

    $ipAddress = getIpAddress();



    echo $filepath;


});
