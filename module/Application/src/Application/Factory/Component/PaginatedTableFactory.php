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
use Application\Utils\DbConsts\DbViewPageReports;
use Application\Utils\DbConsts\DbViewRevisions;
use Application\Utils\DbConsts\DbViewVotes;
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
            'pages',
            [
                Column::group([
                    Column::column('#', 8, '', true, '', Column::INDEX),
                    Column::column('Page', 92, DbViewPages::TITLE, true, '', Column::PAGE),
                ], 35, 768),
                Column::group([
                        Column::column('Total', 20, DbViewPages::CLEANRATING, false, 'Total rating - only counts votes from members of the site'),
                        Column::column('Contributor', 30, DbViewPages::CONTRIBUTORRATING, false, 'Contributors rating - only counts votes from members who have at least one successful page'),
                        Column::column('Adjusted', 25, DbViewPages::ADJUSTEDRATING, false, 'Adjusted rating - only counts votes from active members'),
                        Column::column('Wilson', 25, DbViewPages::WILSONSCORE, false, 'Wilson score - calculated as ratio of upvotes to downvotes corrected by total amount of votes'),
                ], 30, 991, 'Rating'),
                Column::group([
                    Column::group([
                        Column::column('Status', 60, DbViewPages::STATUSID),
                        Column::column('Kind', 40, DbViewPages::KINDID),
                    ], 40, 991),
                    Column::group([
                        Column::column('Posted', 40, DbViewPages::CREATIONDATE, false, '', Column::DATE),
                        Column::column('Authors', 60, '', true, '', Column::USERS)
                    ], 60, 991),
                ], 35, 768)
            ], 
            $paginator, 
            'partial/tables/pages.phtml', 
            $preview
        );
        return $table;
    }

    static public function createSitesPagesTable($paginator, $preview = false)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'sites-pages',
            [
                Column::group([
                    Column::column('#', 13, '', true, '', Column::INDEX),
                    Column::column('Branch', 20, DbViewPages::SITENAME, true, '', Column::OTHER),
                    Column::column('Page', 72, DbViewPages::TITLE, true, '', Column::PAGE),
                ], 35, 768),
                Column::group([
                        Column::column('Total', 20, DbViewPages::CLEANRATING, false, 'Total rating - only counts votes from members of the site'),
                        Column::column('Contributor', 30, DbViewPages::CONTRIBUTORRATING, false, 'Contributors rating - only counts votes from members who have at least one successful page'),
                        Column::column('Adjusted', 25, DbViewPages::ADJUSTEDRATING, false, 'Adjusted rating - only counts votes from active members'),
                        Column::column('Wilson', 25, DbViewPages::WILSONSCORE, false, 'Wilson score - calculated as ratio of upvotes to downvotes corrected by total amount of votes'),
                ], 30, 991, 'Rating'),
                Column::group([
                    Column::group([
                        Column::column('Status', 60, DbViewPages::STATUSID),
                        Column::column('Kind', 40, DbViewPages::KINDID),
                    ], 40, 991),
                    Column::group([
                        Column::column('Posted', 40, DbViewPages::CREATIONDATE, false, '', Column::DATE),
                        Column::column('Authors', 60, '', true, '', Column::USERS)
                    ], 60, 991),
                ], 35, 768)
            ], 
            $paginator, 
            'partial/tables/pages.phtml', 
            $preview
        );
        return $table;
    }
    
    static public function createMembersTable($paginator, $preview = false)
    {
        
        $table = new \Application\Component\PaginatedTable\Table(
            'members',
            [
                Column::group([
                    Column::column('#', 15, '', true, '', Column::INDEX),
                    Column::column('User', 85, DbViewMembership::DISPLAYNAME, true, '', Column::USERS)
                ], 40, 768),
                Column::group([
                    Column::column('Votes', 30, DbViewMembership::VOTES, false),
                    Column::column('Revisions', 40, DbViewMembership::REVISIONS, false),
                    Column::column('Pages', 30, DbViewMembership::PAGES, false)
                ], 30, 768),
                Column::group([
                    Column::column('Joined', 65, DbViewMembership::JOINDATE, false, '', Column::DATE_TIME),
                    Column::column('Active', 35, DbViewMembership::LASTACTIVITY, false, 'User either posted a page, edited one, or cast at least one vote in the last 6 months'),                    
                ], 30, 768)
            ], 
            $paginator, 
            'partial/tables/members.phtml', 
            $preview
        );
        return $table;
    }    
    
    static public function createEditorsTable($paginator, $preview = false)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'editors',
            [
                Column::group([
                    Column::column('#', 5, '', true, '', Column::INDEX),
                    Column::column('User', 95, DbViewRevisions::USERDISPLAYNAME, true, '', Column::USERS),
                ], 70, 768),
                Column::group([
                    Column::column('Revisions', 70, 'Revisions', false),
                    Column::column('%', 30, null, false, 'Relative to all revisions made during selected period'),
                ], 30, 768),
            ],
            $paginator,
            'partial/tables/editors.phtml', 
            $preview
        );
        return $table;
    }
    
    static public function createVotersTable($paginator, $hideVotes = false, $preview = false)
    {
        $voteColumns = [Column::column('Votes', 30, 'Votes', false)];        
        if ($hideVotes) {
            $widthRatio = 90;
        } else {
            $voteColumns[] = Column::column('Positive', 35);
            $voteColumns[] = Column::column('Negative', 35);
            $widthRatio = 70;
        }            
        $table = new \Application\Component\PaginatedTable\Table(
            'voters',
            [
                Column::group([
                    Column::column('#', 5, '', true, '', Column::INDEX),
                    Column::column('User', 95, DbViewRevisions::USERDISPLAYNAME, true, '', Column::USERS),
                ], $widthRatio, 768),
                Column::group($voteColumns, 100-$widthRatio, 768),
            ],
            $paginator,
            'partial/tables/voters.phtml', 
            $preview
        );
        return $table;
    }    
    
    static public function createUsersTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'users',
            [
                Column::column('#', 5, '', true, '', Column::INDEX),
                Column::column('User', 50, DbViewUsers::DISPLAYNAME, true, '', Column::USERS),
                Column::column('Membership', 45)
            ],
            $paginator,
            'partial/tables/users.phtml', 
            false
        );
        return $table;        
    }

    static public function createSiteUsersTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'site-users',
            [
                Column::group([
                    Column::column('#', 15, '', true, '', Column::INDEX),
                    Column::column('User', 85, DbViewUsers::TABLE.'_'.DbViewUsers::DISPLAYNAME, true, '', Column::USERS),
                ], 40, 768),
                Column::group([
                    Column::column('Votes', 30, DbViewUserActivity::TABLE.'_'.DbViewUserActivity::VOTES, false),
                    Column::column('Revisions', 40, DbViewUserActivity::TABLE.'_'.DbViewUserActivity::REVISIONS, false),
                    Column::column('Pages', 30, DbViewUserActivity::TABLE.'_'.DbViewUserActivity::PAGES, false),
                ], 30, 768),
                Column::group([
                    Column::column('Joined', 65, DbViewMembership::TABLE.'_'.DbViewMembership::JOINDATE, false, '', Column::DATE),
                    Column::column('Active', 35, DbViewUserActivity::TABLE.'_'.DbViewUserActivity::LASTACTIVITY, true, 'User either posted a page, edited one, or cast at least one vote in the last 6 months'),
                ], 30, 768)
            ],
            $paginator,
            'partial/tables/siteUsers.phtml', 
            false            
        );
        return $table;        
    }
    
    static public function createAuthorsTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'authors',
            [
                Column::group([
                    Column::column('#', 15, '', true, '', Column::INDEX),
                    Column::column('User', 85, DbViewAuthors::USERDISPLAYNAME, true, '', Column::USERS),
                ], 40, 768),
                Column::group([
                    Column::column('Pages', 25, AuthorSummaryConsts::PAGES, false),
                    Column::column('Originals', 35, AuthorSummaryConsts::ORIGINALS, false),
                    Column::column('Translations', 40, AuthorSummaryConsts::TRANSLATIONS, false),
                ], 30, 768),
                Column::group([
                    Column::column('Rating', 40, AuthorSummaryConsts::TOTAL_RATING, false, 'Summary clean rating of all pages posted by user'),
                    Column::column('Average', 30, AuthorSummaryConsts::AVERAGE_RATING, false, 'Average clean rating of a page posted by user'),
                    Column::column('Highest', 30, AuthorSummaryConsts::HIGHEST_RATING, false, 'Highest clean rating of a page posted by user')
                ], 30, 768)
            ],
            $paginator,
            'partial/tables/authors.phtml', 
            false
        );
        return $table;        
    }
    
    static public function createRevisionsTable($paginator)
    {        
        $table = new \Application\Component\PaginatedTable\Table(
            'revisions',
            [
                Column::group([
                    Column::column('#', 10, DbViewRevisions::REVISIONINDEX, false, '', Column::INDEX),
                    Column::column('User', 90, DbViewRevisions::USERDISPLAYNAME, true, '', Column::USERS),                
                ], 40, 768),
                Column::group([
                    Column::column('Time', 30, DbViewRevisions::DATETIME, false, '', Column::DATE_TIME),
                    Column::column('Comment', 70),                    
                ], 60, 768)
            ],
            $paginator,
            'partial/tables/revisions.phtml', 
            false
        );
        return $table;        
    }

    static public function createVotesTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'votes',
            [
                Column::group([
                    Column::column('#', 7, '', true, '', Column::INDEX),
                    Column::column('User', 43, DbViewVotes::USERDISPLAYNAME, true, '', Column::USERS),                
                    Column::column('Page', 50, DbViewVotes::PAGETITLE, true, '', Column::PAGE),                
                ], 50, 768),
                Column::group([
                    Column::column('Vote', 30, DbViewVotes::VALUE, false),
                    Column::column('Date', 70, DbViewVotes::DATETIME, false, 'Not the exact time but rather upper estimate', Column::DATE),
                ], 20, 768),
                Column::group([
                    Column::column('Member', 30, DbViewVotes::FROMMEMBER, false, 'Vote from a member of the wiki'),
                    Column::column('Contributor', 45, DbViewVotes::FROMCONTRIBUTOR, false, 'Vote from a member who authored at least one successful page'),
                    Column::column('Active', 25, DbViewVotes::FROMACTIVE, false, 'Vote from an active member of the wiki'),                
                ], 30, 768),                
            ],
            $paginator,
            'partial/tables/votes.phtml', 
            false
        );
        return $table;        
    }
    
    static public function createPageVotesTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'page-votes',
            [
                Column::group([
                    Column::column('#', 10, '', true, '', Column::INDEX),
                    Column::column('User', 90, DbViewVotes::USERDISPLAYNAME, true, '', Column::USERS),
                ], 40, 768),
                Column::group([
                    Column::column('Vote', 30, DbViewVotes::VALUE, false),
                    Column::column('Date', 70, DbViewVotes::DATETIME, false, 'Not the exact time but rather upper estimate', Column::DATE),
                ], 25, 768),
                Column::group([
                    Column::column('Member', 30, DbViewVotes::FROMMEMBER, false, 'Vote from a member of the wiki'),
                    Column::column('Contributor', 40, DbViewVotes::FROMCONTRIBUTOR, false, 'Vote from a member who authored at least one successful page'),
                    Column::column('Active', 30, DbViewVotes::FROMACTIVE, false, 'Vote from an active member of the wiki'),
                ], 35, 768)
            ],
            $paginator,
            'partial/tables/votes.phtml', 
            false
        );
        return $table;        
    }
    
    static public function createUserVotesTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'user-votes',
            [
                Column::group([
                    Column::column('#', 5, '', true, '', Column::INDEX),                
                    Column::column('Page', 50, DbViewVotes::PAGETITLE, true, '', Column::PAGE),
                    Column::column('Authors', 45, '', false, '', Column::USERS),
                ], 70, 768),
                Column::group([
                    Column::column('Vote', 20, DbViewVotes::VALUE, false),
                    Column::column('Date', 80, DbViewVotes::DATETIME, false, 'Not the exact time but rather upper estimate', Column::DATE),
                ], 30, 768)
            ],
            $paginator,
            'partial/tables/votes.phtml', 
            false
        );
        return $table;        
    }
    
    static public function createReportsTable($paginator)
    {
        $table = new \Application\Component\PaginatedTable\Table(
            'reports',
            [
                Column::group([
                    Column::column('#', 8, '', true, '', Column::INDEX),
                    Column::column('Branch', 20, '', true, '', Column::OTHER),
                    Column::column('Page', 92, '', true, '', Column::PAGE),
                ], 40, 768),
                Column::group([
                    Column::column('Reporter', 40, '', true, '', Column::OTHER),
                    Column::column('Submitted', 60, '', false, '', Column::DATE),
                ], 20, 991),
                Column::group([
                    Column::column('Status', 60, ''),
                    Column::column('Kind', 40, ''),
                ], 15, 768),
                Column::group([
                    Column::column('Authors', 60, '', true, '', Column::USERS)
                ], 15, 991),                
                Column::group([
                    Column::column('State', 20, '', true, '', Column::OTHER),
                ], 10, 768)
            ], 
            $paginator, 
            'partial/tables/reports.phtml', 
            false
        );
        return $table;        
    }    
}
