<?php

namespace Application\Utils\DbConsts;

class DbSiteStats
{
    const TABLE = 'site_stats';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const MEMBERS = 'Members';
    const ACTIVEMEMBERS = 'ActiveMembers';
    const CONTRIBUTORS = 'Contributors';
    const AUTHORS = 'Authors';
    const PAGES = 'Pages';
    const ORIGINALS = 'Originals';
    const TRANSLATIONS = 'Translations';
    const VOTES = 'Votes';
    const POSITIVE = 'Positive';
    const NEGATIVE = 'Negative';
    const REVISIONS = 'Revisions';


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
