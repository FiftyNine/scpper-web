<?php

namespace Application\Utils\DbConsts;

class DbViewAuthors
{
    const TABLE = 'view_authors';
    const SITEID = 'SiteId';
    const SITE = 'Site';
    const SITENAME = 'SiteName';
    const PAGEID = 'PageId';
    const PAGENAME = 'PageName';
    const PAGETITLE = 'PageTitle';
    const STATUSID = 'StatusId';
    const STATUS = 'Status';
    const RATING = 'Rating';
    const ROLE = 'Role';
    const ROLEID = 'RoleId';
    const USERNAME = 'UserName';
    const USERDISPLAYNAME = 'UserDisplayName';
    const USERDELETED = 'UserDeleted';
    const USERID = 'UserId';


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
