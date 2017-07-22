<?php

namespace Application\Utils\DbConsts;

class DbPageReports
{
    const TABLE = 'page_reports';
    const ID = 'Id';
    const PAGEID = 'PageId';
    const REPORTER = 'Reporter';
    const STATUSID = 'StatusId';
    const ORIGINALID = 'OriginalId';
    const KINDID = 'KindId';
    const CONTRIBUTORS = 'Contributors';
    const PROCESSED = 'Processed';


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
