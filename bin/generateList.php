<?php
use UserAgentParserComparison\GenerateHtmlList;
use UserAgentParserComparison\GenerateHtmlListV2;
include_once 'bootstrap.php';

$pdo = new PDO('sqlite:data/results.sqlite3');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/**
 * **************************
 * All together detail list
 */
$sql = "
    SELECT
        uaId
    FROM userAgent
";
$generate = new GenerateHtmlListV2();
$generate->setSubquery($sql);
$generate->setTitle('All results');

file_put_contents('results/detail.html', $generate->getHtml());

/**
 * ******************************
 * Provider list
 */
$sql = "
    SELECT
        providerName
    FROM vendorResult
    GROUP BY
        providerName
    ORDER BY
        providerName
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$providers = array_column($result, 'providerName');

foreach ($providers as $providerName) {
    $path = 'results/' . $providerName;
    
    if (! file_exists($path)) {
        mkdir($path, null, true);
    }
    
    /*
     * No result found
     */
    $sql = "
        SELECT
        	userAgent_uaId
        FROM vendorResult
        WHERE 
            providerName = '" . $providerName . "'
            AND resultFound = 0
    ";
    
    $generate = new GenerateHtmlListV2();
    $generate->setSubquery($sql);
    $generate->setTitle($providerName . ' - no result found');
    
    file_put_contents($path . '/noResultFound.html', $generate->getHtml());
    
    /*
     * Not detected as bot
     */
    $sql = "
        SELECT
        	userAgent_uaId
        FROM userAgent
        JOIN vendorResult
            ON userAgent_uaId = uaId
        WHERE
            providerName = '" . $providerName . "'
            AND `group` = 'bot'
            AND botIsBot = 0
    ";
    $generate = new GenerateHtmlListV2();
    $generate->setSubquery($sql);
    $generate->setTitle($providerName . ' - not detected as bot');
    
    file_put_contents($path . '/notDetectedAsBot.html', $generate->getHtml());
    
    /*
     * Is probably no bot
     */
    $sql = "
        SELECT
        	userAgent_uaId
        FROM userAgent
        JOIN vendorResult
            ON userAgent_uaId = uaId
        WHERE
            providerName = '" . $providerName . "'
            AND `group` != 'bot'
            AND botIsBot = 1
    ";
    $generate = new GenerateHtmlListV2();
    $generate->setSubquery($sql);
    $generate->setTitle($providerName . ' - is probably no bot?');
    
    $path = 'results/' . $providerName;
    
    file_put_contents($path . '/isProbablyNoBot.html', $generate->getHtml());
    
    /*
     * No browser result found
     */
    $sql = "
        SELECT
        	userAgent_uaId
        FROM vendorResult
        WHERE
            providerName = '" . $providerName . "'
            AND resultFound = 1
            AND botIsBot = 0
            AND browserResultFound = 0
    ";
    $generate = new GenerateHtmlListV2();
    $generate->setSubquery($sql);
    $generate->setTitle($providerName . ' - no browser result found');
    
    file_put_contents($path . '/noBrowserResultFound.html', $generate->getHtml());
    
    /*
     * No renderingEngine result found
     */
    $sql = "
        SELECT
        	userAgent_uaId
        FROM vendorResult
        WHERE
            providerName = '" . $providerName . "'
            AND resultFound = 1
            AND botIsBot = 0
            AND engineResultFound = 0
    ";
    $generate = new GenerateHtmlListV2();
    $generate->setSubquery($sql);
    $generate->setTitle($providerName . ' - no rendering engine result found');
    
    file_put_contents($path . '/noRenderingEngineResultFound.html', $generate->getHtml());
    
    /*
     * No OS result found
     */
    $sql = "
        SELECT
        	userAgent_uaId
        FROM vendorResult
        WHERE
            providerName = '" . $providerName . "'
            AND resultFound = 1
            AND botIsBot = 0
            AND osResultFound = 0
    ";
    $generate = new GenerateHtmlListV2();
    $generate->setSubquery($sql);
    $generate->setTitle($providerName . ' - no operating system result found');
    
    
    file_put_contents($path . '/noOperatingSystemResultFound.html', $generate->getHtml());
}
