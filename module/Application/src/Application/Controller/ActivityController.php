<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Form\FormInterface;
use Application\Service\HubServiceInterface;
use Application\Form\DateIntervalForm;
use Application\Factory\Component\PaginatedTableFactory;
use Application\Utils\UserType;
use Application\Utils\PageStatus;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\DbConsts\DbViewRevisions;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\Aggregate;
use Application\Utils\DateAggregate;
use Application\Utils\Order;

/**
 * Description of RecentController
 *
 * @author Alexander
 */
class ActivityController extends AbstractActionController
{
    /**
     *
     * @var HubServiceInterface 
     */
    protected $services;    
    
    /**
     *
     * @var FormInterface
     */
    protected $dateIntervalForm;

    protected function getEditorsPaginator($siteId, $from, $to, $orderBy, $order, $page, $perPage)
    {
        $aggregates = [
            new Aggregate(DbViewRevisions::USERID, Aggregate::NONE, null, true),
            new Aggregate(DbViewRevisions::USERWIKIDOTNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewRevisions::USERDISPLAYNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewRevisions::USERDELETED, Aggregate::NONE, null, true),            
            new Aggregate('*', Aggregate::COUNT, 'Revisions'),
        ];
        $editors = $this->services->getRevisionService()->getAggregatedValues($siteId, $aggregates, $from, $to, [$orderBy => $order], true);
        $editors->setCurrentPageNumber($page);
        $editors->setItemCountPerPage($perPage); 
        return $editors;
    }

    protected function getVotersPaginator($siteId, $from, $to, $orderBy, $order, $page, $perPage)
    {
        $aggregates = [
            new Aggregate(DbViewVotes::USERID, Aggregate::NONE, null, true),           
            new Aggregate(DbViewVotes::USERNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewVotes::USERDISPLAYNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewVotes::USERDELETED, Aggregate::NONE, null, true),            
            new Aggregate('*', Aggregate::COUNT, 'Votes'),
            new Aggregate(DbViewVotes::VALUE, Aggregate::SUM, 'Sum'),
        ];
        $voters = $this->services->getVoteService()->getAggregatedForSite($siteId, $aggregates, $from, $to, [$orderBy => $order], true);
        $voters->setCurrentPageNumber($page);
        $voters->setItemCountPerPage($perPage);
        return $voters;
    }
    
    protected function getCommonParams(&$siteId, &$fromDate, &$toDate)
    {
        $siteId = $this->params()->fromQuery('siteId', 0);
        $from = $this->params()->fromQuery('fromDate', '2000-01-01');
        $to = $this->params()->fromQuery('toDate', '2020-01-01');
        $fromDate = \DateTime::createFromFormat('Y-m-d', $from);
        $toDate = \DateTime::createFromFormat('Y-m-d', $to);
        if ($fromDate) {
            $fromDate->setTime(0, 0, 0);
        }
        if ($toDate) {       
            $toDate->setTime(23, 59, 59);        
        }
        return $from && $to && $siteId > 0;
    }
    
