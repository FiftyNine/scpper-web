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
use Application\Service\HubServiceInterface;
use Application\Utils\Aggregate;
use Application\Utils\DateAggregate;
use Application\Utils\DbConsts\DbViewVotes;

/**
 * Description of PageController
 *
 * @author Alexander
 */
class PageController extends AbstractActionController 
{
    /**
     *
     * @var Application\Service\ServiceHubInterface
     */    
    protected $services;

    public function __construct(HubServiceInterface $services) 
    {
        $this->services = $services;
    }

    public function pageAction()
    {
        $pageId = (int)$this->params()->fromRoute('pageId');
        $page = $this->services->getPageService()->find($pageId);
        if (!$page) {
            return $this->notFoundAction();
        }
        return new ViewModel(array(
           'page' => $page,
        ));
    }
    
    public function ratingChartAction()
    {
        $pageId = (int)$this->params()->fromQuery('pageId');
        $byDate = new DateAggregate(DbViewVotes::DATETIME, 'Date');
        $count = new Aggregate('*', Aggregate::COUNT, 'Votes');
        $votes = $this->services->getVoteService()->getAggregatedForPage($pageId, array($byDate, $count), true);
        $resVotes = array();
        foreach ($votes as $vote) {
            $resVotes[] = array($vote['Date']->format(\DateTime::ISO8601), $vote['Votes']);
        }
        $revisions = $this->services->getRevisionService()->findRevisionsOfPage($pageId);
        $resRevisions = array();
        foreach ($revisions as $rev) {
            $resRevisions[] = array($rev->getDateTime()->format(\DateTime::ISO8601), $rev);
        }
        return new JsonModel(array(
            'success' => true,
            'votes' => $resVotes,
            'revisions' => $resRevisions,
        ));
    }
}
