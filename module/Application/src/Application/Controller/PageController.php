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
use Application\Utils\Aggregate;
use Application\Utils\DateAggregate;
use Application\Utils\Order;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewRevisions;

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

    protected function getRevisionsTable($pageId, $orderBy, $order, $page, $perPage)
    {
        $revisions = $this->services->getRevisionService()->findRevisionsOfPage($pageId, true, $page, $perPage);
        $table = PaginatedTableFactory::createRevisionsTable($revisions);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;        
    }
    
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
            'revisions' => $this->getRevisionsTable($pageId, DbViewRevisions::REVISIONINDEX, Order::DESCENDING, 1, 10)
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
    
    public function revisionListAction()
    {
        $pageId = (int)$this->params()->fromQuery('pageId');
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewRevisions::REVISIONINDEX);
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }    
        $table = $this->getRevisionsTable($pageId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
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
        return new JsonModel($result);                
    }
}
