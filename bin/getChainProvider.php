<?php
use UserAgentParser\Provider;
use BrowscapPHP\Cache\BrowscapCache;
use Doctrine\Common\Cache;
use WurflCache\Adapter\File;

/*
 * Browscap
 */
$cache = new File([
    File::DIR => 'vendor/browscap/browscap-php/resources'
]);

$browscapParser = new \BrowscapPHP\Browscap();
$browscapParser->setCache($cache);

$browscapProvider = new Provider\BrowscapPhp($browscapParser);

/*
 * Piwik
*/
$cache = new Cache\PhpFileCache('.tmp/piwik');

$piwikParser = new \DeviceDetector\DeviceDetector();
$piwikParser->setCache($cache);

$piwikProvider = new Provider\PiwikDeviceDetector();
$piwikProvider->setParser($piwikParser);

/*
 * Wurfl
*/
$resourcesDir = '.tmp/wurfl';

$persistenceDir = $resourcesDir . '/storage/persistence';
$cacheDir       = $resourcesDir . '/storage/cache';

// Create WURFL Configuration
$wurflConfig = new \Wurfl\Configuration\InMemoryConfig();
$wurflConfig->wurflFile('.tmp/wurfl.xml');
$wurflConfig->matchMode(\Wurfl\Configuration\Config::MATCH_MODE_ACCURACY);
$wurflConfig->persistence('file', [
    \Wurfl\Configuration\Config::DIR => $persistenceDir,
]);

// Setup Caching
$wurflConfig->cache('file', [
    \Wurfl\Configuration\Config::DIR        => $cacheDir,
    \Wurfl\Configuration\Config::EXPIRATION => 36000,
]);

// Create the cache instance from the configuration
$cacheStorage = \Wurfl\Storage\Factory::create($wurflConfig->cache);

// Create the persistent cache instance from the configuration
$persistenceStorage = \Wurfl\Storage\Factory::create($wurflConfig->persistence);

// Create a WURFL Manager from the WURFL Configuration
$wurflManager = new \Wurfl\Manager($wurflConfig, $persistenceStorage, $cacheStorage);

$wurflProvider = new Provider\Wurfl($wurflManager);

$chain = new Provider\Chain([
    $browscapProvider,
    new Provider\DonatjUAParser(),
    $piwikProvider,
    new Provider\SinergiBrowserDetector(),
    new Provider\UAParser(),
    new Provider\WhichBrowser(),
    new Provider\Woothee(),
    $wurflProvider,
    new Provider\YzalisUAParser()
]);

return $chain;
