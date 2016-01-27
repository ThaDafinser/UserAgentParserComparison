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
    
    $folder = $basePath . '/not-detected/' . $provider->name . '';
    if (! file_exists($folder)) {
        mkdir($folder, null, true);
    }
    
    /*
     * no result found
     */
    $sql = "
        SELECT
        	resBrowserName as name,
        	uaId,
        	uaString,
        	(
        		SELECT
        			COUNT(1)
        		FROM result as res2
                WHERE
        			res2.userAgent_id = uaId
                    AND res2.resResultFound = 1
        			AND res2.provider_id != '" . $provider->id . "'
            ) as `detectionCount`
        FROM result
        JOIN userAgent
        	ON uaId = userAgent_id
        WHERE
        	provider_id = '" . $provider->id . "'
            AND resResultFound = 0
    ";
    $result = $conn->fetchAll($sql);
    
    $generate = new SimpleList();
    $generate->setTitle('Not detected - ' . $provider->name . ' <small>' . $provider->version . '</small>');
    $generate->setElements($result);
    
    file_put_contents($folder . '/no-result-found.html', $generate->getHtml());
    
    /*
     * browserName
     */
    if ($provider->canDetectBrowserName === true) {
        echo '.';
        
        $sql = "
            SELECT 
            	resBrowserName as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBrowserName IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NULL
                AND resBrowserName IS NULL
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('No browser name found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/browser-names.html', $generate->getHtml());
    }
    
    /*
     * renderingEngine
     */
    if ($provider->canDetectEngineName === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resEngineName as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resEngineName IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NULL
                AND resEngineName IS NULL
        ";
        $result = $conn->fetchAll($sql);
        
        $generate = new SimpleList();
        $generate->setTitle('No rendering engine found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
        
        file_put_contents($folder . '/rendering-engines.html', $generate->getHtml());
    }
    
    /*
     * OSname
     */
    if ($provider->canDetectOsName === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resOsName as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resOsName IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NULL
                AND resOsName IS NULL
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('No operating system found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/operating-systems.html', $generate->getHtml());
    }
    
    /*
     * deviceModel
     */
    if ($provider->canDetectDeviceModel === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resDeviceModel as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resDeviceModel IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NULL
                AND resDeviceModel IS NULL
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('No device model found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/device-models.html', $generate->getHtml());
    }
    
    /*
     * deviceBrand
     */
    if ($provider->canDetectDeviceBrand === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resDeviceBrand as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resDeviceBrand IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NULL
                AND resDeviceBrand IS NULL
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('No device brands found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/device-brands.html', $generate->getHtml());
    }
    
    /*
     * deviceTypes
     */
    if ($provider->canDetectDeviceType === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resDeviceType as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resDeviceType IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NULL
                AND resDeviceType IS NULL
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('No device type found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/device-types.html', $generate->getHtml());
    }
    
    /*
     * botNames
     */
    if ($provider->canDetectBotName === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resBotName as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resBotName IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NOT NULL
                AND resBotName IS NULL
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('No bot name found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/bot-names.html', $generate->getHtml());
    }
    
    /*
     * botTypes
     */
    if ($provider->canDetectBotType === true) {
        echo '.';
        
        $sql = "
            SELECT
            	resBotType as name,
            	uaId,
            	uaString,
            	(
            		SELECT
            			COUNT(1)
            		FROM result as res2
                    WHERE
            			res2.userAgent_id = uaId
                        AND res2.resBotType IS NOT NULL
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resBotIsBot IS NOT NULL
                AND resBotType IS NULL
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList();
        $generate->setTitle('No bot type found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/bot-types.html', $generate->getHtml());
    }
    
    echo PHP_EOL;
}

