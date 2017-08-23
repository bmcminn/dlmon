<?php

!defined('DB_NAME') ? define('DB_NAME', $_ENV['DB_NAME']) : null;

!defined('DB_PATH') ? define('DB_PATH', DATA_DIR . '/' . DB_NAME) : null;

try {
    $db = new PDO('sqlite:'.DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;

} catch(PDOException $e) {
    return $e->getMessage();

}
