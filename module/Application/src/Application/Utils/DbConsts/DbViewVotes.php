<?php

namespace Application\Utils\DbConsts;

class DbViewVotes
{
    const TABLE = 'view_votes';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const SITENAME = 'SiteName';
    const SITE = 'Site';
    const PAGEID = 'PageId';
    const PAGENAME = 'PageName';
    const PAGETITLE = 'PageTitle';
    const USERID = 'UserId';
    const USERNAME = 'UserName';
    const USERDISPLAYNAME = 'UserDisplayName';
    const USERDELETED = 'UserDeleted';
    const VALUE = 'Value';
    const DATETIME = 'DateTime';


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
