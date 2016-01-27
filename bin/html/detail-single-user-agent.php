<?php
use UserAgentParserComparison\Html\UserAgentDetail;

/*
 * Generate a detail page for each user agent
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$uaEvaluationRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\UserAgentEvaluation');
$resultRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Result');

echo 'load agents...' . PHP_EOL;

/*
 * load userAgents...
 */
$sql = "
    SELECT
        *
    FROM userAgent
    ORDER BY uaId DESC
    LIMIT 9000, 3000
";
$statement = $conn->prepare($sql);
$statement->execute();

echo 'done loading..' . PHP_EOL;

while ($row = $statement->fetch()) {
    
    /* @var $uaEvaluation \UserAgentParserComparison\Entity\UserAgentEvaluation */
    $uaEvaluation = $uaEvaluationRepo->findOneBy([
        'userAgent' => $row['uaId']
    ]);
    
    $qb = $resultRepo->createQueryBuilder('result');
    $qb->join('result.provider', 'provider');
    $qb->join('result.userAgent', 'userAgent');
    $qb->join('result.resultEvaluation', 'resultEvaluation');
    
    $qb->where('result.userAgent = :userAgent');
    $qb->setParameter(':userAgent', $row['uaId']);
    
    $qb->orderBy('provider.name');
    
    $results = $qb->getQuery()->getResult();
    
    if(count($results) === 0){
        throw new \Exception('no results found...'.$qb->getQuery()->getSQL());
    }
    
    $generate = new UserAgentDetail();
    $generate->setTitle($row['uaString']);
    $generate->setUserAgent($row['uaString']);
    $generate->setUserAgentEvaluation($uaEvaluation);
    $generate->setResults($results);
    
    /*
     * create the folder
     */
    $folder = $basePath.'/user-agent-detail/' . substr($row['uaId'], 0, 2) . '/' . substr($row['uaId'], 2, 2);
    if (! file_exists($folder)) {
        mkdir($folder, null, true);
    }
    
    /*
     * persist!
     */
    file_put_contents($folder . '/' . $row['uaId'] . '.html', $generate->getHtml());
    
    echo '.';
}
