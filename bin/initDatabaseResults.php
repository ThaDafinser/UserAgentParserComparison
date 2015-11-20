<?php
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParserComparison\AnalyzeResult;
include_once 'bootstrap.php';
/*
 * generate a sqlite database with all parse results
 */
$pdo = new PDO('sqlite:data/results.sqlite3');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 * Create the tables
 */
$sql = "
    DROP TABLE IF EXISTS `vendorResult`;
    CREATE TABLE vendorResult (
        `resId` VARCHAR(255) NOT NULL PRIMARY KEY,
        `userAgent_uaId` VARCHAR(255),
        
        `providerName` VARCHAR(255),
        `providerPackageName` VARCHAR(255),
        `providerVersion` VARCHAR(255),
        
        `resultFound` INT,
    
        `browserResultFound` INT,
        `browserName` VARCHAR(255),
        `browserVersion` VARCHAR(255),
    
        `engineResultFound` INT,
        `engineNameMatchCount` INT,
        `engineNameOtherResults` INT,
        `engineName` VARCHAR(255),
        `engineVersion` VARCHAR(255),
    
        `osResultFound` INT,
        `osName` VARCHAR(255),
        `osVersion` VARCHAR(255),
    
        `deviceResultFound` INT,
        `deviceModelFound` INT,
        `deviceBrandFound` INT,
        `deviceTypeFound` INT,
        `deviceModel` VARCHAR(255),
        `deviceBrand` VARCHAR(255),
        `deviceType` VARCHAR(255),
        `deviceIsMobile` INT,
        `deviceIsTouch` INT,
    
        `botIsBot` INT,
        `botName` VARCHAR(255),
        `botType` VARCHAR(255),
    
        `result` LONGTEXT,
        `rawResult` LONGTEXT,
    
        `parseTime` DECIMAL(20,10),
    
        FOREIGN KEY(`userAgent_uaId`) REFERENCES `userAgent`(`uaId`)
    );
";
$pdo->exec($sql);

/* @var $chain \UserAgentParser\Provider\Chain */
$chain = include 'getChainProvider.php';

/*
 * prepare insert statement
 */
$insert = "
    INSERT INTO vendorResult (
        `resId`, 
        `userAgent_uaId`, 
    
        `providerName`, 
        `providerPackageName`,
        `providerVersion`, 
    
        `resultFound`,
    
        `browserResultFound`,
        `browserName`,
        `browserVersion`,
    
        `engineResultFound`,
        `engineNameMatchCount`,
        `engineNameOtherResults`,
        `engineName`,
        `engineVersion`,
    
        `osResultFound`,
        `osName`,
        `osVersion`,
        
        `deviceResultFound`,
        `deviceModelFound`,
        `deviceBrandFound`,
        `deviceTypeFound`,
        `deviceModel`,
        `deviceBrand`,
        `deviceType`,
        `deviceIsMobile`,
        `deviceIsTouch`,
    
        `botIsBot`,
        `botName`,
        `botType`,
    
        `result`,
        `rawResult`,
    
        `parseTime`
    )
    VALUES (
        :resId,
        :userAgent_uaId,
    
        :providerName, 
        :providerPackageName,
        :providerVersion, 
    
        :resultFound,
    
        :browserResultFound,
        :browserName,
        :browserVersion,
    
        :engineResultFound,
        :engineNameMatchCount,
        :engineNameOtherResults,
        :engineName,
        :engineVersion,
    
        :osResultFound,
        :osName,
        :osVersion,
        
        :deviceResultFound,
        :deviceModelFound,
        :deviceBrandFound,
        :deviceTypeFound,
        :deviceModel,
        :deviceBrand,
        :deviceType,
        :deviceIsMobile,
        :deviceIsTouch,
    
        :botIsBot,
        :botName,
        :botType,
    
        :result,
        :rawResult,
    
        :parseTime
    )
";
$stmtInsert = $pdo->prepare($insert);

/*
 * load userAgents...
 */
