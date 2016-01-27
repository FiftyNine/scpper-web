<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Component;

use Application\Component\PaginatedTable\Column;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbConsts\DbViewPages;

/**
 * Description of PaginatedTableFactory
 *
 * @author Alexander
 */
class PaginatedTableFactory 
{
    static public function createPagesTable($paginator, $preview = false)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            array(
                new Column('Page', DbViewPages::TITLE),
                new Column('Rating', DbViewPages::CLEANRATING),
                new Column('Status', DbViewPages::STATUS),
                new Column('Posted', DbViewPages::CREATIONDATE),
                new Column('Authors')
            ), 
            $paginator, 
            'partial/tables/pages.phtml', 
            $preview
        );        
        return $table;
    }
    
    static public function createMembersTable($paginator, $preview = false)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            array(
                new Column('User', DbViewMembership::DISPLAYNAME),
                new Column('Votes', DbViewMembership::VOTES),
                new Column('Revisions', DbViewMembership::REVISIONS),
                new Column('Pages', DbViewMembership::PAGES),
                new Column('Active', DbViewMembership::LASTACTIVITY),
                new Column('Joined', DbViewMembership::JOINDATE)
            ), 
            $paginator, 
            'partial/tables/members.phtml', 
            $preview
        );        
        return $table;
    }    
}
