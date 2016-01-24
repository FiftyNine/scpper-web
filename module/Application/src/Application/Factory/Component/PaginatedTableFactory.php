<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Component;

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
                DbViewPages::TITLE => 'Page',
                DbViewPages::CLEANRATING => 'Rating',
                DbViewPages::REVISIONS => 'Revisions',
                DbViewPages::CREATIONDATE => 'Posted'
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
                DbViewMembership::DISPLAYNAME => 'User',
                DbViewMembership::VOTES => 'Votes',
                DbViewMembership::REVISIONS => 'Revisions',
                DbViewMembership::PAGES => 'Pages',
                DbViewMembership::JOINDATE => 'Joined',
            ), 
            $paginator, 
            'partial/tables/members.phtml', 
            $preview
        );        
        return $table;
    }    
}
