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
use Application\Utils\PageType;
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
class RecentController extends AbstractActionController
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
       
    protected function getCommonParams(&$siteId, &$fromDate, &$toDate)
    {
        $siteId = $this->params()->fromQuery('siteId', 0);
        $from = $this->params()->fromQuery('fromDate', '2000-01-01');
        $to = $this->params()->fromQuery('toDate', '2020-01-01');
        $fromDate = \DateTime::createFromFormat('Y-m-d', $from);
        $toDate = \DateTime::createFromFormat('Y-m-d', $to);        
        return $from && $to && $siteId > 0;
    }
    
    protected function getMembersData($siteId, $from, $to)
    {
        $members = $this->services->getUserService()->findSiteMembers($siteId, UserType::ANY, false, $from, $to, array(DbViewMembership::JOINDATE => Order::ASCENDING), true);
        $members->setCurrentPageNumber(1);
        $members->setItemCountPerPage(3);
        $table = PaginatedTableFactory::createMembersTable($members, true);
        $table->getColumns()->setOrder(DbViewMembership::JOINDATE);
        $result = array(
            'header' => array(
                'users' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, false, $from, $to)
            ),
            'list' => array(
                'voters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::VOTER, false, $from, $to),
                'contributors' => $this->services->getUserService()->countSiteMembers($siteId, UserType::CONTRIBUTOR, false, $from, $to),
                'posters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::POSTER, false, $from, $to),
                'still active' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, true, $from, $to),
            ),
            'table' => $table,
        );
        return $result;
    }
    
    protected function getPagesData($siteId, $from, $to)
    {
        $maxRating = new Aggregate(DbViewPages::CLEANRATING, Aggregate::MAX, 'MaxRating');
        $avgRating = new Aggregate(DbViewPages::CLEANRATING, Aggregate::AVERAGE, 'AvgRating');
        $ratings = $this->services->getPageService()->getAggregatedValues($siteId, array($maxRating, $avgRating), $from, $to);
        $pages = $this->services->getPageService()->findSitePages($siteId, PageType::ANY, $from, $to, array(DbViewPages::CREATIONDATE => Order::ASCENDING), true);
        $pages->setCurrentPageNumber(1);
        $pages->setItemCountPerPage(3);
        $table = PaginatedTableFactory::createPagesTable($pages, true);
        $table->getColumns()->setOrder(DbViewPages::CREATIONDATE);        
        $result = array(
            'header' => array(
                'pages' => $this->services->getPageService()->countSitePages($siteId, PageType::ANY, $from, $to),
            ),
            'list' => array(
                'originals' => $this->services->getPageService()->countSitePages($siteId, PageType::ORIGINAL, $from, $to),
                'translations' => $this->services->getPageService()->countSitePages($siteId, PageType::TRANSLATION, $from, $to),
                'highest rating' => $ratings[0]['MaxRating'],
                'average rating' => $ratings[0]['AvgRating']
            ),
            'table' => $table
        );
        return $result;
    }    
    
    protected function getRevisionsData($siteId, $from, $to)
    {
        $pageIdGroup = new Aggregate(DbViewRevisions::PAGEID, Aggregate::NONE, 'Tmp', true);
        $edited = $this->services->getRevisionService()->getAggregatedValues($siteId, array($pageIdGroup), $from, $to);
        $userIdGroup = new Aggregate(DbViewRevisions::USERID, Aggregate::NONE, 'Tmp', true);
        $editors = $this->services->getRevisionService()->getAggregatedValues($siteId, array($userIdGroup), $from, $to);
        $result = array(
            'header' => array(
                'revisions' => $this->services->getRevisionService()->countSiteRevisions($siteId, $from, $to)
            ),
            'list' => array(
                'edited pages' => count($edited),
                'editors' => count($editors)
            )
        );                    
        return $result;
    }        

    protected function getVotesData($siteId, $from, $to)
    {
        $result = array(
            'header' => array(
                'votes' => $this->services->getVoteService()->countSiteVotes($siteId, VoteType::ANY, $from, $to),
            ),
            'list' => array(
                'positive' => $this->services->getVoteService()->countSiteVotes($siteId, VoteType::POSITIVE, $from, $to),
                'negative' => $this->services->getVoteService()->countSiteVotes($siteId, VoteType::NEGATIVE, $from, $to),
            )
        );
        return $result;
    }
    
    public function __construct(HubServiceInterface $hubService, FormInterface $dateIntervalForm)
    {
        $this->services = $hubService;
        $this->dateIntervalForm = $dateIntervalForm;
    }

    public function recentAction()
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
        $result = array(            
            'intervalForm' => $this->dateIntervalForm,
            'site' => $site,
            'members' => $this->getMembersData($siteId, $from, $to),
            'pages' => $this->getPagesData($siteId, $from, $to),
            'revisions' => $this->getRevisionsData($siteId, $from, $to),
            'votes' => $this->getVotesData($siteId, $from, $to),
        );
        return new ViewModel($result);
    }
    
    public function getMemberChartDataAction()
    {
        $result = array('success' => false);
        $siteId = -1;
        $fromDate = null;
        $toDate = null;                
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewMembership::JOINDATE);
            $dateAgg->setBestAggregateType($fromDate, $toDate);
            $joins = $this->services->getUserService()->getAggregatedValues($siteId, array($count, $dateAgg), UserType::ANY, false, $fromDate, $toDate);
            foreach($joins as $join) {
                $result['data'][] = array($join[DbViewMembership::JOINDATE]->format(\DateTime::ISO8601), $join['Number']);
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, false, null, $fromDate);            
            $result['success'] = true;
        }        
        return new JsonModel($result);
    }
    
    public function getPageChartDataAction()
    {
        $result = array('success' => false);
        $siteId = -1;
        $fromDate = null;
        $toDate = null;
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');            
            $dateAgg = new DateAggregate(DbViewPages::CREATIONDATE);
            $dateAgg->setBestAggregateType($fromDate, $toDate);            
            $pages = $this->services->getPageService()->getAggregatedValues($siteId, array($count, $dateAgg), $fromDate, $toDate);            
            foreach ($pages as $page) {
                $result['data'][] = array($page[DbViewPages::CREATIONDATE]->format(\DateTime::ISO8601), $page['Number']);
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getPageService()->countSitePages($siteId, PageType::ANY, null, $fromDate);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }
    
    public function getRevisionChartDataAction()
    {
        $result = array('success' => false);
        $siteId = -1;
        $fromDate = null;
        $toDate = null;
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewRevisions::DATETIME);
            $dateAgg->setBestAggregateType($fromDate, $toDate);            
            $revs = $this->services->getRevisionService()->getAggregatedValues($siteId, array($count, $dateAgg), $fromDate, $toDate);
            foreach($revs as $rev) {
                $result['data'][] = array($rev[DbViewRevisions::DATETIME]->format(\DateTime::ISO8601), $rev['Number']);
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getRevisionService()->countSiteRevisions($siteId, null, $fromDate);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }

    public function getVoteChartDataAction()
    {
        $result = array('success' => false);
        $siteId = -1;
        $fromDate = null;
        $toDate = null;
        if ($this->getCommonParams($siteId, $fromDate, $toDate)) {
            $count = new Aggregate('*', Aggregate::COUNT, 'Number');
            $dateAgg = new DateAggregate(DbViewVotes::DATETIME);
            $dateAgg->setBestAggregateType($fromDate, $toDate);            
            $votes = $this->services->getVoteService()->getAggregatedValues($siteId, array($count, $dateAgg), $fromDate, $toDate);            
            foreach($votes as $vote) {
                $result['data'][] = array($vote[DbViewVotes::DATETIME]->format(\DateTime::ISO8601), $vote['Number']);
            }
            $result['group'] = $dateAgg->getAggregateDescription();
            $result['starting'] = $this->services->getVoteService()->countSiteVotes($siteId, VoteType::ANY, null, $fromDate);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }
    
    public function membersAction()
    {
        $result = array('success' => false);
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
            $members = $this->services->getUserService()->findSiteMembers($siteId, UserType::ANY, false, $from, $to, array($orderBy => $order), true);
            $members->setCurrentPageNumber($page);
            $members->setItemCountPerPage($perPage);
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            $table = PaginatedTableFactory::createMembersTable($members, false);
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/table.phtml', 
                    array(
                        'table' => $table, 
                        'data' => array('siteId' => $siteId)
                    )
                );
            }
        }        
        return new JsonModel($result);
    }
    
    public function pagesAction()
    {
        $result = array('success' => false);
        $siteId = -1;
        $from = null;
        $to = null;
        if ($this->getCommonParams($siteId, $from, $to)) {
            $page = (int)$this->params()->fromQuery("page", 1);
            $perPage = (int)$this->params()->fromQuery("perPage", 10);
            $orderBy = $this->params()->fromQuery('orderBy', DbViewPages::CREATIONDATE);
            if (!DbViewPages::hasField($orderBy)) {
                $orderBy = DbViewPages::CREATIONDATE;                
            }
            $order = $this->params()->fromQuery('ascending', true);
            if ($order) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
            $pages = $this->services->getPageService()->findSitePages($siteId, PageType::ANY, $from, $to, array($orderBy => $order), true);
            $pages->setCurrentPageNumber($page);
            $pages->setItemCountPerPage($perPage);
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            $table = PaginatedTableFactory::createPagesTable($pages, false);
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/table.phtml', 
                    array(
                        'table' => $table, 
                        'data' => array()
                    )
                );
            }
        }        
        return new JsonModel($result);
    }
    
}
