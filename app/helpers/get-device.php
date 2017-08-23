<?php

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;


/**
 * Redirects the user to a given page; aliaas of the built-in header() function
 * @param  string $route Desired route to send the user to
 * @return null
 */
function getDevice($userAgent) {

    // OPTIONAL: Set version truncation to none, so full versions will be returned
    // By default only minor versions will be returned (e.g. X.Y)
    // for other options see VERSION_TRUNCATION_* constants in DeviceParserAbstract class
    DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);

    $dd = new DeviceDetector($userAgent);

    // OPTIONAL: Set caching method
    // By default static cache is used, which works best within one php process (memory array caching)
    // To cache across requests use caching in files or memcache
    $dd->setCache(new Doctrine\Common\Cache\PhpFileCache(DATA_DIR . '/tmp/'));

    // OPTIONAL: Set custom yaml parser
    // By default Spyc will be used for parsing yaml files. You can also use another yaml parser.
    // You may need to implement the Yaml Parser facade if you want to use another parser than Spyc or [Symfony](https://github.com/symfony/yaml)
    // $dd->setYamlParser(new DeviceDetector\Yaml\Symfony());

    // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
    $dd->discardBotInformation();


    $dd->parse();

    // handle bots,spiders,crawlers,...
    if ($dd->isBot()) {
        $data = [
            'isBot'     => true
        ,   'botInfo'   => $dd->getBot()
        ];

    // holds information about browser, feed reader, media player, ...
    } else {
        $data = [
            'client'    => $dd->getClient()
        ,   'osInfo'    => $dd->getOs()
        ,   'device'    => $dd->getDevice()
        ,   'brand'     => $dd->getBrandName()
        ,   'model'     => $dd->getModel()
        ];
    }

    return $data;
}



// function getDevice($detect) {

//     $detect->isMobile() ? return 'isMobile' : null;
//     $detect->isTablet() ? return 'isTablet' : null;

//     return 'desktop';

// }


// function getOS($detect) {
//     $detect->isiOS()                ? return 'iOS'             : null;
//     $detect->isAndroidOS()          ? return 'AndroidOS'       : null;
//     $detect->isBlackBerryOS()       ? return 'BlackBerryOS'    : null;
//     $detect->isWindowsMobileOS()    ? return 'WindowsMobileOS' : null;
//     $detect->isWindowsPhoneOS()     ? return 'WindowsPhoneOS'  : null;
//     return
// }
