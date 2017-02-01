<?php

//
// DEFINE HELPER FUNCTIONS
//

/**
 * [model description]
 * @param  boolean $key [description]
 * @param  boolean $val [description]
 * @return [type]       [description]
 */
function &model($key=false, $val=false) {
    static $data = [];

    // if our args are empty
    if (!$key) {
        return $data;
    }

    // if we're setting both the key and its value
    if ($key && $val) {
        $data[$key] = $val;
    }

    return $data[$key];
}


/**
 * [get_template description]
 * @param  [type] $tplname [description]
 * @return [type]          [description]
 */
function get_template($tplname) {
    $filepath   = glob(VIEWS_DIR.DS.$tplname.'.*');
    $tpl        = '';

    if (count($filepath) > 0) {
        $tpl = file_get_contents($filepath[0]);
    }

    return $tpl;
}


/**
 * Logs debug info to a log file
 * @param  [type] $message [description]
 * @return [type]          [description]
 */
function _logger($message, $type='info') {

    // make the logs directory if not already created
    if (!is_dir(LOGS_DIR)) {
        mkdir(LOGS_DIR);
    }

    $message = join(' ', $message);

    $logfile = LOGS_DIR.DS.'debug-'.date('Y-m-d').'.log';

    $date = date('H:m:s');
    $ts = microtime();
    $fs = fopen($logfile, 'a');

    fwrite($fs, "[{$type}]\t[{$date}]\t[{$ts}]\t- {$message}\n");

    fclose($fs);

    return $message;
}

function info($message)  { _logger(func_get_args(), 'INFO'); }
function error($message) { _logger(func_get_args(), 'ERROR'); }
function debug($message) { _logger(func_get_args(), 'DEBUG'); }
function warn($message)  { _logger(func_get_args(), 'WARNING'); }



function get_file($filepath) {
    debug('filepath for media:', $filepath);
    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        error('missing file', $filepath);
    }
}
