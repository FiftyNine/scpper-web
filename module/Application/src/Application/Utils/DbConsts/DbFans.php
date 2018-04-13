<?php

namespace Application\Utils\DbConsts;

class DbFans
{
    const TABLE = 'fans';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const USERID = 'UserId';
    const AUTHORID = 'AuthorId';
    const POSITIVE = 'Positive';
    const NEGATIVE = 'Negative';


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
