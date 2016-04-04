<?php
use UserAgentParserComparison\Html\UserAgentDetail;

/*
 * Generate a detail page for each user agent
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$userAgentRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\UserAgent');
$resultRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Result');

echo 'load agents...' . PHP_EOL;

/*
 * load userAgents...
 */
echo 'done loading..' . PHP_EOL;

foreach ($userAgentRepo->findAll()) as $key => $userAgent) {
    /* @var $userAgent \UserAgentParserComparison\Entity\UserAgent */
    
    $qb = $resultRepo->createQueryBuilder('result');
    $qb->join('result.provider', 'provider');
    $qb->join('result.userAgent', 'userAgent');
    
    $qb->where('result.userAgent = :userAgent');
    $qb->setParameter(':userAgent', $userAgent->id);
    
    $qb->orderBy('provider.name');
    
    $results = $qb->getQuery()->getResult();
    
    if (count($results) === 0) {
        throw new \Exception('no results found...' . $qb->getQuery()->getSQL());
    }
    
    $generate = new UserAgentDetail($entityManager);
    $generate->setTitle('User agent detail - ' . $userAgent->string);
    $generate->setUserAgent($userAgent);
    $generate->setResults($results);
    
    /*
     * create the folder
     */
    $folder = $basePath . '/user-agent-detail/' . substr($userAgent->id, 0, 2) . '/' . substr($userAgent->id, 2, 2);
    if (! file_exists($folder)) {
        mkdir($folder, null, true);
    }
    
    /*
     * persist!
     */
    file_put_contents($folder . '/' . $userAgent->id . '.html', $generate->getHtml());
    
    if ($key % 100 === 0) {
        $entityManager->clear();
    }
    
    echo '.';
}
