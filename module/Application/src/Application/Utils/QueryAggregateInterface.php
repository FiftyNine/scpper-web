<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 *
 * @author Alexander
 */
interface QueryAggregateInterface 
{
    /**
     * Property for aggregation
     * @return string
     */
    public function getPropertyName();
    
    /**
     * Aggregated value name
     * @return string
     */
    public function getAggregateName();
    
    /**
     * Whether to group results based on this property or not
     * @return bool
     */
    public function getGroup();
    
    /**
     * SQL expression for aggregation
     * @return \Zend\Db\Sql\Expression
     */
    public function getAggregateExpression();
}
