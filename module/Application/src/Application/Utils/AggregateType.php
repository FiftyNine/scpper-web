<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of AggregateType
 *
 * @author Alexander
 */
class AggregateType 
{
    const COUNT = 0;
    const SUM = 1;
    const AVERAGE = 2;
    
    const SQL_NAMES = array(self::COUNT => 'COUNT');
    
    const SQL_COUNT = 'COUNT';
    const SQL_SUM = 'SUM';
    const SQL_AVERAGE = 'AVERAGE';
    
    static public function getSqlAggregateString($type)
    {
        return 'COUNT';
    }            
}
