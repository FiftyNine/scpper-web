<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of Aggregate
 *
 * @author Alexander
 */
class Aggregate 
{
    protected $propertyName;
    protected $aggregateType;
    protected $aggregateName;
    
    public function __construct($propertyName, $aggregateType, $aggregateName) 
    {
        $this->propertyName = $propertyName;
        $this->aggregateType = $aggregateType;
        $this->aggregateName = $aggregateName;
    }
    
    public function getPropertyName()
    {
        if ($this->getAggregateType() === AggregateType::COUNT) {
            return '*';
        } else {
            return $this->propertyName;
        }
    }
    
    public function getAggregateType()
    {
        return $this->aggregateType;                
    }
    
    public function getAggregateName()
    {
        if (isset($this->aggregateName)) {
            return $this->aggregateName;
        } else if ($this->getAggregateType() !== AggregateType::COUNT) {
            return $this->propertyName;
        } else {
            return 'Count';
        }
    }

    public function getAggregateExpression()
    {
        $sqlAggregate = AggregateType::getSqlAggregateString($this->getAggregateType());
        return new \Zend\Db\Sql\Expression("$sqlAggregate({$this->getPropertyName()})");
    }
}
