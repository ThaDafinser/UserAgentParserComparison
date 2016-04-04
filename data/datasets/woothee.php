<?php
use Symfony\Component\Yaml\Yaml;

function hydrateWoothee($data, array $row)
{
    $data['resRawResult'] = serialize($row);
    
    if (isset($row['category']) && $row['category'] == 'crawler') {
        $data['resBotIsBot'] = 1;
        $data['resBotName'] = $row['name'];
        
        return $data;
    }
    
    if (isset($row['name']) && $row['name'] != '') {
        $data['resBrowserName'] = $row['name'];
    }
    if (isset($row['version']) && $row['version'] != '') {
        $data['resBrowserVersion'] = $row['version'];
    }
    
    if (isset($row['os']) && $row['os'] != '') {
        $data['resOsName'] = $row['os'];
    }
    if (isset($row['os_version']) && $row['os_version'] != '') {
        $data['resOsVersion'] = $row['os_version'];
    }
    
    if (isset($row['category']) && $row['category'] != '') {
        $data['resDeviceType'] = $row['category'];
    }
    
    return $data;
}

$path = 'vendor/woothee/woothee-testset/testsets';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);

$userAgents = [];

foreach ($files as $file) {
    
    $file = $file[0];
    
    $fixtureData = Yaml::parse(file_get_contents($file));
    
    if (! is_array($fixtureData)) {
        throw new \Exception('wrong result!');
    }
    
    foreach ($fixtureData as $row) {
        
        $data = [
            'resFilename' => $file,
            
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
        
        if (! isset($row['target']) || $row['target'] == '') {
            continue;
        }
        
        $result = hydrateWoothee($data, $row);
        
        $userAgents[bin2hex(sha1($row['target'], true))] = [
            'uaString' => $row['target'],
            'result' => $result
        ];
    }
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'Woothee',
        'proPackageName' => 'woothee/woothee-testset',
        'proHomepage' => 'https://github.com/woothee/woothee',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 0,
        'proCanDetectEngineVersion' => 0,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 0,
        'proCanDetectDeviceBrand' => 0,
        'proCanDetectDeviceType' => 1,
        'proCanDetectDeviceIsMobile' => 0,
        'proCanDetectDeviceIsTouch' => 0,
        
        'proCanDetectBotIsBot' => 1,
        'proCanDetectBotName' => 1,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => $userAgents
];
