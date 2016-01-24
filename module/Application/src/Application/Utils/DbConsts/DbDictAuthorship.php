<?php

namespace Application\Utils\DbConsts;

class DbDictAuthorship
{
    const TABLE = 'dict_authorship';
    const __ID = '__Id';
    const CODEID = 'CodeId';
    const NAME = 'Name';


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
