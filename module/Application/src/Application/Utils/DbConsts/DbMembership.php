<?php

namespace Application\Utils\DbConsts;

class DbMembership
{
    const TABLE = 'membership';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const USERID = 'UserId';
    const JOINDATE = 'JoinDate';
    const SUMMARYRATING = 'SummaryRating';
    const ADJUSTEDRATING = 'AdjustedRating';
    const ADJUSTEDWEIGHT = 'AdjustedWeight';
    const CLEANRATING = 'CleanRating';


    static public function hasField($field) 
    {
        if (!is_string($field)) {
            return false;
        }
        $field = strtoupper($field);
        $reflect = new \ReflectionClass(__CLASS__);
        foreach ($reflect->getConstants() as $name => $value) {
            if (strtoupper($value) === $field) {
                return true;
            }
        };
        return false;
    }
}
