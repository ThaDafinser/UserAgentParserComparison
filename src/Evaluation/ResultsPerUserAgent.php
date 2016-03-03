<?php
namespace UserAgentParserComparison\Evaluation;

class ResultsPerUserAgent
{

    private $values;

    private $harmonizedValues;

    private $type;

    private $foundCount;

    private $foundCountUnique;

    private $maxSameResultCount;

    private $harmonizedFoundUnique;

    private $harmonizedMaxSameResultCount;

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
        $this->foundCount = count($this->getValues());
        $this->foundCountUnique = $this->getUniqueCount($this->getValues());
        $this->maxSameResultCount = $this->getMaxSameCount($this->getValues());
        
        $harmonizedValues = $this->getHarmonizedValues();
        
        $this->harmonizedFoundUnique = $this->getUniqueCount($harmonizedValues);
        $this->harmonizedMaxSameResultCount = $this->getMaxSameCount($harmonizedValues);
    }

    protected function getHarmonizedValues()
    {
        if ($this->harmonizedValues !== null) {
            return $this->harmonizedValues;
        }
        
        $class = '\UserAgentParserComparison\Harmonize\\' . ucfirst($this->getType());
        
        $this->harmonizedValues =  $class::getHarmonizedValues($this->getValues());
        
        return $this->harmonizedValues;
    }
    
    public function getUniqueHarmonizedValues()
    {
        return array_unique($this->getHarmonizedValues());
    }

    private function getUniqueCount(array $values)
    {
        return count(array_unique($values));
    }

    private function getMaxSameCount(array $values)
    {
        if (count($values) === 0) {
            return 0;
        }
        
        $count = array_count_values($values);
        
        return max($count);
    }

    public function getFoundCount()
    {
        return $this->foundCount;
    }

    public function getFoundCountUnique()
    {
        return $this->foundCountUnique;
    }

    public function getMaxSameResultCount()
    {
        return $this->maxSameResultCount;
    }

    public function getHarmonizedFoundUnique()
    {
        return $this->harmonizedFoundUnique;
    }

    public function getHarmonizedMaxSameResultCount()
    {
        return $this->harmonizedMaxSameResultCount;
    }
}
