<?php

namespace Application\Utils\DbConsts;

class DbSites
{
    const TABLE = 'sites';
    const __ID = '__Id';
    const WIKIDOTID = 'WikidotId';
    const ENGLISHNAME = 'EnglishName';
    const NATIVENAME = 'NativeName';
    const SHORTNAME = 'ShortName';
    const WIKIDOTNAME = 'WikidotName';
    const DOMAIN = 'Domain';
    const DEFAULTLANGUAGE = 'DefaultLanguage';
    const LASTUPDATE = 'LastUpdate';
    const HIDEVOTES = 'HideVotes';


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
