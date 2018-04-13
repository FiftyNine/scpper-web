<?php

namespace Application\Utils\DbConsts;

class DbViewFans
{
    const TABLE = 'view_fans';
    const SITEID = 'SiteId';
    const USERID = 'UserId';
    const USERNAME = 'UserName';
    const USERDISPLAYNAME = 'UserDisplayName';
    const USERDELETED = 'UserDeleted';
    const AUTHORID = 'AuthorId';
    const AUTHORNAME = 'AuthorName';
    const AUTHORDISPLAYNAME = 'AuthorDisplayName';
    const AUTHORDELETED = 'AuthorDeleted';
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
