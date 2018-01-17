<?php

namespace Application\Utils\DbConsts;

class DbViewTags
{
    const TABLE = 'view_tags';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const SITENAME = 'SiteName';
    const PAGEID = 'PageId';
    const PAGENAME = 'PageName';
    const TAG = 'Tag';
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
