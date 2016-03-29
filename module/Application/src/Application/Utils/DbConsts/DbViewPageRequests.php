<?php

namespace Application\Utils\DbConsts;

class DbViewPageRequests
{
    const TABLE = 'view_page_requests';
    const SITE = 'Site';
    const PAGENAME = 'PageName';
    const TITLE = 'Title';
    const HOST = 'Host';
    const COUNT = 'Count';


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
