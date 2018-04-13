<?php

namespace Application\Utils\DbConsts;

class DbViewUserActivity
{
    const TABLE = 'view_user_activity';
    const USERID = 'UserId';
    const USERDISPLAYNAME = 'UserDisplayName';
    const USERNAME = 'UserName';
    const USERDELETED = 'UserDeleted';
    const SITEID = 'SiteId';
    const VOTES = 'Votes';
    const VOTESSUMM = 'VotesSumm';
    const REVISIONS = 'Revisions';
    const PAGES = 'Pages';
    const TOTALRATING = 'TotalRating';
    const LASTACTIVITY = 'LastActivity';
    const SITEENGLISHNAME = 'SiteEnglishName';
    const SITENAME = 'SiteName';
    const SITE = 'Site';


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
