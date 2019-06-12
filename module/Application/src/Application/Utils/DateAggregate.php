<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of DateAggregate
 *
 * @author Alexander
 */
class DateAggregate implements QueryAggregateInterface
{
    const DAY = 0;
    const WEEK = 1;
    const MONTH = 2;
    const YEAR = 3;
    
    const DAY_NAME = 'day';
    const WEEK_NAME = 'week';
    const MONTH_NAME = 'month';
    const YEAR_NAME = 'year';    
    
    protected $propertyName;
    protected $aggregateType;
    protected $aggregateName;
    protected $group;
    
    public function __construct($propertyName, $aggregateName, $aggregateType = self::DAY) 
    {
        $this->propertyName = $propertyName;        
        $this->aggregateName = $aggregateName;            
        $this->aggregateType = $aggregateType;
    }
    
    public function getAggregateExpression()
    {
        switch ($this->aggregateType) {
            case self::YEAR:
                $result = 'MAKEDATE(YEAR('.$this->propertyName.'), 1)';
                break;
            case self::MONTH:
                $result = 'DATE_SUB(DATE('.$this->propertyName.'), INTERVAL DAYOFMONTH('.$this->propertyName.')-1 DAY)';
                break;
            case self::WEEK:
                $result = 'DATE_SUB(DATE('.$this->propertyName.'), INTERVAL WEEKDAY('.$this->propertyName.')-1 DAY)';
                break;
            default:
                $result = 'DATE('.$this->propertyName.')';
        }         
        return new \Zend\Db\Sql\Expression($result);
    }

    public function getAggregateName() 
    {
        return $this->aggregateName;
    }

    public function getGroup() 
    {
        return true;
    }

    public function getPropertyName() 
    {
        return $this->propertyName;
    }
    
    public function getAggregateType()
    {
        return $this->aggregateType;
    }
    
    public function setAggregateType($value)
    {
        $this->aggregateType = $value;
    }
    
    public function setBestAggregateType(\DateTime $fromDate, \DateTime $toDate)
    {
        $diff = $toDate->diff($fromDate, true);
        if ($diff->days < 60) {
            $type = self::DAY;
        } else if ($diff->days < 365) {
            $type = self::WEEK;
        } else if ($diff->days < 365*10) {
            $type = self::MONTH;
        } else {
            $type = self::YEAR;
        }
        $this->setAggregateType($type);
    }
    
    public function getAggregateDescription()
    {
        switch ($this->getAggregateType()) {
            case self::YEAR:
                return self::YEAR_NAME;
            case self::MONTH:
                return self::MONTH_NAME;
            case self::WEEK:
                return self::WEEK_NAME;
            default:
                return self::DAY_NAME;
        }
    }
    
}
