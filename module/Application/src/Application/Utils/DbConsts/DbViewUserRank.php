<?php

namespace Application\Utils\DbConsts;

class DbViewUserRank
{
    const TABLE = 'view_user_rank';
    const USERID = 'UserId';
    const DISPLAYNAME = 'DisplayName';
    const SITEID = 'SiteId';
    const TOTAL = 'Total';


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
