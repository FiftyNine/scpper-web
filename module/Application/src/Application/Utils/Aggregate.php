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
    const NONE = 0;
    const COUNT = 1;
    const SUM = 2;
    const AVERAGE = 3;
    const MIN = 4;    
    const MAX = 5;    
    
    const SQL_NAMES = array(
        self::NONE => '',
        self::COUNT => 'COUNT',
        self::SUM => 'SUM',
        self::AVERAGE => 'AVG',
        self::MIN => 'MIN',
        self::MAX => 'MAX'
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
    public function __construct($propertyName, $aggregateType, $aggregateName = null, $group = false) 
    {
        $this->propertyName = $propertyName;
        $this->aggregateType = $aggregateType;
        $this->aggregateName = $aggregateName;
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
        return $this->aggregateName;
    }
    
    public function getGroup()
    {
        return $this->group;
    }

    public function getAggregateExpression()
    {
        $sqlAggregate = self::SQL_NAMES[$this->getAggregateType()];
        if ($sqlAggregate) {
            return new \Zend\Db\Sql\Expression("$sqlAggregate({$this->getPropertyName()})");
        } else {
            return $this->getPropertyName();
        }
    }    
}
