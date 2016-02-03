<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Component;

use Application\Component\PaginatedTable\Column;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\DbConsts\DbViewRevisions;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\AuthorSummaryConsts;

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
                new Column('Rating', DbViewPages::CLEANRATING, false),
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
                new Column('Votes', DbViewMembership::VOTES, false),
                new Column('Revisions', DbViewMembership::REVISIONS, false),
                new Column('Pages', DbViewMembership::PAGES, false),
                new Column('Active', DbViewMembership::LASTACTIVITY, false),
                new Column('Joined', DbViewMembership::JOINDATE)
            ), 
            $paginator, 
            'partial/tables/members.phtml', 
            $preview
        );        
        return $table;
    }    
    
    static public function createEditorsTable($paginator, $preview = false)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            array(
                new Column('User', DbViewRevisions::USERDISPLAYNAME),
                new Column('Revisions', 'Revisions', false),
                new Column('Percent of total'),
            ),
            $paginator,
            'partial/tables/editors.phtml', 
            $preview
        );
        return $table;
    }
    
    static public function createVotersTable($paginator, $preview = false)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            array(
                new Column('User', DbViewRevisions::USERDISPLAYNAME),
                new Column('Votes', 'Votes', false),
                new Column('Positive'),
                new Column('Negative'),
            ),
            $paginator,
            'partial/tables/voters.phtml', 
            $preview
        );
        return $table;
    }    
    
    static public function createUsersTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            array(
                new Column('User', DbViewUsers::TABLE.'_'.DbViewUsers::DISPLAYNAME),
                new Column('Votes', DbViewUserActivity::TABLE.'_'.DbViewUserActivity::VOTES, false),
                new Column('Revisions', DbViewUserActivity::TABLE.'_'.DbViewUserActivity::REVISIONS, false),
                new Column('Pages', DbViewUserActivity::TABLE.'_'.DbViewUserActivity::PAGES, false),
                new Column('Active', DbViewUserActivity::TABLE.'_'.DbViewUserActivity::LASTACTIVITY),
                new Column('Joined', DbViewMembership::TABLE.'_'.DbViewMembership::JOINDATE, false)
            ),
            $paginator,
            'partial/tables/users.phtml', 
            false
        );
        return $table;        
    }
    
    static public function createAuthorsTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            array(
                new Column('User', DbViewAuthors::USERDISPLAYNAME),
                new Column('Pages', AuthorSummaryConsts::PAGES, false),
                new Column('Originals', AuthorSummaryConsts::ORIGINALS, false),
                new Column('Translations', AuthorSummaryConsts::TRANSLATIONS, false),
                new Column('Rating', AuthorSummaryConsts::TOTAL_RATING, false),
                new Column('Average'),
                new Column('Highest', AuthorSummaryConsts::HIGHEST_RATING, false)
            ),
            $paginator,
            'partial/tables/authors.phtml', 
            false
        );
        return $table;        
    }
}