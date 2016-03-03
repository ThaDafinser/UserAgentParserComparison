<?php
use UserAgentParserComparison\Entity\Provider;

include_once 'bootstrap.php';

/* @var $entityManager \Doctrine\ORM\EntityManager */

/* @var $chain \UserAgentParser\Provider\Chain */
$chain = include 'bin/getChainProvider.php';

foreach ($chain->getProviders() as $provider) {
    /* @var $provider \UserAgentParser\Provider\AbstractProvider */
    
    $providerRepo = $entityManager->getRepository('UserAgentParserComparison\Entity\Provider');
    
    $providerEntity = $providerRepo->findOneBy([
        'name' => $provider->getName()
    ]);
    if ($providerEntity === null) {
        $providerEntity = new Provider();
    }
    
    $providerEntity->type = 'real';
    $providerEntity->name = $provider->getName();
    $providerEntity->packageName = $provider->getPackageName();
    $providerEntity->homepage = $provider->getHomepage();
    $providerEntity->version = $provider->getVersion();
    
    /*
     * capabilities
     */
    $capabilities = $provider->getDetectionCapabilities();
    
    $providerEntity->canDetectBrowserName = $capabilities['browser']['name'];
    $providerEntity->canDetectBrowserVersion = $capabilities['browser']['version'];
    
    $providerEntity->canDetectEngineName = $capabilities['renderingEngine']['name'];
    $providerEntity->canDetectEngineVersion = $capabilities['renderingEngine']['version'];
    
    $providerEntity->canDetectOsName = $capabilities['operatingSystem']['name'];
    $providerEntity->canDetectOsVersion = $capabilities['operatingSystem']['version'];
    
    $providerEntity->canDetectDeviceModel = $capabilities['device']['model'];
    $providerEntity->canDetectDeviceBrand = $capabilities['device']['brand'];
    $providerEntity->canDetectDeviceType = $capabilities['device']['type'];
    $providerEntity->canDetectDeviceIsMobile = $capabilities['device']['isMobile'];
    $providerEntity->canDetectDeviceIsTouch = $capabilities['device']['isTouch'];
    
    $providerEntity->canDetectBotIsBot = $capabilities['bot']['isBot'];
    $providerEntity->canDetectBotName = $capabilities['bot']['name'];
    $providerEntity->canDetectBotType = $capabilities['bot']['type'];
    
    $entityManager->persist($providerEntity);
}

$entityManager->flush();
