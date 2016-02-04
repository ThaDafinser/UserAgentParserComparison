<?php
use BrowscapPHP\Browscap;
use BrowscapPHP\Helper\IniLoader;
use WurflCache\Adapter\File;

/*
 * Browscap cache init
 */
include 'bootstrap.php';

/*
 * File
 */
$cache = new File([
    File::DIR => '../myCache/.tmp/browscap'
]);

$bc = new Browscap();
$bc->setCache($cache);
$bc->convertFile('data/full_php_browscap.ini');

// /*
//  * APC
//  */
// $cache = new \WurflCache\Adapter\Apc([
//     'namespace' => 'browscap-php'
// ]);

// $bc = new Browscap();
// $bc->setCache($cache);
// $bc->convertFile('data/full_php_browscap.ini');
