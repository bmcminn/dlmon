<?php


$router->get('/content/(.*)', function($filepath) use ($model, $db) {

    $ipAddress = getIpAddress();

    // $db->query("SELECT * FROM files WHERE alias='${filepath}'");

    echo $filepath;

});
