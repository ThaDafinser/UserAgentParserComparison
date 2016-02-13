<?php
use UserAgentParserComparison\Html\SimpleList;

/**
 * Generate some general lists
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$providerRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Provider');

$providers = $providerRepo->findAll();

foreach ($providers as $provider) {
    /* @var $provider \UserAgentParserComparison\Entity\Provider */
    
    echo $provider->name . PHP_EOL;
    
    $folder = $basePath . '/detected/' . $provider->name . '';
    if (! file_exists($folder)) {
        mkdir($folder, null, true);
    }
    
    /*
     * detected - browserNames
     */
    if ($provider->canDetectBrowserName === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resBrowserName
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected browser names - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/browser-names.html', $generate->getHtml());
    }
    
    /*
     * detected - renderingEngines
     */
    if ($provider->canDetectEngineName === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resEngineName
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected rendering engines - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/rendering-engines.html', $generate->getHtml());
    }
    
    /*
     * detected - OSnames
     */
    if ($provider->canDetectOsName === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resOsName
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected operating systems - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/operating-systems.html', $generate->getHtml());
    }
    
    /*
     * detected - deviceModel
     */
    if ($provider->canDetectDeviceModel === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resDeviceModel
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected device models - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/device-models.html', $generate->getHtml());
    }
    
    /*
     * detected - deviceBrand
     */
    if ($provider->canDetectDeviceBrand === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resDeviceBrand
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected device brands - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/device-brands.html', $generate->getHtml());
    }
    
    /*
     * detected - deviceTypes
     */
    if ($provider->canDetectDeviceType === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resDeviceType
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected device types - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/device-types.html', $generate->getHtml());
    }
    
    /*
     * detected - bots
     */
    if ($provider->canDetectBotIsBot === true) {
        $sql = "
            SELECT
                resBotName as name,
                uaId,
            	uaString
            FROM result
            JOIN userAgent
                ON uaId = userAgent_id
            WHERE
                resBotIsBot IS NOT NULL
                AND provider_id = '" . $provider->id . "'
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('Detected as bot - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/bot-is-bot.html', $generate->getHtml());
    }
    
    /*
     * detected - botNames
     */
    if ($provider->canDetectBotName === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resBotName
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected bot names - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/bot-names.html', $generate->getHtml());
    }
    
    /*
     * detected - botTypes
     */
    if ($provider->canDetectBotType === true) {
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
                AND provider_id = '" . $provider->id . "'
            GROUP BY resBotType
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('Detected bot types - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/bot-types.html', $generate->getHtml());
    }
}
