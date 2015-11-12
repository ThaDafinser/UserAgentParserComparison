<?php
include_once 'bootstrap.php';

/*
 * per cli parameter
 */
if (! isset($filename)) {
    if (! isset($argv[1])) {
        throw new \Exception('parameter missing, usage: php generateMatrix.php myFile.php');
    }
    
    $filename = $argv[1];
    if (! file_exists($filename)) {
        throw new \Exception('file does not exists: ' . $filename);
    }
}

if (! isset($userAgents)) {
    $userAgents = include $filename;
    if (! is_array($userAgents)) {
        throw new \Exception('the data file does not return an array!');
    }
}

if (isset($useFilename)) {
    $reportName = $useFilename;
} else {
    $reportName = basename($filename);
}
$reportName = str_replace([
    '.json',
    '.php'
], '', $reportName);

/*
 * include different datasets!
 */

/*
 * do always the same - generate a matrix
 */

use UserAgentParser\Provider;
use UserAgentParserMatrix\Analyze;
use UserAgentParserMatrix\GenerateSummary;
use BrowscapPHP\Cache\BrowscapCache;
use Doctrine\Common\Cache;
use WurflCache\Adapter\File;
use UserAgentParserMatrix\GenerateNotFound;

/*
 * Providers
 */
$cache = new File([
    File::DIR => '.tmp/browscap_full'
]);

$browscapFull = new Provider\BrowscapPhp();
$browscapFull->setCache($cache);

$cache = new Cache\PhpFileCache('.tmp/piwik');
$piwik = new Provider\PiwikDeviceDetector();
$piwik->setCache($cache);

$chain = new Provider\Chain([
    $browscapFull,
    new Provider\DonatjUAParser(),
    $piwik,
    new Provider\UAParser(),
    new Provider\WhichBrowser(),
    new Provider\Woothee(),
    new Provider\YzalisUAParser()
]);

$analyze = new Analyze();
$analyze->setUserAgents($userAgents);
$analyze->setChainProvider($chain);
$analyze->execute();

/*
 * create folder
 */
$folder = 'results/' . $reportName;

/*
 * Summary
 */
$table = new GenerateSummary();
$table->setAnalyze($analyze);
$table->setFolder($folder);
$table->persist();


/*
 * Not found
 */
$table = new GenerateNotFound();
$table->setAnalyze($analyze);
$table->setFolder($folder);
$table->persist();

