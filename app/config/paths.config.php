<?php

!defined('ROOT_DIR')    ? define('ROOT_DIR',    $_SERVER['DOCUMENT_ROOT'])          : null;
!defined('APP_DIR')     ? define('APP_DIR',     ROOT_DIR . '/app')                  : null;
!defined('DATA_DIR')    ? define('DATA_DIR',    ROOT_DIR . '/data')                 : null;
!defined('VIEWS_DIR')   ? define('VIEWS_DIR',   ROOT_DIR . '/resources/views')      : null;
!defined('TEMP_DIR')    ? define('TEMP_DIR',    ROOT_DIR . '/__temp')               : null;
!defined('LOGS_DIR')    ? define('LOGS_DIR',    ROOT_DIR . '/__logs')               : null;

!defined('CONFIGS_DIR') ? define('CONFIGS_DIR', APP_DIR  . '/config')               : null;
!defined('TEMP_VIEWS')  ? define('TEMP_VIEWS',  APP_DIR  . '/views')                : null;
!defined('ROUTES_DIR')  ? define('ROUTES_DIR',  APP_DIR  . '/routes')               : null;

!defined('CONTENT_DIR') ? define('CONTENT_DIR', ROOT_DIR . getenv('CONTENT_DIR'))   : null;
