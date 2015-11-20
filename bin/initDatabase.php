<?php
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
    DROP TABLE IF EXISTS `userAgent`;
    CREATE TABLE userAgent (
        `uaId` VARCHAR(255) NOT NULL PRIMARY KEY, 
        `userAgent` VARCHAR(255), 
        `source` VARCHAR(255),
        `group` VARCHAR(255)
    );
";
$pdo->exec($sql);

/*
 * Fill in the userAgents!
 */
$handleAll = opendir('data/datasets');
if ($handleAll === false) {
    throw new \Exception('folder could not get opened');
}

$ourId = 1;
while ($filename = readdir($handleAll)) {
    if ($filename == '.' || $filename == '..' || strpos($filename, '.php') === false) {
        continue;
    }
    
    echo $filename . PHP_EOL;
    
    $result = include 'data/datasets/' . $filename;
    $userAgents = $result['userAgents'];
    $source = $result['source'];
    $group = $result['group'];
    
    $insert = "
            INSERT INTO userAgent (`uaId`, userAgent, source, `group`)
            VALUES (:uaId, :userAgent, :source, :group)
        ";
    $stmt = $pdo->prepare($insert);
    
    // @todo replace id with real UUID!
    foreach ($userAgents as $userAgent) {
        $stmt->bindValue(':uaId', $ourId);
        $stmt->bindValue(':userAgent', $userAgent);
        $stmt->bindValue(':source', $source);
        $stmt->bindValue(':group', $group);
        
        $stmt->execute();
        
        $ourId ++;
    }
}
