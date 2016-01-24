<?php

namespace Application\Utils\DbConsts;

class DbViewPageStatus
{
    const TABLE = 'view_page_status';
    const SITEID = 'SiteId';
    const SITENAME = 'SiteName';
    const SITE = 'Site';
    const PAGEID = 'PageId';
    const PAGENAME = 'PageName';
    const TITLE = 'Title';
    const CREATIONDATE = 'CreationDate';
    const STATUSID = 'StatusId';
    const STATUS = 'Status';
    const ORIGINALID = 'OriginalId';


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
