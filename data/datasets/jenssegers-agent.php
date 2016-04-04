<?php
require_once 'vendor/jenssegers/agent/tests/AgentTest.php';

function hydrateJenssegersAgent($userAgent)
{
    return  [
        'resFilename' => 'vendor/jenssegers/agent/tests/AgentTest.php',
        
        'resBrowserName' => null,
        'resBrowserVersion' => null,
        
        'resEngineName' => null,
        'resEngineVersion' => null,
        
        'resOsName' => null,
        'resOsVersion' => null,
        
        'resDeviceModel' => null,
        'resDeviceBrand' => null,
        'resDeviceType' => null,
        'resDeviceIsMobile' => null,
        'resDeviceIsTouch' => null,
        
        'resBotIsBot' => null,
        'resBotName' => null,
        'resBotType' => null
    ];
    
}

$userAgents = [];

$class = new ReflectionClass('AgentTest');

$agentTest = new \AgentTest();

$property = $class->getProperty('operatingSystems');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resOsName'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

$property = $class->getProperty('browsers');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resBrowserName'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

$property = $class->getProperty('robots');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resBotIsBot'] = 1;
    $data['resBotName'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

$property = $class->getProperty('mobileDevices');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resDeviceModel'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

$property = $class->getProperty('desktopDevices');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resDeviceModel'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

$property = $class->getProperty('browserVersions');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resBrowserVersion'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

$property = $class->getProperty('operatingSystemVersions');
$property->setAccessible(true);
foreach ($property->getValue($agentTest) as $userAgent => $value) {
    
    $data = hydrateJenssegersAgent($userAgent);
    $data['resOsVersion'] = $value;
    
    $userAgents[bin2hex(sha1($userAgent, true))] = [
        'uaString' => $userAgent,
        'result' => $data
    ];
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'JenssegersAgent',
        'proPackageName' => 'jenssegers/agent',
        'proHomepage' => 'https://github.com/jenssegers/agent',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 0,
        'proCanDetectEngineVersion' => 0,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 0,
        'proCanDetectDeviceType' => 0,
        'proCanDetectDeviceIsMobile' => 1,
        'proCanDetectDeviceIsTouch' => 1,
        
        'proCanDetectBotIsBot' => 1,
        'proCanDetectBotName' => 1,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => $userAgents
];
