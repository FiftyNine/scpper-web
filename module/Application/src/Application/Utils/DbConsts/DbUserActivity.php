<?php

namespace Application\Utils\DbConsts;

class DbUserActivity
{
    const TABLE = 'user_activity';
    const __ID = '__Id';
    const USERID = 'UserId';
    const SITEID = 'SiteId';
    const VOTES = 'Votes';
    const REVISIONS = 'Revisions';
    const PAGES = 'Pages';


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
