<?php
use UserAgentParserComparison\Html\OverviewProvider;

/*
 * Generate a page for each provider
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$providerRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Provider');

$providers = $providerRepo->findAll();

foreach ($providers as $provider) {
    /* @var $provider \UserAgentParserComparison\Entity\Provider */
    
    echo $provider->name . PHP_EOL;
    
    $generate = new OverviewProvider($entityManager, $provider);
    $generate->setTitle('Overview - ' . $provider->name);
    
    /*
     * persist!
     */
    file_put_contents($basePath . '/' . $provider->name . '.html', $generate->getHtml());
}