<?php
use DeviceDetector\Parser\Device\DeviceParserAbstract;

function hydratePiwik($data, array $row)
{
    $data['uaString'] = $row['user_agent'];
    $data['uaRawResult'] = serialize($row);
    
    /*
     * Dirty here, because it's a magic method in piwik
     */
    if (isset($data['uaBotName']) || isset($data['uaBotType'])) {
        $data['uaBotIsBot'] = 1;
        
        if (isset($row['bot']['name']) && $row['bot']['name'] != '') {
            $data['uaBotName'] = $row['bot']['name'];
        }
        
        if (isset($row['bot']['category']) && $row['bot']['category'] != '') {
            $data['uaBotType'] = $row['bot']['category'];
        }
        
        return $data;
    }
    
    if (isset($row['client']['name']) && $row['client']['name'] != '') {
        $data['uaBrowserName'] = $row['client']['name'];
    }
    
    if (isset($row['client']['version']) && $row['client']['version'] != '') {
        $data['uaBrowserVersion'] = $row['client']['version'];
    }
    
    if (isset($row['client']['engine']) && $row['client']['engine'] != '') {
        $data['uaEngineName'] = $row['client']['engine'];
    }
    
    if (isset($row['os']['name']) && $row['os']['name'] != '') {
        $data['uaOsName'] = $row['os']['name'];
    }
    
    if (isset($row['os']['version']) && $row['os']['version'] != '') {
        $data['uaOsVersion'] = $row['os']['version'];
    }
    
    if (isset($row['device']['model']) && $row['device']['model'] != '') {
        $data['uaDeviceModel'] = $row['device']['model'];
    }
    
    if (isset($row['device']['brand']) && $row['device']['brand'] != '') {
        $data['uaDeviceBrand'] = DeviceParserAbstract::$deviceBrands[$row['device']['brand']];
    }
    
    if (isset($row['device']['type']) && $row['device']['type'] != '') {
        $data['uaDeviceType'] = $row['device']['type'];
    }
    
    return $data;
}

/*
 * Fixtures 1) all expect bots.yml
 */
$path = 'vendor/piwik/device-detector/Tests/fixtures';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yml$/i', RecursiveRegexIterator::GET_MATCH);

$allData = [];

foreach ($files as $file) {
    $file = $file[0];
    
    if (basename($file) == 'bots.yml') {
        echo '  skip ' . $file . PHP_EOL;
        continue;
    }
    
    $fixtureData = \Spyc::YAMLLoad($file);
    
    if (! is_array($fixtureData)) {
        throw new \Exception('wrong result!');
    }
    
    foreach ($fixtureData as $row) {
        
        $data = [
            'uaSource' => 'piwik/device-detector',
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
            'uaBotType' => null,
        ];
        
        $allData[] = hydratePiwik($data, $row);
    }
}

/*
 * Fixtures 2) bots.yml only
 */
$path = 'vendor/piwik/device-detector/Tests/Parser/fixtures';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.yml$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $file = $file[0];
    
    if (basename($file) != 'bots.yml') {
        echo '  skip ' . $file . PHP_EOL;
        continue;
    }
    
    $fixtureData = \Spyc::YAMLLoad($file);
    
    if (! is_array($fixtureData)) {
        throw new \Exception('wrong result!');
    }
    
    foreach ($fixtureData as $row) {
        
        $data = [
            'uaSource' => 'piwik/device-detector',
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
            'uaBotType' => null,
        ];
        
        $allData[] = hydratePiwik($data, $row);
    }
}

return $allData;
