<?php
/*
 * Browscap cache init
 */
include 'bootstrap.php';

/*
 * Full
 */
echo '.';

$memoryCache = new \Doctrine\Common\Cache\FilesystemCache('../myCache/.tmp/browscap/full');
$cache = new \Roave\DoctrineSimpleCache\SimpleCacheAdapter($memoryCache);
$logger = new \Psr\Log\NullLogger();

$bc = new \BrowscapPHP\BrowscapUpdater($cache, $logger);
$bc->convertFile('data/full_php_browscap.ini');


/*
 * Lite
 */
echo '.';

$memoryCache = new \Doctrine\Common\Cache\FilesystemCache('../myCache/.tmp/browscap/lite');
$cache = new \Roave\DoctrineSimpleCache\SimpleCacheAdapter($memoryCache);
$logger = new \Psr\Log\NullLogger();

$bc = new \BrowscapPHP\BrowscapUpdater($cache, $logger);
$bc->convertFile('data/lite_php_browscap.ini');

/*
 * PHP
 */
echo '.';

$memoryCache = new \Doctrine\Common\Cache\FilesystemCache('../myCache/.tmp/browscap/standard');
$cache = new \Roave\DoctrineSimpleCache\SimpleCacheAdapter($memoryCache);
$logger = new \Psr\Log\NullLogger();

$bc = new \BrowscapPHP\BrowscapUpdater($cache, $logger);
$bc->convertFile('data/php_browscap.ini');

echo PHP_EOL;
