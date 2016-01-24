<?php

namespace Application\Utils\DbConsts;

class DbPages
{
    const TABLE = 'pages';
    const __ID = '__Id';
    const SITEID = 'SiteId';
    const WIKIDOTID = 'WikidotId';
    const TITLE = 'Title';
    const NAME = 'Name';
    const CATEGORYID = 'CategoryId';
    const SOURCE = 'Source';


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
