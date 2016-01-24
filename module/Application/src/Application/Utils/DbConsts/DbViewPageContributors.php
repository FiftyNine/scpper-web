<?php

namespace Application\Utils\DbConsts;

class DbViewPageContributors
{
    const TABLE = 'view_page_contributors';
    const PAGEID = 'PageId';
    const STATUSID = 'StatusId';
    const ORIGINALID = 'OriginalId';
    const USERID = 'UserId';
    const ROLEID = 'RoleId';


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
