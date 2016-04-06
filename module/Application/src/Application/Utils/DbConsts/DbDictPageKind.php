<?php

namespace Application\Utils\DbConsts;

class DbDictPageKind
{
    const TABLE = 'dict_page_kind';
    const __ID = '__Id';
    const KINDID = 'KindId';
    const DESCRIPTION = 'Description';


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
