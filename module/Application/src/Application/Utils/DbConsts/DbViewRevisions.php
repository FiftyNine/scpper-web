<?php

namespace Application\Utils\DbConsts;

class DbViewRevisions
{
    const TABLE = 'view_revisions';
    const __ID = '__Id';
    const REVISIONID = 'RevisionId';
    const PAGEID = 'PageId';
    const REVISIONINDEX = 'RevisionIndex';
    const PAGENAME = 'PageName';
    const PAGETITLE = 'PageTitle';
    const SITEID = 'SiteId';
    const SITENAME = 'SiteName';
    const SITE = 'Site';
    const USERID = 'UserId';
    const USERWIKIDOTNAME = 'UserWikidotName';
    const USERDISPLAYNAME = 'UserDisplayName';
    const USERDELETED = 'UserDeleted';
    const DATETIME = 'DateTime';
    const COMMENTS = 'Comments';


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
