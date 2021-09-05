<?php

namespace Application\Utils\DbConsts;

class DbViewSites
{
    const TABLE = 'view_sites';
    const SITEID = 'SiteId';
    const ENGLISHNAME = 'EnglishName';
    const NATIVENAME = 'NativeName';
    const SHORTNAME = 'ShortName';
    const WIKIDOTNAME = 'WikidotName';
    const DOMAIN = 'Domain';
    const DEFAULTLANGUAGE = 'DefaultLanguage';
    const LASTUPDATE = 'LastUpdate';
    const HIDEVOTES = 'HideVotes';
    const PROTOCOL = 'Protocol';
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
