<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of DateGroupType
 *
 * @author Alexander
 */
class DateGroupType 
{
    const DAY = 0;
    const WEEK = 1;
    const MONTH = 2;
    const YEAR = 3;
    
    const DAY_NAME = 'day';
    const WEEK_NAME = 'week';
    const MONTH_NAME = 'month';
    const YEAR_NAME = 'year';
    
    public static function getBestGroupType(\DateTime $fromDate, \DateTime $toDate)
    {
        $diff = $toDate->diff($fromDate, true);
        if ($diff->days < 60) {
            $group = DateGroupType::DAY;
        } else if ($diff->days < 365) {
            $group = DateGroupType::WEEK;
        } else if ($diff->days < 365*10) {
            $group = DateGroupType::MONTH;
        } else {
            $group = DateGroupType::YEAR;
        }
        return $group;
    }
    
    public static function getSqlGroupString($group, $fieldName)
    {
        switch ($group) {
            case self::YEAR:
                $result = 'MAKEDATE(YEAR('.$fieldName.'), 1)';
                break;
            case self::MONTH:
                $result = 'DATE_SUB(DATE('.$fieldName.'), INTERVAL DAYOFMONTH('.$fieldName.') DAY)';
                break;
            case self::WEEK:
                $result = 'DATE_SUB(DATE('.$fieldName.'), INTERVAL WEEKDAY('.$fieldName.') DAY)';
                break;
            default:
                $result = 'DATE('.$fieldName.')';
        }         
        return $result;
    }
    
    public static function getGroupName($group)
    {
        switch ($group) {
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
