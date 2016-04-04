<?php
use UserAgentParserComparison\Entity\UserAgent;
use Ramsey\Uuid\Uuid;

include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$userAgentRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\UserAgent');

/*
 * Grab the userAgents!
 */
echo '~~~ Load all UAs ~~~' . PHP_EOL;

$files = [
    'browscap.php',
    'donatj.php',
    'jenssegers-agent.php',
    'mobile-detect.php',
    'piwik.php',
    'sinergi.php',
    'uap-core.php',
    'whichbrowser.php',
    'woothee.php',
    'zsxsoft.php'
];

foreach ($files as $file) {
    if (strpos($file, '.php') === false) {
        continue;
    }
    
    echo $file . PHP_EOL;
    
    $result = include 'data/datasets/' . $file;
    
    if (! isset($result['provider']) || ! isset($result['userAgents']) || ! is_array($result['userAgents'])) {
        throw new \Exception('Result is not valid! ' . $file);
    }
    
    /*
     * save provider
     */
    $provider = $result['provider'];
    
    $version = \PackageVersions\Versions::getVersion($provider['proPackageName']);
    $version = explode('@', $version);
    $version = $version[0];
    
    $provider['proVersion'] = $version;
    
    $sql = "
        SELECT
            *
        FROM provider
        WHERE
            proType = '" . $provider['proType'] . "'
            AND proName = '" . $provider['proName'] . "'
    ";
    $dbResult = $conn->fetchAll($sql);
    
    if (count($dbResult) === 1) {
        // update!
        $proId = $dbResult[0]['proId'];
        
        $conn->update('provider', $provider, [
            'proId' => $dbResult[0]['proId']
        ]);
        
        echo 'U';
    } else {
        $proId = Uuid::uuid4()->toString();
        
        $provider['proId'] = $proId;
        
        $conn->insert('provider', $provider);
        
        echo 'I';
    }
    
    echo PHP_EOL;
    echo 'UserAgent count: ' . count($result['userAgents']) . PHP_EOL;
    
    /*
     * Useragents
     */
    foreach ($result['userAgents'] as $uaHash => $row) {
        
        /*
         * insert UA itself
         */
        $sql = "
            SELECT
                *
            FROM userAgent
            WHERE
                uaHash = '" . $uaHash . "'
        ";
        $result2 = $conn->fetchAll($sql);
        
        if (count($result2) === 1) {
            // update!
            $uaId = $result2[0]['uaId'];
        } else {
            $uaId = Uuid::uuid4()->toString();
            
            $row2 = [
                'uaId' => $uaId,
                'uaHash' => $uaHash,
                'uaString' => $row['uaString']
            ];
            
            $conn->insert('userAgent', $row2);
        }
        
        /*
         * Result
         */
        $res = $row['result'];
        
        $res['provider_id'] = $proId;
        $res['userAgent_id'] = $uaId;
        
        $res['resProviderVersion'] = $version;
        
        $res['resResultFound'] = 1;
        $res['resFilename'] = str_replace('\\', '/', $res['resFilename']);
        
        $date = new \DateTime(null, new \DateTimeZone('UTC'));
        $res['resLastChangeDate'] = $date->format('Y-m-d H:i:s');
        
        $sql = "
            SELECT
                *
            FROM result
            WHERE
                provider_id = '" . $proId . "'
                AND userAgent_id = '" . $uaId . "'
        ";
        $result2 = $conn->fetchAll($sql);
        
        if (count($result2) === 1) {
            // update!
            $resId = $result2[0]['resId'];
            
            $conn->update('result', $res, [
                'resId' => $resId
            ]);
            
            echo 'U';
        } else {
            $res['resId'] = Uuid::uuid4()->toString();
            
            $conn->insert('result', $res);
            
            echo 'I';
        }
    }
    
    echo PHP_EOL . PHP_EOL;
}
