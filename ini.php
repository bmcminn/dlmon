<?php

// set generic overrides
$filename = Date('Y-m-d').'-debug.log';

ini_set('error_log', LOGS_DIR.DS.$filename);


// enable debug ENV configs
if (isset($_ENV['DEBUG'])) {
    ini_set('display_errors', 1);

} else {

}
