<?php
use UserAgentParserComparison\Html\Index;

/*
 * Generate a detail page for each user agent
 */
include_once 'bootstrap.php';

$generate = new Index();
$generate->setTitle('UserAgentParser comparison');

/*
 * persist!
 */
file_put_contents($basePath . '/../index.html', $generate->getHtml());
