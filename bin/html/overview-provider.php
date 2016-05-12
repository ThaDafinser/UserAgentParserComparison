<?php
use UserAgentParserComparison\Html\OverviewProvider;

/*
 * Generate a page for each provider
 */
include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */
$conn = $entityManager->getConnection();

$providerRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Provider');

foreach ($providerRepo->findBy(['type' => 'real']) as $provider) {
    /* @var $provider \UserAgentParserComparison\Entity\Provider */
    
    echo $provider->name . PHP_EOL;
    
    $generate = new OverviewProvider($entityManager, $provider);
    $generate->setTitle('Overview - ' . $provider->name);
    
    /*
     * persist!
     */
    $folder = $basePath;
    if (! file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    file_put_contents($folder . '/' . $provider->name . '.html', $generate->getHtml());
}
