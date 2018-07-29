<?php

namespace Application\Utils\DbConsts;

class DbVotes
{
    const TABLE = 'votes';
    const __ID = '__Id';
    const PAGEID = 'PageId';
    const USERID = 'UserId';
    const VALUE = 'Value';
    const DATETIME = 'DateTime';
    const DELTAFROMPREV = 'DeltaFromPrev';


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
