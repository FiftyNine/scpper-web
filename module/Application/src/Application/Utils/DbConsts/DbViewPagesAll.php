<?php

namespace Application\Utils\DbConsts;

class DbViewPagesAll
{
    const TABLE = 'view_pages_all';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const PAGEID = 'PageId';
    const CATEGORYID = 'CategoryId';
    const SITENAME = 'SiteName';
    const SITE = 'Site';
    const PAGENAME = 'PageName';
    const TITLE = 'Title';
    const ALTTITLE = 'AltTitle';
    const SOURCE = 'Source';
    const DELETED = 'Deleted';
    const LASTUPDATE = 'LastUpdate';
    const CREATIONDATE = 'CreationDate';
    const RATING = 'Rating';
    const CLEANRATING = 'CleanRating';
    const CONTRIBUTORRATING = 'ContributorRating';
    const ADJUSTEDRATING = 'AdjustedRating';
    const WILSONSCORE = 'WilsonScore';
    const MONTHRATING = 'MonthRating';
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
