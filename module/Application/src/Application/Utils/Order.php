<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of Order
 *
 * @author Alexander
 */
class Order 
{
    const ASCENDING = 0;
    const DESCENDING = 1;
    
    static public function getTypeSql($type) {
        switch ($type) {
            case self::ASCENDING:
                return 'ASC';
            case self::DESCENDING:
                return 'DESC';
            default:
                return 'ASC';
        }
    }
}
