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
use Application\Factory\Component\PaginatedTableFactory;
use Application\Utils\Order;
use Application\Utils\Aggregate;
use Application\Utils\DbConsts\DbViewVotes;

/**
 * Description of PagesController
 *
 * @author Alexander
 */
class VotesController extends AbstractActionController
{
    /**
     *
     * @var Application\Service\ServiceHubInterface
     */    
    protected $services;
    
    /**
     * 
     * @param int $site
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\TableInterface
     */
    protected function getVotersTable($site, $orderBy, $order, $page, $perPage)
    {
        $aggregates = [
            new Aggregate(DbViewVotes::USERID, Aggregate::NONE, null, true),
            new Aggregate(DbViewVotes::USERNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewVotes::USERDISPLAYNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewVotes::USERDELETED, Aggregate::NONE, null, true),
            new Aggregate('*', Aggregate::COUNT, 'Votes'),
            new Aggregate(DbViewVotes::VALUE, Aggregate::SUM, 'Sum'),
        ];
        $voters = $this->services->getVoteService()->getAggregatedForSite($site->getId(), $aggregates, null, null, [$orderBy => $order], true);
        $voters->setCurrentPageNumber($page);
        $voters->setItemCountPerPage($perPage);
        $table = PaginatedTableFactory::createVotersTable($voters, $site->getHideVotes());
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;
    }

    /**
     * 
     * @param int $siteId
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\TableInterface
     */
    protected function getVotesTable($siteId, $orderBy, $order, $page, $perPage)
    {
        $votes = $this->services->getVoteService()->findSiteVotes($siteId, [$orderBy => $order], true, $page, $perPage);
        $table = PaginatedTableFactory::createVotesTable($votes);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;
    }
    
    public function __construct(HubServiceInterface $services) 
    {
        $this->services = $services;
    }
    
    public function votesAction()
    {
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $voters = $this->getVotersTable($site, 'Votes', Order::DESCENDING, 1, 10);
        $votes = $this->getVotesTable($siteId, DbViewVotes::DATETIME, Order::DESCENDING, 1, 10);
        $result = [
            'site' => $site,
            'votersTable' => $voters,
            'votesTable' => $votes
        ];
        return new ViewModel($result);
    }  
       
    public function voterListAction()
    {
        $result = ['success' => false];
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $site = $this->services->getSiteService()->find($siteId);
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', 'Votes');
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }
        $table = $this->getVotersTable($site, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
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
        return new JsonModel($result);        
    }
    
    public function voteListAction()
    {
        $result = ['success' => false];
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewVotes::DATETIME);
        $order = $this->params()->fromQuery('ascending', false);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }
        $table = $this->getVotesTable($siteId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
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
        return new JsonModel($result);        
    }    
}
