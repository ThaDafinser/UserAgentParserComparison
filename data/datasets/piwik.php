<?php
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Symfony\Component\Yaml\Yaml;

function hydratePiwik($data, array $row)
{
    $data['resRawResult'] = serialize($row);
    
    /*
     * Dirty here, because it's a magic method in piwik
     */
    if (isset($row['bot']['name']) || isset($row['bot']['category'])) {
        $data['resBotIsBot'] = 1;
        
        if (isset($row['bot']['name']) && $row['bot']['name'] != '') {
            $data['resBotName'] = $row['bot']['name'];
        }
        
        if (isset($row['bot']['category']) && $row['bot']['category'] != '') {
            $data['resBotType'] = $row['bot']['category'];
        }
        
        return $data;
    }
    
    if (isset($row['client']['name']) && $row['client']['name'] != '') {
        $data['resBrowserName'] = $row['client']['name'];
    }
    if (isset($row['client']['version']) && $row['client']['version'] != '') {
        $data['resBrowserVersion'] = $row['client']['version'];
    }
    
    if (isset($row['client']['engine']) && $row['client']['engine'] != '') {
        $data['resEngineName'] = $row['client']['engine'];
    }
    
    if (isset($row['os']['name']) && $row['os']['name'] != '') {
        $data['resOsName'] = $row['os']['name'];
    }
    if (isset($row['os']['version']) && $row['os']['version'] != '') {
        $data['resOsVersion'] = $row['os']['version'];
    }
    
    if (isset($row['device']['model']) && $row['device']['model'] != '') {
        $data['resDeviceModel'] = $row['device']['model'];
    }
    if (isset($row['device']['brand']) && $row['device']['brand'] != '') {
        $data['resDeviceBrand'] = DeviceParserAbstract::$deviceBrands[$row['device']['brand']];
    }
    if (isset($row['device']['type']) && $row['device']['type'] != '') {
        $data['resDeviceType'] = $row['device']['type'];
    }
    
    return $data;
}

/*
 * Fixtures 1) all expect bots.yml
 */
$path = 'vendor/piwik/device-detector/Tests/fixtures';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yml$/i', RecursiveRegexIterator::GET_MATCH);

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
            'resBotType' => null,
        ];
        
        $result = hydratePiwik($data, $row);
        
        $userAgents[bin2hex(sha1($row['user_agent'], true))] = [
            'uaString' => $row['user_agent'],
            'result' => $result
        ];
    }
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'PiwikDeviceDetector',
        'proPackageName' => 'piwik/device-detector',
        'proHomepage' => 'https://github.com/piwik/device-detector',

        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,

        'proCanDetectEngineName' => 1,
        'proCanDetectEngineVersion' => 0,

        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,

        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 1,
        'proCanDetectDeviceType' => 1,
        'proCanDetectDeviceIsMobile' => 0,
        'proCanDetectDeviceIsTouch' => 0,

        'proCanDetectBotIsBot' => 1,
        'proCanDetectBotName' => 1,
        'proCanDetectBotType' => 1
    ],

    'userAgents' => $userAgents
];
