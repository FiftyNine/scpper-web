<?php

namespace Application\Utils\DbConsts;

class DbTags
{
    const TABLE = 'tags';
    const __ID = '__Id';
    const PAGEID = 'PageId';
    const TAG = 'Tag';


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
