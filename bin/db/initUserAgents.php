<?php
use UserAgentParserComparison\Entity\UserAgent;
use Ramsey\Uuid\Uuid;

include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$userAgentRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\UserAgent');

/*
 * Get all current UA
 */
$sql = "
SELECT
    uaString
FROM userAgent
";
$result = $conn->fetchAll($sql);
$alreadyInsertedUserAgents = array_column($result, 'uaString');

/*
 * Grab the userAgents!
 */
$handleAll = opendir('data/datasets');
if ($handleAll === false) {
    throw new \Exception('folder could not get opened');
}

echo '~~~ Load all UAs ~~~' . PHP_EOL;

$allUserAgents = [];
while ($filename = readdir($handleAll)) {
    if ($filename == '.' || $filename == '..' || strpos($filename, '.php') === false) {
        continue;
    }
    
    echo $filename;
    
    // skip currently whichbrowser, because shell_exec does not work
    // if ($filename != 'whichbrowser_all.php') {
    // echo ' - skip' . PHP_EOL;
    
    // continue;
    // }
    
    echo ' - use it!' . PHP_EOL;
    
    $result = include 'data/datasets/' . $filename;
    
    $allUserAgents = array_merge($allUserAgents, $result['userAgents']);
}

$allUserAgents = array_unique($allUserAgents);

foreach ($allUserAgents as $key => $value) {
    if ($value == '') {
        unset($allUserAgents[$key]);
    }
}

echo 'UserAgents Unique: ' . count($allUserAgents) . PHP_EOL;
echo 'Already inserted: ' . count($alreadyInsertedUserAgents) . PHP_EOL;

$allUserAgents = array_diff($allUserAgents, $alreadyInsertedUserAgents);

echo 'LEft: ' . count($allUserAgents) . PHP_EOL;

/*
 * Insert them!
 */
echo '~~~ Insert all UAs ~~~' . PHP_EOL;

$sql = "
    INSERT INTO userAgent
        (uaId, uaHash, uaString)
    VALUES
";

$values = [];
foreach ($allUserAgents as $userAgent) {
    
    $uuid = Uuid::uuid4();
    
    $values[] = '(' . $conn->quote($uuid->toString(), \PDO::PARAM_STR) . ', 0x' . bin2hex(sha1($userAgent, true)) . ', ' . $conn->quote($userAgent, \PDO::PARAM_STR) . ')';
    
    if (count($values) > 2000) {
        $realSql = $sql . implode(',', $values);
        
        $conn->query($realSql);
        
        $values = [];
    }
    
    echo '.';
}

if (count($values) > 0) {
    $realSql = $sql . implode(',', $values);
    
    $conn->query($realSql);
}
