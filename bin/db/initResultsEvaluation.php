<?php
use UserAgentParserComparison\Evaluation\ResultsPerUserAgent;
use UserAgentParserComparison\Entity\UserAgentEvaluation;
use UserAgentParserComparison\Entity\ResultEvaluation;
use UserAgentParserComparison\Evaluation\ResultsPerProviderResult;
use Ramsey\Uuid\Uuid;

include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$resultEvaluationRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\ResultEvaluation');

$sql = "
SELECT
    *
FROM result
ORDER BY userAgent_id
";
$statement = $conn->prepare($sql);
$statement->execute();

echo 'done loading..' . PHP_EOL;

$conn->beginTransaction();

$i = 1;
while ($row = $statement->fetch()) {
    $sql = "
        SELECT
            GROUP_CONCAT(resBrowserName SEPARATOR '~~~') browserName,
            GROUP_CONCAT(resBrowserVersion SEPARATOR '~~~') browserVersion,
            
            GROUP_CONCAT(resEngineName SEPARATOR '~~~') engineName,
            GROUP_CONCAT(resEngineVersion SEPARATOR '~~~') engineVersion,
            
            GROUP_CONCAT(resOsName SEPARATOR '~~~') osName,
            GROUP_CONCAT(resOsVersion SEPARATOR '~~~') osVersion,
            
            GROUP_CONCAT(resDeviceModel SEPARATOR '~~~') deviceModel,
            GROUP_CONCAT(resDeviceBrand SEPARATOR '~~~') deviceBrand,
            GROUP_CONCAT(resDeviceType SEPARATOR '~~~') deviceType,
            
            IFNULL(SUM(resDeviceIsMobile), 0) as deviceIsMobileCount,
        	IFNULL(SUM(resDeviceIsTouch), 0) as deviceIsTouchCount,
            
        	IFNULL(SUM(resBotIsBot), 0) as isBotCount,
            
            GROUP_CONCAT(resBotName SEPARATOR '~~~') botName,
            GROUP_CONCAT(resBotType SEPARATOR '~~~') botType
        FROM result
        WHERE
            userAgent_id = '" . $row['userAgent_id'] . "'
            AND provider_id != '" . $row['provider_id'] . "'
        GROUP BY 
            userAgent_id
    ";
    $result = $conn->fetchAll($sql);
    if (! isset($result[0])) {
        throw new \Exception('no result found...' . $row['userAgent_id'] . ' | ' . $row['provider_id']);
    }
    $resultGrouped = $result[0];
    
    /*
     * Check if already inserted
     */
    $sql = "
        SELECT
            *
        FROM resultEvaluation
        WHERE
            result_id = '" . $row['resId'] . "'
    ";
    $result = $conn->fetchAll($sql);
    if (count($result) === 1) {
        $row2 = $result[0];
    
        // skip date is greater (generated after last result)
        if ($row2['lastChangeDate'] >= $row['resLastChangeDate']) {
            echo 'S';
            continue;
        }
    
        // so go update!
    } else {
        // create
        $row2 = [
            'result_id' => $row['resId']
        ];
    }
    
    $date = new \DateTime(null, new \DateTimeZone('UTC'));
    $row2['lastChangeDate'] = $date->format('Y-m-d H:i:s');
    ;
    
    $row2 = hydrateResult($row2, $row, $resultGrouped);
    
    if (! isset($row2['revId'])) {
        $row2['revId'] = Uuid::uuid4()->toString();
    
        $conn->insert('resultEvaluation', $row2);
    } else {
        $conn->update('resultEvaluation', $row2, [
            'revId' => $row2['revId']
        ]);
    }
    
    echo '.';
    
    if ($i % 100 === 0) {
        $conn->commit();
        
        $conn->beginTransaction();
    }
    
    $i ++;
}

if ($conn->getTransactionNestingLevel() !== 0) {
    $conn->commit();
}