$sql = "
    SELECT
        *
    FROM userAgent
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currenUserAgent = 1;
$ourId = 0;
foreach ($results as $row) {
    
    $analyze = new AnalyzeResult();
    
    foreach ($chain->getProviders() as $provider) {
        /* @var $provider \UserAgentParser\Provider\AbstractProvider */
        
        $start = microtime(true);
        try {
            $result = $provider->parse($row['userAgent']);
        } catch (NoResultFoundException $ex) {
            $result = null;
        }
        $end = microtime(true);
        
        $analyze->addResult($provider, $result, ['parseTime' => $end-$start]);
    }
    
    foreach ($analyze->getAnalyzedResult() as $providerResult) {
        /* @var $provider \UserAgentParser\Provider\AbstractProvider */
        $provider = $providerResult['provider'];
        
        /* @var $result \UserAgentParser\Model\UserAgent */
        $result = $providerResult['result'];
        
        $misc = $providerResult['misc'];
        
        $matchCount = $providerResult['matchCount'];
        
        $ourId ++;
        
        $stmtInsert->bindValue(':resId', $ourId);
        $stmtInsert->bindValue(':userAgent_uaId', $row['uaId']);
        
        $stmtInsert->bindValue(':providerName', $provider->getName());
        $stmtInsert->bindValue(':providerPackageName', $provider->getComposerPackageName());
        $stmtInsert->bindValue(':providerVersion', $provider->getVersion());
        
        $stmtInsert->bindValue(':parseTime', $misc['parseTime']);
        
        if ($result === null) {
            // no result found
            $stmtInsert->bindValue(':resultFound', 0);
            
            $stmtInsert->bindValue(':browserResultFound', 0);
            $stmtInsert->bindValue(':browserName', null);
            $stmtInsert->bindValue(':browserVersion', null);
            
            $stmtInsert->bindValue(':engineResultFound', 0);
            $stmtInsert->bindValue(':engineNameMatchCount', null);
            $stmtInsert->bindValue(':engineNameOtherResults', null);
            $stmtInsert->bindValue(':engineName', null);
            $stmtInsert->bindValue(':engineVersion', null);
            
            $stmtInsert->bindValue(':osResultFound', 0);
            $stmtInsert->bindValue(':osName', null);
            $stmtInsert->bindValue(':osVersion', null);
            
            $stmtInsert->bindValue(':deviceResultFound', 0);
            $stmtInsert->bindValue(':deviceModelFound', 0);
            $stmtInsert->bindValue(':deviceBrandFound', 0);
            $stmtInsert->bindValue(':deviceTypeFound', 0);
            $stmtInsert->bindValue(':deviceModel', null);
            $stmtInsert->bindValue(':deviceBrand', null);
            $stmtInsert->bindValue(':deviceType', null);
            $stmtInsert->bindValue(':deviceIsMobile', 0);
            $stmtInsert->bindValue(':deviceIsTouch', 0);
            
            $stmtInsert->bindValue(':botIsBot', 0);
            $stmtInsert->bindValue(':botName', null);
            $stmtInsert->bindValue(':botType', null);
            
            $stmtInsert->bindValue(':result', null);
            $stmtInsert->bindValue(':rawResult', null);
            
            $stmtInsert->execute();
            
            continue;
        }
        
        $stmtInsert->bindValue(':resultFound', 1);
        
        /*
         * Browser
         */
        if ($result->getBrowser()->getName() !== null) {
            $stmtInsert->bindValue(':browserResultFound', 1);
        } else {
            $stmtInsert->bindValue(':browserResultFound', 0);
        }
        
        $stmtInsert->bindValue(':browserName', $result->getBrowser()
            ->getName());
        $stmtInsert->bindValue(':browserVersion', $result->getBrowser()
            ->getVersion()
            ->getComplete());
        
        /*
         * Engine
         */
        if ($result->getRenderingEngine()->getName() !== null) {
            $stmtInsert->bindValue(':engineResultFound', 1);
        } else {
            $stmtInsert->bindValue(':engineResultFound', 0);
        }
        
        $stmtInsert->bindValue(':engineNameMatchCount', $matchCount['renderingEngine']['name']['matchCount']);
        $stmtInsert->bindValue(':engineNameOtherResults', $matchCount['renderingEngine']['name']['countOtherResults']);
        
        $stmtInsert->bindValue(':engineName', $result->getRenderingEngine()
            ->getName());
        $stmtInsert->bindValue(':engineVersion', $result->getRenderingEngine()
            ->getVersion()
            ->getComplete());
        
        /*
         * OS
         */
        if ($result->getOperatingSystem()->getName() !== null) {
            $stmtInsert->bindValue(':osResultFound', 1);
        } else {
            $stmtInsert->bindValue(':osResultFound', 0);
        }
        
        $stmtInsert->bindValue(':osName', $result->getOperatingSystem()
            ->getName());
        $stmtInsert->bindValue(':osVersion', $result->getOperatingSystem()
            ->getVersion()
            ->getComplete());
        
        /*
         * Device
         */
        $device = $result->getDevice();
        if ($device->getModel() !== null || $device->getBrand() !== null || $device->getType() !== null || $device->getIsMobile() !== null) {
            $stmtInsert->bindValue(':deviceResultFound', 1);
        } else {
            $stmtInsert->bindValue(':deviceResultFound', 0);
        }
        
        if ($device->getModel() !== null) {
            $stmtInsert->bindValue(':deviceModelFound', 1);
        } else {
            $stmtInsert->bindValue(':deviceModelFound', 0);
        }
        
        if ($device->getBrand() !== null) {
            $stmtInsert->bindValue(':deviceBrandFound', 1);
        } else {
            $stmtInsert->bindValue(':deviceBrandFound', 0);
        }
        
        if ($device->getType() !== null) {
            $stmtInsert->bindValue(':deviceTypeFound', 1);
        } else {
            $stmtInsert->bindValue(':deviceTypeFound', 0);
        }
        $stmtInsert->bindValue(':deviceModel', $device->getModel());
        $stmtInsert->bindValue(':deviceBrand', $device->getBrand());
        $stmtInsert->bindValue(':deviceType', $device->getType());
        $stmtInsert->bindValue(':deviceIsMobile', ($device->getIsMobile() ? $device->getIsMobile() : 0));
        $stmtInsert->bindValue(':deviceIsTouch', ($device->getIsTouch() ? $device->getIsTouch() : 0));
        
        /*
         * Bot
         */
        $bot = $result->getBot();
        $stmtInsert->bindValue(':botIsBot', ($bot->getIsBot() ? $bot->getIsBot() : 0));
        $stmtInsert->bindValue(':botName', $bot->getName());
        $stmtInsert->bindValue(':botType', $bot->getType());
        
        $stmtInsert->bindValue(':result', serialize($result->toArray()));
        $stmtInsert->bindValue(':rawResult', serialize($result->getProviderResultRaw()));
        
        $stmtInsert->execute();
    }
    
    // display "progress"
    echo $currenUserAgent . ' / ' . count($results) . PHP_EOL;
    
    $currenUserAgent ++;
}
