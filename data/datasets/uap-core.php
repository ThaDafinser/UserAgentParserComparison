<?php

use Symfony\Component\Yaml\Yaml;
function hydrateUapCore(array $data, array $row, $type)
{
    $data['resRawResult'] = serialize($row);
    
    if ($type == 'os') {
        
        if ($row['family'] == 'Other') {
            throw new \Exception('skip...');
        }
        
        $data['resOsName'] = $row['family'];
        
        if ($row['major'] != '') {
            $version = $row['major'];
            if ($row['minor'] != '') {
                $version .= '.' . $row['minor'];
                
                if ($row['patch'] != '') {
                    $version .= '.' . $row['patch'];
                    
                    if ($row['patch_minor'] != '') {
                        $version .= '.' . $row['patch_minor'];
                    }
                }
            }
            
            $data['resOsVersion'] = $version;
        }
        
        return $data;
    }
    
    if ($type == 'browser') {
        
        $data['resBrowserName'] = $row['family'];
        
        if ($row['major'] != '') {
            $version = $row['major'];
            if ($row['minor'] != '') {
                $version .= '.' . $row['minor'];
                
                if ($row['patch'] != '') {
                    $version .= '.' . $row['patch'];
                }
            }
            
            $data['resBrowserVersion'] = $version;
        }
        
        return $data;
    }
    
    if ($type == 'device') {
        
        if ($row['family'] == 'Spider') {
            $data['resBotIsBot'] = 1;
            
            return $data;
        }
        
        if ($row['brand'] != '') {
            $data['resDeviceBrand'] = $row['brand'];
        }
        if ($row['model'] != '') {
            $data['resDeviceModel'] = $row['model'];
        }
        
        return $data;
    }
    
    throw new \Exception('unknown type: ' . $type);
}


/*
 * Fixtures 1)
 */
$path = 'vendor/thadafinser/uap-core/tests';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);

$userAgents = [];

/*
 * UA (browser)
 */
$file = $path . '/test_ua.yaml';

$fixtureData = Yaml::parse(file_get_contents($file));

if (! is_array($fixtureData) || ! isset($fixtureData['test_cases'])) {
    throw new \Exception('wrong result!');
}

foreach ($fixtureData['test_cases'] as $row) {
    
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
    
    try {
        $result = hydrateUapCore($data, $row, 'browser');
        
        $userAgents[bin2hex(sha1($row['user_agent_string'], true))] = [
            'uaString' => $row['user_agent_string'],
            'result' => $result
        ];
    } catch (\Exception $ex) {
        // skip this UA
        echo 'S';
    }
}

/*
 * OS
 */
$file = $path . '/test_os.yaml';

$fixtureData = Yaml::parse(file_get_contents($file));

if (! is_array($fixtureData) || ! isset($fixtureData['test_cases'])) {
    throw new \Exception('wrong result!');
}

foreach ($fixtureData['test_cases'] as $row) {
    
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
    
    try {
        $result = hydrateUapCore($data, $row, 'os');
        
        $userAgents[bin2hex(sha1($row['user_agent_string'], true))] = [
            'uaString' => $row['user_agent_string'],
            'result' => $result
        ];
    } catch (\Exception $ex) {
        // skip this UA
        echo 'S';
    }
}

/*
 * Device
 */
$file = $path . '/test_device.yaml';

$fixtureData = Yaml::parse(file_get_contents($file));

if (! is_array($fixtureData) || ! isset($fixtureData['test_cases'])) {
    throw new \Exception('wrong result!');
}

foreach ($fixtureData['test_cases'] as $row) {
    
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
    
    try {
        $result = hydrateUapCore($data, $row, 'device');
        
        $userAgents[bin2hex(sha1($row['user_agent_string'], true))] = [
            'uaString' => $row['user_agent_string'],
            'result' => $result
        ];
    } catch (\Exception $ex) {
        // skip this UA
        echo 'S';
    }
}

return [
    'provider' => [
        'proType' => 'testSuite',
        'proName' => 'UAParser',
        'proPackageName' => 'thadafinser/uap-core',
        'proHomepage' => 'https://github.com/ua-parser/uap-core',
        
        'proCanDetectBrowserName' => 1,
        'proCanDetectBrowserVersion' => 1,
        
        'proCanDetectEngineName' => 0,
        'proCanDetectEngineVersion' => 0,
        
        'proCanDetectOsName' => 1,
        'proCanDetectOsVersion' => 1,
        
        'proCanDetectDeviceModel' => 1,
        'proCanDetectDeviceBrand' => 1,
        'proCanDetectDeviceType' => 0,
        'proCanDetectDeviceIsMobile' => 0,
        'proCanDetectDeviceIsTouch' => 0,
        
        'proCanDetectBotIsBot' => 1,
        'proCanDetectBotName' => 0,
        'proCanDetectBotType' => 0
    ],
    
    'userAgents' => $userAgents
];
