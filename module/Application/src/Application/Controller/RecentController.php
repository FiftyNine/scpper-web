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
use Application\Utils\DateGroupType;
use Application\Utils\UserType;
use Application\Utils\PageType;
use Application\Utils\VoteType;

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
       
    protected function getChartParams(&$siteId, &$fromDate, &$toDate)
    {
        $siteId = $this->params()->fromQuery('siteId', 0);
        $from = $this->params()->fromQuery('fromDate', '2000-01-01');
        $to = $this->params()->fromQuery('toDate', '2020-01-01');
        $fromDate = \DateTime::createFromFormat('Y-m-d', $from);
        $toDate = \DateTime::createFromFormat('Y-m-d', $to);        
        return $from && $to && $siteId > 0;
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
            // Users
            'members' => array(
                'total' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, false, $from, $to),
                'voters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::VOTER, false, $from, $to),
                'contributors' => $this->services->getUserService()->countSiteMembers($siteId, UserType::CONTRIBUTOR, false, $from, $to),
                'posters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::POSTER, false, $from, $to),
                'active' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, true, $from, $to),
            ),
            // Pages
            'pages' => $this->services->getPageService()->countSitePages($siteId, PageType::ANY, $from, $to),
            // Votes
            'votes' => $this->services->getVoteService()->countSiteVotes($siteId, VoteType::ANY, $from, $to),
            // Revisions
            'revisions' => $this->services->getRevisionService()->countSiteRevisions($siteId, $from, $to)
        );          
        return new ViewModel($result);
    }
    
    public function getMemberChartDataAction()
    {
        $result = array('success' => false);
        $siteId = -1;
        $fromDate = null;
        $toDate = null;                
        if ($this->getChartParams($siteId, $fromDate, $toDate)) {
            $joins = $this->services->getUserService()->countSiteMembersGroup($siteId, UserType::ANY, false, $fromDate, $toDate);
            foreach($joins as $join) {
                $result['data'][] = array($join[0]->format(\DateTime::ISO8601), $join[1]);
            }
            $group = DateGroupType::getBestGroupType($fromDate, $toDate);
            $result['group'] = DateGroupType::getGroupName($group);
            $result['starting'] = (int)$this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, false, null, $fromDate);            
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
        if ($this->getChartParams($siteId, $fromDate, $toDate)) {
            $pages = $this->services->getPageService()->countCreatedPages($siteId, $fromDate, $toDate);
            foreach($pages as $page) {
                $result['data'][] = array($page[0]->format(\DateTime::ISO8601), $page[1]);
            }
            $group = DateGroupType::getBestGroupType($fromDate, $toDate);
            $result['group'] = DateGroupType::getGroupName($group);
            $result['starting'] = (int)$this->services->getPageService()->countSitePages($siteId, PageType::ANY, null, $fromDate);
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
        if ($this->getChartParams($siteId, $fromDate, $toDate)) {
            $revs = $this->services->getRevisionService()->countCreatedRevisions($siteId, $fromDate, $toDate);
            foreach($revs as $rev) {
                $result['data'][] = array($rev[0]->format(\DateTime::ISO8601), $rev[1]);
            }
            $group = DateGroupType::getBestGroupType($fromDate, $toDate);
            $result['group'] = DateGroupType::getGroupName($group);
            $result['starting'] = (int)$this->services->getRevisionService()->countSiteRevisions($siteId, null, $fromDate);
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
        if ($this->getChartParams($siteId, $fromDate, $toDate)) {
            $votes = $this->services->getVoteService()->countCastVotes($siteId, $fromDate, $toDate);
            foreach($votes as $vote) {
                $result['data'][] = array($vote[0]->format(\DateTime::ISO8601), $vote[1]);
            }
            $group = DateGroupType::getBestGroupType($fromDate, $toDate);
            $result['group'] = DateGroupType::getGroupName($group);
            $result['starting'] = (int)$this->services->getVoteService()->countSiteVotes($siteId, VoteType::ANY, null, $fromDate);
            $result['success'] = true;
        }
        return new JsonModel($result);
    }    
}
