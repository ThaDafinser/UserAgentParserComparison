<?php
namespace UserAgentParserComparison\Harmonize;

class Version extends AbstractHarmonize
{
    
    /**
     * Only compare the major and minor version!
     */
    public static function getHarmonizedValue($value)
    {
        preg_match("/\d+(?:\.*\d*)[1,2]*/", $value, $result);
        
        if (! isset($result[0])) {
            return $value;
        }
        
        $useValue = $result[0];
        
        if (stripos($useValue, '.') === false) {
            $useValue .= '.0';
        }
        
        return $useValue;
    }

    /**
     * Only compare the major and minor version!
     */
    public static function getHarmonizedValues(array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = self::getHarmonizedValue($value);
        }
        
        return $values;
    }
}
