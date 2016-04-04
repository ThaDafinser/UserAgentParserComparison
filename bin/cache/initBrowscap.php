<?php
use BrowscapPHP\Browscap;
use BrowscapPHP\Helper\IniLoader;
use WurflCache\Adapter\File;

/*
 * Browscap cache init
 */
include 'bootstrap.php';

/*
 * Full
 */
echo '.';

$cache = new File([
    File::DIR => '../myCache/.tmp/browscap/full'
]);

$bc = new Browscap();
$bc->setCache($cache);
$bc->convertFile('data/full_php_browscap.ini');


/*
 * Lite
 */
echo '.';
$cache = new File([
    File::DIR => '../myCache/.tmp/browscap/lite'
]);

$bc = new Browscap();
$bc->setCache($cache);
$bc->convertFile('data/lite_php_browscap.ini');

/*
 * PHP
 */
echo '.';
$cache = new File([
    File::DIR => '../myCache/.tmp/browscap/php'
]);

$bc = new Browscap();
$bc->setCache($cache);
$bc->convertFile('data/php_browscap.ini');
