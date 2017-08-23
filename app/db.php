<?php

!defined('DB_NAME') ? define('DB_NAME', getenv('DB_NAME') || '__db.sqlite') : null;

!defined('DB_PATH') ? define('DB_PATH', DATA_DIR . '/' . DB_NAME) : null;


try {
    $db = new PDO('sqlite:'.DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;

} catch(PDOException $e) {
    return $e->getMessage();

}
