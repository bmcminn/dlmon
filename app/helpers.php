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


/**
 * [mime_content_type description]
 * @sauce: http://nl3.php.net/manual/en/function.mime-content-type.php#87856
 * @param  [type] $filename [description]
 * @return [type]           [description]
 */
if(!function_exists('mime_content_type')) {
    function mime_content_type($filename) {

        $mime_types = array(
            'txt'   => 'text/plain'
        ,   'htm'   => 'text/html'
        ,   'html'  => 'text/html'
        ,   'php'   => 'text/html'
        ,   'css'   => 'text/css'
        ,   'js'    => 'application/javascript'
        ,   'json'  => 'application/json'
        ,   'xml'   => 'application/xml'
        ,   'swf'   => 'application/x-shockwave-flash'
        ,   'flv'   => 'video/x-flv'

        // images
        ,   'png'   => 'image/png'
        ,   'jpe'   => 'image/jpeg'
        ,   'jpeg'  => 'image/jpeg'
        ,   'jpg'   => 'image/jpeg'
        ,   'gif'   => 'image/gif'
        ,   'bmp'   => 'image/bmp'
        ,   'ico'   => 'image/vnd.microsoft.icon'
        ,   'tiff'  => 'image/tiff'
        ,   'tif'   => 'image/tiff'
        ,   'svg'   => 'image/svg+xml'
        ,   'svgz'  => 'image/svg+xml'

        // archives
        ,   'zip'   => 'application/zip'
        ,   'rar'   => 'application/x-rar-compressed'
        ,   'exe'   => 'application/x-msdownload'
        ,   'msi'   => 'application/x-msdownload'
        ,   'cab'   => 'application/vnd.ms-cab-compressed'

        // audio/video
        ,   'mp3'   => 'audio/mpeg'
        ,   'qt'    => 'video/quicktime'
        ,   'mov'   => 'video/quicktime'

        // adobe
        ,   'pdf'   => 'application/pdf'
        ,   'psd'   => 'image/vnd.adobe.photoshop'
        ,   'ai'    => 'application/postscript'
        ,   'eps'   => 'application/postscript'
        ,   'ps'    => 'application/postscript'

            // ms office
        ,   'doc'   => 'application/msword'
        ,   'rtf'   => 'application/rtf'
        ,   'xls'   => 'application/vnd.ms-excel'
        ,   'ppt'   => 'application/vnd.ms-powerpoint'

            // open office
        ,   'odt'   => 'application/vnd.oasis.opendocument.text'
        ,   'ods'   => 'application/vnd.oasis.opendocument.spreadsheet'
        );

        $arr    = explode('.',$filename);
        $str    = array_pop($arr);
        $ext    = strtolower($str);

        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];

        } elseif (function_exists('finfo_open')) {
            $finfo      = finfo_open(FILEINFO_MIME);
            $mimetype   = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;

        } else {
            return 'application/octet-stream';

        }
    }
}


/**
 * [getClientIP description]
 * @return [type] [description]
 */
function getClientIP() {

    // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //     $ip = $_SERVER['HTTP_CLIENT_IP'];
    // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // } else {
    //     $ip = $_SERVER['REMOTE_ADDR'];
    // }

    $ip = $_SERVER['REMOTE_ADDR'];

    return $ip;
}


// ---------------------------------------------------------------------------

/**
 * [getClientDeviceInfo description]
 * @param  [type] $detect [description]
 * @return [type]         [description]
 */
function getClientDeviceInfo($userAgent) {

    // NOTE: this is fecking garbage @serbanghita, make this easier to load via composers autoloader...
    // $detect = new Detection\MobileDetect;
    require_once(VENDOR_DIR.DS.'mobiledetect'.DS.'mobiledetectlib'.DS.'Mobile_Detect.php');

    $detect = new Mobile_Detect;

    $detect->setUserAgent($userAgent);

    $data = [
        'device' => [
            'isMobile'          => $detect->isMobile()
        ,   'isTablet'          => $detect->isTablet()
        ,   'isiOS'             => $detect->isiOS()
        ,   'isAndroidOS'       => $detect->isAndroidOS()
        ,   'isBlackBerryOS'    => $detect->isBlackBerryOS()
        ,   'isWindowsMobileOS' => $detect->isWindowsMobileOS()
        ,   'isWindowsPhoneOS'  => $detect->isWindowsPhoneOS()
        ]

        // DESKTOP BROWSERS
    ,   'browser' => [
            'isChrome'          => $detect->isChrome()
        ,   'isFirefox'         => $detect->isFirefox()
        ,   'isOpera'           => $detect->isOpera()
        ,   'isEdge'            => $detect->isEdge()
        ,   'isIE'              => $detect->isIE()
        ,   'isSafari'          => $detect->isSafari()
        ]
    ];


    // $data['isOtherBrowser'] =
    //         $data['isChrome'] ? false : true
    //     ||  $data['isFirefox'] ? false : true
    //     ||  $data['isOpera'] ? false : true
    //     ||  $data['isEdge'] ? false : true
    //     ||  $data['isIE'] ? false : true
    //     ||  $data['isSafari'] ? false : true
    //     ||  true
    // ;

    return $data;

}
