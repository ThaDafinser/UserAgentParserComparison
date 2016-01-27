<?php
use UserAgentParserComparison\Html\SimpleList;

/**
 * Generate some general lists
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

/*
 * create the folder
 */
$folder = $basePath . '/detected/general';
if (! file_exists($folder)) {
    mkdir($folder, null, true);
}

/*
 * detected - browserNames
 */
$sql = "
    SELECT 
        resBrowserName as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resBrowserName IS NOT NULL
    GROUP BY resBrowserName
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected browser names');
$generate->setElements($result);

file_put_contents($folder . '/browser-names.html', $generate->getHtml());

/*
 * detected - renderingEngines
 */
$sql = "
    SELECT
        resEngineName as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resEngineName IS NOT NULL
    GROUP BY resEngineName
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected rendering engines');
$generate->setElements($result);

file_put_contents($folder . '/rendering-engines.html', $generate->getHtml());

/*
 * detected - OSnames
 */
$sql = "
    SELECT
        resOsName as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resOsName IS NOT NULL
    GROUP BY resOsName
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected operating systems');
$generate->setElements($result);

file_put_contents($folder . '/operating-systems.html', $generate->getHtml());

/*
 * detected - deviceModel
 */
$sql = "
    SELECT
        resDeviceModel as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resDeviceModel IS NOT NULL
    GROUP BY resDeviceModel
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected device models');
$generate->setElements($result);

file_put_contents($folder . '/device-models.html', $generate->getHtml());

/*
 * detected - deviceBrand
 */
$sql = "
    SELECT
        resDeviceBrand as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resDeviceBrand IS NOT NULL
    GROUP BY resDeviceBrand
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected device brands');
$generate->setElements($result);

file_put_contents($folder . '/device-brands.html', $generate->getHtml());

/*
 * detected - deviceTypes
 */
$sql = "
    SELECT
        resDeviceType as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resDeviceType IS NOT NULL
    GROUP BY resDeviceType
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected device types');
$generate->setElements($result);

file_put_contents($folder . '/device-types.html', $generate->getHtml());

/*
 * detected - botNames
 */
$sql = "
    SELECT
        resBotName as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resBotName IS NOT NULL
    GROUP BY resBotName
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected bot names');
$generate->setElements($result);

file_put_contents($folder . '/bot-names.html', $generate->getHtml());

/*
 * detected - botTypes
 */
$sql = "
    SELECT
        resBotType as name,
        uaId,
    	uaString,
        COUNT(1) `detectionCount`
    FROM result
    JOIN userAgent
        ON uaId = userAgent_id
    WHERE
        resBotType IS NOT NULL
    GROUP BY resBotType
";
$result = $conn->fetchAll($sql);

$generate = new SimpleList();
$generate->setTitle('Detected bot types');
$generate->setElements($result);

file_put_contents($folder . '/bot-types.html', $generate->getHtml());
