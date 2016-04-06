<?php

namespace Application\Utils\DbConsts;

class DbViewPages
{
    const TABLE = 'view_pages';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const PAGEID = 'PageId';
    const CATEGORYID = 'CategoryId';
    const SITENAME = 'SiteName';
    const SITE = 'Site';
    const PAGENAME = 'PageName';
    const TITLE = 'Title';
    const SOURCE = 'Source';
    const CREATIONDATE = 'CreationDate';
    const RATING = 'Rating';
    const CLEANRATING = 'CleanRating';
    const CONTRIBUTORRATING = 'ContributorRating';
    const ADJUSTEDRATING = 'AdjustedRating';
    const REVISIONS = 'Revisions';
    const STATUSID = 'StatusId';
    const KINDID = 'KindId';
    const STATUS = 'Status';
    const KIND = 'Kind';
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
