<?php

function hydrateWoothee($data, array $row)
{
    $data['uaString'] = $row['target'];
    $data['uaRawResult'] = serialize($row);
    
    if (isset($row['category']) && $row['category'] == 'crawler') {
        $data['uaBotIsBot'] = 1;
        $data['uaBotName'] = $row['name'];
        
        return $data;
    }
    
    if (isset($row['name']) && $row['name'] != '') {
        $data['uaBrowserName'] = $row['name'];
    }
    
    if (isset($row['version']) && $row['version'] != '') {
        $data['uaBrowserVersion'] = $row['version'];
    }
    
    if (isset($row['os']) && $row['os'] != '') {
        $data['uaOsName'] = $row['os'];
    }
    
    if (isset($row['os_version']) && $row['os_version'] != '') {
        $data['uaOsVersion'] = $row['os_version'];
    }
    
    if (isset($row['category']) && $row['category'] != '') {
        $data['uaDeviceType'] = $row['category'];
    }
    
    return $data;
}

$path = 'vendor/woothee/woothee-testset/testsets';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yaml$/i', RecursiveRegexIterator::GET_MATCH);

$allData = [];

foreach ($files as $file) {
    $file = $file[0];
    
    $fixtureData = \Spyc::YAMLLoad($file);
    
    if (! is_array($fixtureData)) {
        throw new \Exception('wrong result!');
    }
    
    foreach ($fixtureData as $row) {
        
        $data = [
            'uaSource' => 'woothee/woothee-testset',
            'uaFileName' => $file,
            
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
        
        if(!isset($row['target']) || $row['target'] == ''){
            continue;
        }
        
        $allData[] = hydrateWoothee($data, $row);
    }
}

return $allData;
