<?php
namespace UserAgentParserComparison\Harmonize;

abstract class AbstractHarmonize
{

    public static function getHarmonizedValue($value)
    {
        foreach (static::$replaces as $replace => $searches) {
            $value = str_ireplace($searches, $replace, $value);
        }
        
        return $value;
    }

    public static function getHarmonizedValues(array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = self::getHarmonizedValue($value);
        }
        
        return $values;
    }
}