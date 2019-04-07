<?php

namespace Application\Utils\DbConsts;

class DbViewPageReports
{
    const TABLE = 'view_page_reports';
    const ID = 'Id';
    const PAGEID = 'PageId';
    const REPORTER = 'Reporter';
    const STATUSID = 'StatusId';
    const ORIGINALID = 'OriginalId';
    const KINDID = 'KindId';
    const CONTRIBUTORS = 'Contributors';
    const DATE = 'Date';
    const REPORTSTATE = 'ReportState';
    const SITEID = 'SiteId';
    const SITENAME = 'SiteName';
    const PAGENAME = 'PageName';
    const TITLE = 'Title';
    const OLDSTATUSID = 'OldStatusId';
    const OLDSTATUS = 'OldStatus';
    const OLDKINDID = 'OldKindId';
    const OLDKIND = 'OldKind';
    const OLDORIGINALID = 'OldOriginalId';


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
