<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Component;

use Application\Utils\DbConsts\DbViewMembership;

/**
 * Description of PaginatedTableFactory
 *
 * @author Alexander
 */
class PaginatedTableFactory 
{
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
            'partial/membersTableBody.phtml', 
            $preview
        );        
        return $table;
    }
}
