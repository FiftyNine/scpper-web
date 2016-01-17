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
class Aggregate implements QueryAggregateInterface
{
    const COUNT = 0;
    const SUM = 1;
    const AVERAGE = 2;
    
    const SQL_NAMES = array(
        self::COUNT => 'COUNT',
        self::SUM => 'SUM',
        self::AVERAGE => 'AVERAGE',
    );        
    
    protected $propertyName;
    protected $aggregateType;
    protected $aggregateName;
    protected $group;
    
    /**
     * 
     * @param string $propertyName
     * @param int $aggregateType \Application\Utils\AggregateType
     * @param string $aggregateName
     * @param bool $group
     */
    public function __construct($propertyName, $aggregateType, $aggregateName = '', $group = false) 
    {
        $this->propertyName = $propertyName;
        $this->aggregateType = $aggregateType;
        if ($aggregateName !== '') {
            $this->aggregateName = $aggregateName;            
        } else {
            $this->aggregateName = $propertyName;
        }
        $this->group = $group;
    }
    
    public function getPropertyName()
    {
        if ($this->getAggregateType() === self::COUNT) {
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
        } else if ($this->getAggregateType() !== self::COUNT) {
            return $this->propertyName;
        } else {
            return 'Count';
        }
    }
    
    public function getGroup()
    {
        return $this->group;
    }

    public function getAggregateExpression()
    {
        $sqlAggregate = self::SQL_NAMES[$this->getAggregateType()];
        return new \Zend\Db\Sql\Expression("$sqlAggregate({$this->getPropertyName()})");
    }    
}
