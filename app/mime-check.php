<?php

// TODO: see about managing this with mimetype-config.php
// if (count(preg_match('/\.(?:css|js|html|php)$/', REQUEST_URI)) > 0) {

if (preg_match('/\.(?:css|js|html|php|twig|swig|ejs)/i', REQUEST_URI) && file_exists(__DIR__ . '/..' . REQUEST_URI)) {

    $ext = preg_match('/\.(.+)$/', REQUEST_URI, $matches);

    if (isset(MIME_TYPES[$matches[1]])) {
        $mimetype = MIME_TYPES[$matches[1]];

        header("content-type: ${mimetype}");
    }

    echo file_get_contents(__DIR__ . '/..' . REQUEST_URI);
    exit();
}
