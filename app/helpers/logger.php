<?php

/**
 * Appends a log entry to a daily log file
 * @param  [string, obj, array, int, double] $data  Data or message to be written to the log file
 * @param  string $level Delimits what
 * @return null
 */
function logger($data, $level="info") {

    // if production, ignore non-error logging
    if (getenv('PRODUCTION')) {
        if ($level !== 'error'
        ||  $level !== 'fail'
        ) {
            return;
        }
    }

    // ensure LOGS_DIR exists
    if (!file_exists(LOGS_DIR)) {
        mkdir(LOGS_DIR);
    }

    // get the filepath of our daily logs file
    $filepath = Path::join(LOGS_DIR, "/${date('Y-m-d')}.log");

    // capture the stack trace so we know what file called this function
    $db = debug_backtrace();

    // define our log record
    $data = implode(' | ', [
        "[${level}]"
    ,   date('Y-m-d H:s:m Z')
    ,   $db[0]['file'] . ':' . $db[0]['line']
    ,   print_r($data, true)
    ]) . "\n";

    // append the log entry to our log file
    file_put_contents($filepath, $data, FILE_APPEND);
}
