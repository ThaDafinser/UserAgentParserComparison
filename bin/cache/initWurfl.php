<?php
/*
 * Wurfl
 */
include 'bootstrap.php';

use WurflCache\Adapter\File;

/*
 * File
 */
$resourcesDir = '../myCache/.tmp/wurfl';

$persistenceDir = $resourcesDir . '/storage/persistence';
$cacheDir = $resourcesDir . '/storage/cache';

// Create WURFL Configuration
$wurflConfig = new \Wurfl\Configuration\InMemoryConfig();
$wurflConfig->wurflFile('data/wurfl.xml');
$wurflConfig->matchMode(\Wurfl\Configuration\Config::MATCH_MODE_ACCURACY);

// save convertion
$wurflConfig->persistence('file', [
    \Wurfl\Configuration\Config::DIR => $persistenceDir
]);

// Setup Caching
$wurflConfig->cache('file', [
    \Wurfl\Configuration\Config::DIR => $cacheDir
]);

// Create the cache instance from the configuration
$cacheStorage = \Wurfl\Storage\Factory::create($wurflConfig->cache);

// Create the persistent cache instance from the configuration
$persistenceStorage = \Wurfl\Storage\Factory::create($wurflConfig->persistence);

// Create a WURFL Manager from the WURFL Configuration
$wurflManager = new \Wurfl\Manager($wurflConfig, $persistenceStorage, $cacheStorage);
$wurflManager->reload();
