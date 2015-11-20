<?php
use UserAgentParserComparison\GenerateHtmlListV2;
use UserAgentParserComparison\UserAgentParserComparison;
use UserAgentParserComparison\GenerateHtmlListSimple;
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
    /**
     * *****************************
     * Grouped detection results
     * *******************************
     */
    $path = 'results/' . $providerName . '/grouped';
    
    if (! file_exists($path)) {
        mkdir($path, null, true);
    }
    
    /*
     * detected browsers
     */
    $sql = "
        SELECT 
            userAgent,
            browserName as name
        FROM vendorResult
        JOIN userAgent ON uaId = userAgent_uaId
        WHERE
            providerName = '" . $providerName . "'
            AND browserName IS NOT NULL
        GROUP BY browserName
        ORDER BY browserName
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $generate = new GenerateHtmlListSimple();
    $generate->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
    $generate->setTitle($providerName . ' - browsers detected');
    
    file_put_contents($path . '/browser.html', $generate->getHtml());
    
    /*
     * detected engines
     */
    $sql = "
        SELECT
            userAgent,
            engineName as name
        FROM vendorResult
        JOIN userAgent ON uaId = userAgent_uaId
        WHERE
            providerName = '" . $providerName . "'
            AND engineName IS NOT NULL
        GROUP BY engineName
        ORDER BY engineName
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $generate = new GenerateHtmlListSimple();
    $generate->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
    $generate->setTitle($providerName . ' - rendering engines detected');
    
    file_put_contents($path . '/engine.html', $generate->getHtml());
    
    /*
     * detected OS
     */
    $sql = "
        SELECT
            userAgent,
            osName as name
        FROM vendorResult
        JOIN userAgent ON uaId = userAgent_uaId
        WHERE
            providerName = '" . $providerName . "'
            AND osName IS NOT NULL
        GROUP BY osName
        ORDER BY osName
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $generate = new GenerateHtmlListSimple();
    $generate->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
    $generate->setTitle($providerName . ' - operating systems detected');
    
    file_put_contents($path . '/os.html', $generate->getHtml());
    
    /*
     * detected model
     */
    $sql = "
        SELECT
            userAgent,
            deviceModel as name
        FROM vendorResult
        JOIN userAgent ON uaId = userAgent_uaId
        WHERE
            providerName = '" . $providerName . "'
            AND deviceModel IS NOT NULL
        GROUP BY deviceModel
        ORDER BY deviceModel
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $generate = new GenerateHtmlListSimple();
    $generate->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
    $generate->setTitle($providerName . ' - device models detected');
    
    file_put_contents($path . '/deviceModel.html', $generate->getHtml());
    
    /*
     * detected brand
     */
    $sql = "
        SELECT
            userAgent,
            deviceBrand as name
        FROM vendorResult
        JOIN userAgent ON uaId = userAgent_uaId
        WHERE
            providerName = '" . $providerName . "'
            AND deviceBrand IS NOT NULL
        GROUP BY deviceBrand
        ORDER BY deviceBrand
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $generate = new GenerateHtmlListSimple();
    $generate->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
    $generate->setTitle($providerName . ' - device brands detected');
    
    file_put_contents($path . '/deviceBrand.html', $generate->getHtml());
    
    /*
     * detected type
     */
    $sql = "
        SELECT
            userAgent,
            deviceType as name
        FROM vendorResult
        JOIN userAgent ON uaId = userAgent_uaId
        WHERE
            providerName = '" . $providerName . "'
            AND deviceType IS NOT NULL
        GROUP BY deviceType
        ORDER BY deviceType
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $generate = new GenerateHtmlListSimple();
    $generate->setResult($stmt->fetchAll(PDO::FETCH_ASSOC));
    $generate->setTitle($providerName . ' - device types detected');
    
    file_put_contents($path . '/deviceType.html', $generate->getHtml());
    
    /**
     * *****************
     * No result found
     * **********************
     */
    $path = 'results/' . $providerName . '/noResult';
    
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
    $generate->setTitle($providerName . ' - no result found at all');
    
    file_put_contents($path . '/atAll.html', $generate->getHtml());
    
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
    
    file_put_contents($path . '/browser.html', $generate->getHtml());
    
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
    
    file_put_contents($path . '/engine.html', $generate->getHtml());
    
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
    
    file_put_contents($path . '/os.html', $generate->getHtml());
    
    /**
     * *****************
     * bot things
     * ***************
     */
    $path = 'results/' . $providerName . '/bot';
    
    if (! file_exists($path)) {
        mkdir($path, null, true);
    }
    /*
     * Should be detected as bot
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
    
    file_put_contents($path . '/shouldBeABot.html', $generate->getHtml());
    
    /*
     * Should NOT be detected as bot
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
    
    file_put_contents($path . '/shouldNotBeABot.html', $generate->getHtml());
}
