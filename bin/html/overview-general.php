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
file_put_contents($basePath . '/index.html', $generate->getHtml());