function hydrateResult(array $row2, array $row, array $resultGrouped)
{
    /*
     * Browser name
     */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resBrowserName']);
    $evaluate->setValue($resultGrouped['browserName']);
    $evaluate->setType('browserName');
    $evaluate->evaluate();
    
    $row2['browserNameSameResult'] = $evaluate->getSameResultCount();
    $row2['browserNameHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * Browser version
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resBrowserVersion']);
    $evaluate->setValue($resultGrouped['browserVersion']);
    $evaluate->setType('version');
    $evaluate->evaluate();
    
    $row2['browserVersionSameResult'] = $evaluate->getSameResultCount();
    $row2['browserVersionHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * Engine name
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resEngineName']);
    $evaluate->setValue($resultGrouped['engineName']);
    $evaluate->setType('engineName');
    $evaluate->evaluate();
    
    $row2['engineNameSameResult'] = $evaluate->getSameResultCount();
    $row2['engineNameHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * Engine version
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resEngineVersion']);
    $evaluate->setValue($resultGrouped['engineVersion']);
    $evaluate->setType('version');
    $evaluate->evaluate();
    
    $row2['engineVersionSameResult'] = $evaluate->getSameResultCount();
    $row2['engineVersionHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * Os name
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resOsName']);
    $evaluate->setValue($resultGrouped['osName']);
    $evaluate->setType('osName');
    $evaluate->evaluate();
    
    $row2['osNameSameResult'] = $evaluate->getSameResultCount();
    $row2['osNameHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * Os version
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resOsVersion']);
    $evaluate->setValue($resultGrouped['osVersion']);
    $evaluate->setType('version');
    $evaluate->evaluate();
    
    $row2['osVersionSameResult'] = $evaluate->getSameResultCount();
    $row2['osVersionHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * deviceModel
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resDeviceModel']);
    $evaluate->setValue($resultGrouped['deviceModel']);
    $evaluate->setType('deviceModel');
    $evaluate->evaluate();
    
    $row2['deviceModelSameResult'] = $evaluate->getSameResultCount();
    $row2['deviceModelHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * deviceBrand
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resDeviceBrand']);
    $evaluate->setValue($resultGrouped['deviceBrand']);
    $evaluate->setType('deviceBrand');
    $evaluate->evaluate();
    
    $row2['deviceBrandSameResult'] = $evaluate->getSameResultCount();
    $row2['deviceBrandHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * deviceType
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resDeviceType']);
    $evaluate->setValue($resultGrouped['deviceType']);
    $evaluate->setType('deviceType');
    $evaluate->evaluate();
    
    $row2['deviceTypeSameResult'] = $evaluate->getSameResultCount();
    $row2['deviceTypeHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * Detected as mobile
    */
    $row2['asMobileDetectedByOthers'] = (int) $resultGrouped['deviceIsMobileCount'];
    
    /*
     * Detected as touch
    */
    $row2['asTouchDetectedByOthers'] = (int) $resultGrouped['deviceIsTouchCount'];
    
    /*
     * Detected as bot
    */
    $row2['asBotDetectedByOthers'] = (int) $resultGrouped['isBotCount'];
    
    /*
     * botName
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resBotName']);
    $evaluate->setValue($resultGrouped['botName']);
    $evaluate->setType('botName');
    $evaluate->evaluate();
    
    $row2['botNameSameResult'] = $evaluate->getSameResultCount();
    $row2['botNameHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    /*
     * botType
    */
    $evaluate = new ResultsPerProviderResult();
    $evaluate->setCurrentValue($row['resBotType']);
    $evaluate->setValue($resultGrouped['botType']);
    $evaluate->setType('botType');
    $evaluate->evaluate();
    
    $row2['botTypeSameResult'] = $evaluate->getSameResultCount();
    $row2['botTypeHarmonizedSameResult'] = $evaluate->getHarmonizedSameResultCount();
    
    return $row2;
}
