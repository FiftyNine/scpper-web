<?php

namespace Application\Utils\DbConsts;

class DbViewUsers
{
    const TABLE = 'view_users';
    const __ID = '__Id';
    const USERID = 'UserId';
    const WIKIDOTNAME = 'WikidotName';
    const DISPLAYNAME = 'DisplayName';
    const REGISTRATIONDATE = 'RegistrationDate';
    const DELETED = 'Deleted';


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
