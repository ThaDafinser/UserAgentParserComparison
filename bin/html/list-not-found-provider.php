<?php
use UserAgentParserComparison\Html\SimpleList;

/**
 * Generate some general lists
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

/*
 * select all real providers
 */
$sql = "
    SELECT
        *
    FROM provider
    WHERE
        proType = 'real'
";
$result = $conn->fetchAll($sql);

$proIds = array_column($result, 'proId');

/*
 * Start for each provider
 */
$providerRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Provider');

foreach ($providerRepo->findBy(['type' => 'real']) as $provider) {
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
                    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
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
    
    $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resBrowserName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBrowserName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resBrowserName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBrowserName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
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
        
        $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resEngineName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resEngineName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resEngineName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resEngineName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
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
        
        $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resOsName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resOsName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resOsName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resOsName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
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
    
        $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resDeviceModel)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resDeviceModel IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resDeviceModel)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resDeviceModel IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
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
    
        $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resDeviceBrand)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resDeviceBrand IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resDeviceBrand)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resDeviceBrand IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
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
    
        $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resDeviceType)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resDeviceType IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resDeviceType)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resDeviceType IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
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
    
        $generate = new SimpleList($entityManager);
        $generate->setTitle('No device type found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/device-types.html', $generate->getHtml());
    }
    
    /*
     * not detected as mobile
     */
    if ($provider->canDetectDeviceIsMobile === true) {
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
                        AND res2.resDeviceIsMobile IS NOT NULL
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
                AND resResultFound = 1
                AND resDeviceIsMobile IS NULL
        	    AND userAgent_id IN(
            		SELECT
            			userAgent_id
            		FROM provider
                    JOIN result 
            			ON provider_id = proId
            			AND resDeviceIsMobile = 1
                    WHERE 
            			proType = 'testSuite'
                )
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList($entityManager);
        $generate->setTitle('Not detected as mobile - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/device-is-mobile.html', $generate->getHtml());
    }
    
    /*
     * not detected as bot
     */
    if ($provider->canDetectBotIsBot === true) {
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
                        AND res2.resBotIsBot IS NOT NULL
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
        	    AND resResultFound = 1
                AND resBotIsBot IS NULL
        	    AND userAgent_id IN(
            		SELECT
            			userAgent_id
            		FROM provider
                    JOIN result 
            			ON provider_id = proId
            			AND resBotIsBot = 1
                    WHERE 
            			proType = 'testSuite'
                )
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList($entityManager);
        $generate->setTitle('Not detected as bot - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/bot-is-bot.html', $generate->getHtml());
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resBotName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBotName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resBotName)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBotName IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
        	    AND resResultFound = 1
                AND resBotName IS NULL
        	    AND userAgent_id IN(
            		SELECT
            			userAgent_id
            		FROM provider
                    JOIN result 
            			ON provider_id = proId
            			AND resBotName IS NOT NULL
        	       WHERE 
            			proType = 'testSuite'
                )
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList($entityManager);
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
                        AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCount`,
			    (
            		SELECT
            			COUNT(DISTINCT res2.resBotType)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBotType IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionCountUnique`,
                (
            		SELECT
            			GROUP_CONCAT(DISTINCT res2.resBotType)
            		FROM result as res2
                    WHERE 
            			res2.userAgent_id = uaId
                        AND res2.resBotType IS NOT NULL
        			    AND res2.provider_id IN('" . implode('\', \'', $proIds) . "')
            			AND res2.provider_id != '" . $provider->id . "'
                ) as `detectionValuesDistinct`
            FROM result
            JOIN userAgent
            	ON uaId = userAgent_id
            WHERE
            	provider_id = '" . $provider->id . "'
        	    AND resResultFound = 1
                AND resBotType IS NULL
        	    AND userAgent_id IN(
            		SELECT
            			userAgent_id
            		FROM provider
                    JOIN result 
            			ON provider_id = proId
            			AND resBotType IS NOT NULL
        	       WHERE 
            			proType = 'testSuite'
                )
        ";
        $result = $conn->fetchAll($sql);
    
        $generate = new SimpleList($entityManager);
        $generate->setTitle('No bot type found - ' . $provider->name . ' <small>' . $provider->version . '</small>');
        $generate->setElements($result);
    
        file_put_contents($folder . '/bot-types.html', $generate->getHtml());
    }
    
    echo PHP_EOL;
}
