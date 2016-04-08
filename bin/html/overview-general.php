<?php
use UserAgentParserComparison\Html\OverviewGeneral;

/*
 * Generate a general overview
 */
include_once 'bootstrap.php';

$generate = new OverviewGeneral($entityManager);
$generate->setTitle('UserAgentParser comparison overview');
/*
 * persist!
 */

$folder = $basePath;
if (! file_exists($folder)) {
 mkdir($folder, 0777, true);
}

file_put_contents($folder . '/index.html', $generate->getHtml());
