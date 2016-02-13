<?php

function hydrateUapCore(array $data, array $row, $type)
{
    $data['uaRawResult'] = serialize($row);
    
    if ($type == 'os') {
        if ($row['family'] == 'Other') {
            throw new \Exception('skip...');
        }
        
        $data['uaOsName'] = $row['family'];
        
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
            
            $data['uaOsVersion'] = $version;
        }
        
        return $data;
    }
    
    if ($type == 'browser') {
        $data['uaBrowserName'] = $row['family'];
        
        if ($row['major'] != '') {
            $version = $row['major'];
            if ($row['minor'] != '') {
                $version .= '.' . $row['minor'];
                
                if ($row['patch'] != '') {
                    $version .= '.' . $row['patch'];
                }
            }
            
            $data['uaBrowserVersion'] = $version;
        }
        
        return $data;
    }
    
    if ($type == 'device') {
        
        if ($row['family'] == 'Spider') {
            $data['uaBotIsBot'] = 1;
            
            return $data;
        }
        
        if ($row['brand'] != '') {
            $data['uaDeviceBrand'] = $row['brand'];
        }
        if ($row['model'] != '') {
            $data['uaDeviceModel'] = $row['model'];
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

$allData = [];

/*
 * UA (browser)
 */
$file = $path . '/test_ua.yaml';

$fixtureData = \Spyc::YAMLLoad($file);

if (! is_array($fixtureData) || ! isset($fixtureData['test_cases'])) {
    throw new \Exception('wrong result!');
}

foreach ($fixtureData['test_cases'] as $row) {
    
    $data = [
        'uaSource' => 'ua-parser/uap-core',
        'uaFileName' => $file,
        
        'uaString' => $row['user_agent_string'],
        
        'uaBrowserName' => null,
        'uaBrowserVersion' => null,
        
        'uaEngineName' => null,
        'uaEngineVersion' => null,
        
        'uaOsName' => null,
        'uaOsVersion' => null,
        
        'uaDeviceModel' => null,
        'uaDeviceBrand' => null,
        'uaDeviceType' => null,
        'uaDeviceIsMobile' => null,
        'uaDeviceIsTouch' => null,
        
        'uaBotIsBot' => null,
        'uaBotName' => null,
        'uaBotType' => null
    ];
    
    try {
        $data = hydrateUapCore($data, $row, 'browser');
        
        $allData[] = $data;
    } catch (\Exception $ex) {
        // skip this UA
        echo 'S';
    }
}

/*
 * OS
 */
$file = $path . '/test_os.yaml';

$fixtureData = \Spyc::YAMLLoad($file);

if (! is_array($fixtureData) || ! isset($fixtureData['test_cases'])) {
    throw new \Exception('wrong result!');
}

foreach ($fixtureData['test_cases'] as $row) {
    
    $data = [
        'uaSource' => 'ua-parser/uap-core',
        'uaFileName' => $file,
        
        'uaString' => $row['user_agent_string'],
        
        'uaBrowserName' => null,
        'uaBrowserVersion' => null,
        
        'uaEngineName' => null,
        'uaEngineVersion' => null,
        
        'uaOsName' => null,
        'uaOsVersion' => null,
        
        'uaDeviceModel' => null,
        'uaDeviceBrand' => null,
        'uaDeviceType' => null,
        'uaDeviceIsMobile' => null,
        'uaDeviceIsTouch' => null,
        
        'uaBotIsBot' => null,
        'uaBotName' => null,
        'uaBotType' => null
    ];
    
    try {
        $data = hydrateUapCore($data, $row, 'os');
        
        $allData[] = $data;
    } catch (\Exception $ex) {
        // skip this UA
        echo 'S';
    }
}

/*
 * Device
 */
$file = $path . '/test_device.yaml';

$fixtureData = \Spyc::YAMLLoad($file);

if (! is_array($fixtureData) || ! isset($fixtureData['test_cases'])) {
    throw new \Exception('wrong result!');
}

foreach ($fixtureData['test_cases'] as $row) {
    
    $data = [
        'uaSource' => 'ua-parser/uap-core',
        'uaFileName' => $file,
        
        'uaString' => $row['user_agent_string'],
        
        'uaBrowserName' => null,
        'uaBrowserVersion' => null,
        
        'uaEngineName' => null,
        'uaEngineVersion' => null,
        
        'uaOsName' => null,
        'uaOsVersion' => null,
        
        'uaDeviceModel' => null,
        'uaDeviceBrand' => null,
        'uaDeviceType' => null,
        'uaDeviceIsMobile' => null,
        'uaDeviceIsTouch' => null,
        
        'uaBotIsBot' => null,
        'uaBotName' => null,
        'uaBotType' => null
    ];
    
    try {
        $data = hydrateUapCore($data, $row, 'device');
        
        $allData[] = $data;
    } catch (\Exception $ex) {
        // skip this UA
        echo 'S';
    }
}

return $allData;
