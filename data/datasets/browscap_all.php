<?php
$path = 'vendor/browscap/browscap/tests/fixtures/issues';

function hydrateBrowscap($data, array $row)
{
    $data['uaString'] = $row[0];
    $data['uaRawResult'] = serialize($row[1]);
    
    $row = $row[1];
    
    if (isset($row['Crawler']) && $row['Crawler'] === true) {
        $data['uaBotIsBot'] = 1;
        
        if (isset($row['Browser']) && $row['Browser'] != '') {
            $data['uaBotName'] = $row['Browser'];
        }
        
        if (isset($row['Browser_Type']) && $row['Browser_Type'] != '') {
            $data['uaBotType'] = $row['Browser_Type'];
        }
        
        return $data;
    }
    
    if (isset($row['Browser']) && $row['Browser'] != '') {
        $data['uaBrowserName'] = $row['Browser'];
    }
    
    if (isset($row['Version']) && $row['Version'] != '') {
        $data['uaBrowserVersion'] = $row['Version'];
    }
    
    if (isset($row['RenderingEngine_Name']) && $row['RenderingEngine_Name'] != '') {
        $data['uaEngineName'] = $row['RenderingEngine_Name'];
    }
    
    if (isset($row['RenderingEngine_Version']) && $row['RenderingEngine_Version'] != '') {
        $data['uaEngineName'] = $row['RenderingEngine_Version'];
    }
    
    if (isset($row['Platform']) && $row['Platform'] != '') {
        $data['uaOsName'] = $row['Platform'];
    }
    
    if (isset($row['Platform_Version']) && $row['Platform_Version'] != '') {
        $data['uaOsVersion'] = $row['Platform_Version'];
    }
    
    if (isset($row['Device_Name']) && $row['Device_Name'] != '') {
        $data['uaDeviceModel'] = $row['Device_Name'];
    }
    if (isset($row['Device_Brand_Name']) && $row['Device_Brand_Name'] != '') {
        $data['uaDeviceBrand'] = $row['Device_Brand_Name'];
    }
    if (isset($row['Device_Type']) && $row['Device_Type'] != '') {
        $data['uaDeviceType'] = $row['Device_Type'];
    }
    if (isset($row['isMobileDevice']) && $row['isMobileDevice'] != '') {
        $data['uaDeviceIsMobile'] = $row['isMobileDevice'];
    }
    if (isset($row['Device_Pointing_Method']) && $row['Device_Pointing_Method'] == 'touchscreen') {
        $data['uaDeviceIsTouch'] = 1;
    }
    
    return $data;
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$allData = [];

foreach ($files as $file) {
    $file = $file[0];
    
    $result = include $file;
    
    if (! is_array($result)) {
        throw new \Exception($file . ' did not return an array!');
    }
    
    foreach ($result as $row) {
        
        $data = [
            'uaSource' => 'browscap/browscap',
            'uaFileName' => $file
        ];
        
        $allData[] = hydrateBrowscap($data, $row);
    }
    
}

return $allData;
