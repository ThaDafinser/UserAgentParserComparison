<?php
include_once 'bootstrap.php';

$handleAll = opendir('data');
if ($handleAll === false) {
    throw new \Exception('folder could not get opened');
}

$generateFiles = [];

while ($filename = readdir($handleAll)) {
    if ($filename == '.' || $filename == '..' || strpos($filename, '.php') === false) {
        continue;
    }
    
    $useFilename = str_replace('.php', '', $filename);
    
    $generateFiles[] = $useFilename;
    
    $userAgents = include 'data/' . $filename;
    if (! is_array($userAgents)) {
        throw new \Exception('could not load array from: ' . $filename);
    }
    
    echo '~~~Now dataset: ' . $useFilename . '~~~' . PHP_EOL;
    
    // generate
    require 'generateMatrix.php';
}
closedir($handleAll);

$htmlPart = '<h1>UserAgentParserComparison index';
$htmlPart .= '<h2>All results listed (different testsuites)</h2>';

$htmlPart .= '<table class="table table-bordered">';

$htmlPart .= '<tr>';
$htmlPart .= '<th>Testsuite</th>';

foreach ($chain->getProviders() as $provider) {
    $htmlPart .= '<th>' . $provider->getName() . '</th>';
}
$htmlPart .= '</tr>';

foreach ($generateFiles as $file) {
    $htmlPart .= '<tr>';
    
    $htmlPart .= '<th>' . $file . '</th>';
    
    foreach ($chain->getProviders() as $provider) {
        $htmlPart .= '<td>';
        $htmlPart .= '<a href="results/' . $file . '/summary.html">Summary</a><br />';
        $htmlPart .= '<a href="results/' . $file . '/notFound_' . $provider->getName() . '.html">Not found</a>';
        $htmlPart .= '</td>';
    }
    
    $htmlPart .= '</tr>';
}
$htmlPart .= '</table>';

/*
 * Index page
 */
$html = <<<END
<html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />
    	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>

    <body>
        $htmlPart
    </body>
</html>
END;

file_put_contents('index.html', $html);