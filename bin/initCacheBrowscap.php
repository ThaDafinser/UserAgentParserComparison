<?php
use BrowscapPHP\Browscap;
use BrowscapPHP\Helper\IniLoader;
use WurflCache\Adapter\File;

/*
 * Browscap cache init
 */
include 'bootstrap.php';

$cache = new File([
    File::DIR => '.tmp/browscap'
]);

$bc = new Browscap();
$bc->setCache($cache);
$bc->convertFile('data/full_php_browscap.ini');