    protected function getUsersData($siteId, $from, $to)
    {
        $users = $this->services->getUserService()->findSiteMembers($siteId, UserType::ANY, false, $from, $to, [DbViewMembership::JOINDATE => Order::ASCENDING], true);
        $users->setCurrentPageNumber(1);
        $users->setItemCountPerPage(3);
        $table = PaginatedTableFactory::createMembersTable($users, true);
        $table->getColumns()->setOrder(DbViewMembership::JOINDATE);
        $result = [
            'header' => [
                'users' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, false, $from, $to)
            ],
            'list' => [
                'voters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::VOTER, false, $from, $to),
                'contributors' => $this->services->getUserService()->countSiteMembers($siteId, UserType::CONTRIBUTOR, false, $from, $to),
                'posters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::POSTER, false, $from, $to),
                'still active' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, true, $from, $to),
            ],
            'table' => $table,
        ];
        return $result;
    }
    
    protected function getPagesData($siteId, $from, $to)
    {
        $maxRating = new Aggregate(DbViewPages::CLEANRATING, Aggregate::MAX, 'MaxRating');
        $avgRating = new Aggregate(DbViewPages::CLEANRATING, Aggregate::AVERAGE, 'AvgRating');
        $ratings = $this->services->getPageService()->getAggregatedValues($siteId, [$maxRating, $avgRating], $from, $to);
        $pages = $this->services->getPageService()->findSitePages($siteId, PageStatus::ANY, $from, $to, false, [DbViewPages::CREATIONDATE => Order::DESCENDING], true);
        $pages->setCurrentPageNumber(1);
        $pages->setItemCountPerPage(3);
        $table = PaginatedTableFactory::createPagesTable($pages, true);
        $table->getColumns()->setOrder(DbViewPages::CREATIONDATE);        
        $result = [
            'header' => [
                'pages' => $this->services->getPageService()->countSitePages($siteId, PageStatus::ANY, $from, $to, null),
            ],
            'list' => [
                'remains' => $this->services->getPageService()->countSitePages($siteId, PageStatus::ANY, $from, $to, false),
                'originals' => $this->services->getPageService()->countSitePages($siteId, PageStatus::ORIGINAL, $from, $to, false),
                'translations' => $this->services->getPageService()->countSitePages($siteId, PageStatus::TRANSLATION, $from, $to, false),
                'highest rating' => $ratings[0]['MaxRating'],
                //'average rating' => number_format($ratings[0]['AvgRating'], 1)
            ],
            'table' => $table
        ];
        return $result;
    }    
    
    protected function getRevisionsData($siteId, $from, $to)
    {
        $pageIdGroup = new Aggregate(DbViewRevisions::PAGEID, Aggregate::NONE, 'Tmp', true);
        $edited = $this->services->getRevisionService()->getAggregatedValues($siteId, [$pageIdGroup], $from, $to);
        $editors = $this->getEditorsPaginator($siteId, $from, $to, 'Revisions', Order::DESCENDING, 1, 3);
        $table = PaginatedTableFactory::createEditorsTable($editors, true);
        $result = [
            'header' => [
                'revisions' => $this->services->getRevisionService()->countSiteRevisions($siteId, $from, $to)
            ],
            'list' => [
                'edited pages' => count($edited),
                'editors' => $editors->getTotalItemCount()
            ],
            'table' => $table
        ];                    
        return $result;
    }        

    protected function getVotesData($site, $from, $to)
    {
        $voters = $this->getVotersPaginator($site->getId(), $from, $to, 'Votes', Order::DESCENDING, 1, 3);
        $table = PaginatedTableFactory::createVotersTable($voters, $site->getHideVotes(), true);
        $result = [
            'header' => [
                'votes' => $this->services->getVoteService()->countSiteVotes($site->getId(), VoteType::ANY, $from, $to),
            ],
            'list' => [
                'positive' => $this->services->getVoteService()->countSiteVotes($site->getId(), VoteType::POSITIVE, $from, $to),
                'negative' => $this->services->getVoteService()->countSiteVotes($site->getId(), VoteType::NEGATIVE, $from, $to),
            ],
            'table' => $table
        ];
        return $result;
    }
    
    public function __construct(HubServiceInterface $hubService, FormInterface $dateIntervalForm)
    {
        $this->services = $hubService;
        $this->dateIntervalForm = $dateIntervalForm;
    }

    public function activityAction()
    {        
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);        
        $fromControl = $this->dateIntervalForm->get(DateIntervalForm::FROM_DATE_NAME);
        $toControl= $this->dateIntervalForm->get(DateIntervalForm::TO_DATE_NAME);
        $lastDate = $site->getLastUpdate();
        $lastDate->setTime(23, 59, 59);
        $fromControl->setAttribute('max', $lastDate->format('Y-m-d'));
        $toControl->setAttribute('max', $lastDate->format('Y-m-d'));        
        $from = clone $lastDate;
        $from->sub(new \DateInterval('P1M'));
        $to = $lastDate;
        $fromControl->setValue($from->format('Y-m-d'));
        $toControl->setValue($to->format('Y-m-d'));        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->dateIntervalForm->setData($request->getPost());
            if ($this->dateIntervalForm->isValid()) {
                $from = \DateTime::createFromFormat('Y-m-d', $fromControl->getValue());
                $to = \DateTime::createFromFormat('Y-m-d', $toControl->getValue());
            }
        }
        $from->setTime(0, 0, 0);
        $to->setTime(23, 59, 59);
        $result = [            
            'intervalForm' => $this->dateIntervalForm,
            'site' => $site,
            'users' => $this->getUsersData($siteId, $from, $to),
            'pages' => $this->getPagesData($siteId, $from, $to),
            'revisions' => $this->getRevisionsData($siteId, $from, $to),
            'votes' => $this->getVotesData($site, $from, $to),
        ];
        return new ViewModel($result);
    }
    
    public function getUserChartDataAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $fromDate = null;
        $toDate = null;                
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewMembership::JOINDATE, 'Period');
            $dateAgg->setBestAggregateType($fromDate, $toDate);
            $result['data'] = [];
            $joins = $this->services->getUserService()->getMembershipAggregated($siteId, [$count, $dateAgg], UserType::ANY, false, $fromDate, $toDate);            
            foreach($joins as $join) {
                $result['data'][] = [$join['Period']->format(\DateTime::ISO8601), $join['Number']];
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, false, null, $fromDate);
            $result['success'] = true;
        }        
        return new JsonModel($result);
    }
    
    public function getPageChartDataAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $fromDate = null;
        $toDate = null;
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewPages::CREATIONDATE, 'Period');
            $dateAgg->setBestAggregateType($fromDate, $toDate);            
            $combined = [];                        
            $pages = $this->services->getPageService()->getAggregatedValues($siteId, [$count, $dateAgg], $fromDate, $toDate, false);            
            foreach ($pages as $page) {
                $combined[$page['Period']->format(\DateTime::ISO8601)] = [$page['Number'], 0];
            }
            $deleted = $this->services->getPageService()->getAggregatedValues($siteId, [$count, $dateAgg], $fromDate, $toDate, true);
            foreach ($deleted as $page) {
                $period = $page['Period']->format(\DateTime::ISO8601);
                if (array_key_exists($period, $combined)) {
                    $combined[$period][1] = $page['Number'];
                } else {
                    $combined[$period] = [0, $page['Number']];
                }
            }            
            $result['data'] = [];
            foreach ($combined as $period => $data) {
                $result['data'][] = [$period, $data[0], $data[1]];
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getPageService()->countSitePages($siteId, PageStatus::ANY, null, $fromDate, false);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }
    
    public function getRevisionChartDataAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $fromDate = null;
        $toDate = null;
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewRevisions::DATETIME, 'Period');
            $dateAgg->setBestAggregateType($fromDate, $toDate);            
            $result['data'] = [];
            $revs = $this->services->getRevisionService()->getAggregatedValues($siteId, [$count, $dateAgg], $fromDate, $toDate);
            foreach($revs as $rev) {
                $result['data'][] = [$rev['Period']->format(\DateTime::ISO8601), $rev['Number']];
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getRevisionService()->countSiteRevisions($siteId, null, $fromDate);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }

    public function getVoteChartDataAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $fromDate = null;
        $toDate = null;
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewVotes::DATETIME, 'Period');
            $dateAgg->setBestAggregateType($fromDate, $toDate);            
            $votes = $this->services->getVoteService()->getAggregatedForSite($siteId, [$count, $dateAgg], $fromDate, $toDate);
            $result['data'] = [];
            foreach($votes as $vote) {
                $result['data'][] = [$vote['Period']->format(\DateTime::ISO8601), $vote['Number']];
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getVoteService()->countSiteVotes($siteId, VoteType::ANY, null, $fromDate);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }
    
    public function usersAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $from = null;
        $to = null;
        if ($this->getCommonParams($siteId, $from, $to)) {
            $page = (int)$this->params()->fromQuery("page", 1);
            $perPage = (int)$this->params()->fromQuery("perPage", 10);
            $orderBy = $this->params()->fromQuery('orderBy', DbViewMembership::JOINDATE);            
            if (!DbViewMembership::hasField($orderBy)) {
                $orderBy = DbViewMembership::JOINDATE;                
            }
            $order = $this->params()->fromQuery('ascending', true);
            if ($order) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
            $users = $this->services->getUserService()->findSiteMembers($siteId, UserType::ANY, false, $from, $to, [$orderBy => $order], true);
            $users->setCurrentPageNumber($page);
            $users->setItemCountPerPage($perPage);
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            $table = PaginatedTableFactory::createMembersTable($users, false);
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/default/table.phtml', 
                    [
                        'table' => $table, 
                        'data' => ['siteId' => $siteId]
                    ]
                );
            }
        }        
        return new JsonModel($result);
    }
    
    public function pagesAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $from = null;
        $to = null;
        if ($this->getCommonParams($siteId, $from, $to)) {
            $page = (int)$this->params()->fromQuery('page', 1);
            $perPage = (int)$this->params()->fromQuery('perPage', 10);
            $orderBy = $this->params()->fromQuery('orderBy', DbViewPages::CREATIONDATE);
            if (!DbViewPages::hasField($orderBy)) {
                $orderBy = DbViewPages::CREATIONDATE;                
            }
            $order = $this->params()->fromQuery('ascending', false);
            if ($order) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
            $pages = $this->services->getPageService()->findSitePages($siteId, PageStatus::ANY, $from, $to, false, [$orderBy => $order], true);
            $pages->setCurrentPageNumber($page);
            $pages->setItemCountPerPage($perPage);
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            $table = PaginatedTableFactory::createPagesTable($pages, false);
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/default/table.phtml', 
                    [
                        'table' => $table, 
                        'data' => []
                    ]
                );
            }
        }        
        return new JsonModel($result);
    }

    public function editorsAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $from = null;
        $to = null;
        if ($this->getCommonParams($siteId, $from, $to)) {
            $page = (int)$this->params()->fromQuery('page', 1);
            $perPage = (int)$this->params()->fromQuery('perPage', 10);
            $orderBy = $this->params()->fromQuery('orderBy');
            if (!$orderBy || ($orderBy !== 'Revisions' && !DbViewRevisions::hasField($orderBy))) {
                $orderBy = 'Revisions';
                $order = Order::DESCENDING;
            }
            else if ($this->params()->fromQuery('ascending', true)) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
            $editors = $this->getEditorsPaginator($siteId, $from, $to, $orderBy, $order, $page, $perPage);
            $table = PaginatedTableFactory::createEditorsTable($editors);            
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/default/table.phtml', 
                    [
                        'table' => $table, 
                        'data' => [
                            'total' => $this->services->getRevisionService()->countSiteRevisions($siteId, $from, $to)
                        ]
                    ]
                );
            }
        }        
        return new JsonModel($result);
    }

    public function votersAction()
    {
        $result = ['success' => false];
        $siteId = -1;
        $from = null;
        $to = null;
        if ($this->getCommonParams($siteId, $from, $to)) {
            $site = $this->services->getSiteService()->find($siteId);
            $page = (int)$this->params()->fromQuery('page', 1);
            $perPage = (int)$this->params()->fromQuery('perPage', 10);
            $orderBy = $this->params()->fromQuery('orderBy');
            if (!$orderBy || ($orderBy !== 'Votes' && !DbViewVotes::hasField($orderBy))) {
                $orderBy = 'Votes';
                $order = Order::DESCENDING;
            }
            else if ($this->params()->fromQuery('ascending', true)) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
            $voters = $this->getVotersPaginator($siteId, $from, $to, $orderBy, $order, $page, $perPage);
            $table = PaginatedTableFactory::createVotersTable($voters, $site->getHideVotes());
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/default/table.phtml', 
                    [
                        'table' => $table, 
                        'data' => []
                    ]
                );
            }
        }        
        return new JsonModel($result);
    }    
}
