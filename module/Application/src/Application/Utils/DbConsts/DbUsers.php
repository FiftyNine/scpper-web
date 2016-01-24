<?php

namespace Application\Utils\DbConsts;

class DbUsers
{
    const TABLE = 'users';
    const __ID = '__Id';
    const WIKIDOTNAME = 'WikidotName';
    const REGISTRATIONDATE = 'RegistrationDate';
    const WIKIDOTID = 'WikidotId';
    const DELETED = 'Deleted';
    const DISPLAYNAME = 'DisplayName';


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
