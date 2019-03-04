<?php

namespace Application\Utils\DbConsts;

class DbPageSummary
{
    const TABLE = 'page_summary';
    const __ID = '__Id';
    const PAGEID = 'PageId';
    const RATING = 'Rating';
    const CLEANRATING = 'CleanRating';
    const REVISIONS = 'Revisions';
    const CONTRIBUTORRATING = 'ContributorRating';
    const ADJUSTEDRATING = 'AdjustedRating';
    const WILSONSCORE = 'WilsonScore';
    const MONTHRATING = 'MonthRating';


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
