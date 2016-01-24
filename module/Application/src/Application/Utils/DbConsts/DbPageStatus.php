<?php

namespace Application\Utils\DbConsts;

class DbPageStatus
{
    const TABLE = 'page_status';
    const __ID = '__Id';
    const PAGEID = 'PageId';
    const STATUSID = 'StatusId';
    const ORIGINALID = 'OriginalId';
    const FIXED = 'Fixed';


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
