<?php

namespace Application\Utils\DbConsts;

class DbRevisions
{
    const TABLE = 'revisions';
    const __ID = '__Id';
    const WIKIDOTID = 'WikidotId';
    const PAGEID = 'PageId';
    const REVISIONINDEX = 'RevisionIndex';
    const USERID = 'UserId';
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
