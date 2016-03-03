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
    'browscap_all.php',
    'piwik_all.php',
    // 'uap-core_all.php',
    // 'whichbrowser_all.php',
    // 'woothee_all.php',
];


$userAgents = [];

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
     * Add user agents
     */
    $userAgents = array_merge_recursive($userAgents, $result['userAgents']);
    
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
    $result = $conn->fetchAll($sql);
    
    if (count($result) === 1) {
        // update!
        $conn->update('provider', $provider, [
            'proId' => $result[0]['proId']
        ]);
        
        echo 'U';
    } else {
        $provider['proId'] = Uuid::uuid4()->toString();
        
        $conn->insert('provider', $provider);
        
        echo 'I';
    }
}

var_dump(count($userAgents));
exit();

echo 'UserAgent count: ' . count($userAgents) . PHP_EOL;

/*
 * Insert them!
 */
echo '~~~ Insert all UAs ~~~' . PHP_EOL;

$conn->beginTransaction();
$currenUserAgent = 1;

foreach ($userAgents as $row) {
    $row['uaFileName'] = str_replace('\\', '/', $row['uaFileName']);
    
    $parts = explode($row['uaSource'], $row['uaFileName']);
    if (count($parts) === 2) {
        $row['uaFileName'] = $parts[1];
    }
    
    $row['uaHash'] = bin2hex(sha1($row['uaString'], true));
    
    $sql = "
        SELECT
            *
        FROM userAgent
        WHERE
            uaHash = '" . $row['uaHash'] . "'
    ";
    $result = $conn->fetchAll($sql);
    
    if (count($result) === 1) {
        // update!
        $conn->update('userAgent', $row, [
            'uaId' => $result[0]['uaId']
        ]);
        
        echo 'U';
        
        continue;
    }
    
    $row['uaId'] = Uuid::uuid4()->toString();
    
    $conn->insert('userAgent', $row);
    
    echo 'I';
    
    if ($currenUserAgent % 100 === 0) {
        $conn->commit();
        
        $conn->beginTransaction();
    }
    
    $currenUserAgent ++;
}

if ($conn->getTransactionNestingLevel() !== 0) {
    $conn->commit();
}
