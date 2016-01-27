<?php
namespace UserAgentParserComparison\Evaluation;

class ResultsPerProviderResult
{

    private $currentValue;

    private $values;

    private $harmonizedValues;

    private $type;

    private $sameResultCount = 0;

    private $harmonizedSameResultCount = 0;

    public function setCurrentValue($currentValue)
    {
        $this->currentValue = $currentValue;
    }

    public function getCurrentValue()
    {
        return $this->currentValue;
    }

    public function setValue($value)
    {
        if ($value === null) {
            $values = [];
        } else {
            $values = explode('~~~', $value);
        }
        
        $this->values = $values;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function evaluate()
    {
        $this->sameResultCount = 0;
        $this->harmonizedSameResultCount = 0;
        
        foreach ($this->getValues() as $value) {
            if ($this->getCurrentValue() == $value) {
                $this->sameResultCount ++;
            }
        }
        
        $class = $this->getHarmonizerClass();
        $harmonizedCurrentValue = $class::getHarmonizedValue($this->getCurrentValue());
        
        foreach ($this->getHarmonizedValues() as $value) {
            if ($harmonizedCurrentValue == $value) {
                $this->harmonizedSameResultCount ++;
            }
        }
    }

    protected function getHarmonizerClass()
    {
        return '\UserAgentParserComparison\Harmonize\\' . ucfirst($this->getType());
    }

    protected function getHarmonizedValues()
    {
        if ($this->harmonizedValues !== null) {
            return $this->harmonizedValues;
        }
        
        $class = $this->getHarmonizerClass();
        
        $this->harmonizedValues = $class::getHarmonizedValues($this->getValues());
        
        return $this->harmonizedValues;
    }

    public function getSameResultCount()
    {
        return $this->sameResultCount;
    }

    public function getHarmonizedSameResultCount()
    {
        return $this->harmonizedSameResultCount;
    }
}
