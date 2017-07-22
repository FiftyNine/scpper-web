<?php

namespace Application\Utils\DbConsts;

class DbScpperUsers
{
    const TABLE = 'scpper_users';
    const ID = 'id';
    const USER = 'user';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const GROUPID = 'groupId';


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
