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
use Application\Utils\DbConsts\DbViewRevisions;

/**
 * Description of PagesController
 *
 * @author Alexander
 */
class EditorsController extends AbstractActionController
{
    /**
     *
     * @var Application\Service\ServiceHubInterface
     */    
    protected $services;
    
    /**
     * 
     * @param int $siteId
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\TableInterface
     */
    protected function getEditorsTable($siteId, $orderBy, $order, $page, $perPage)
    {
        $aggregates = array(
            new Aggregate(DbViewRevisions::USERWIKIDOTNAME, Aggregate::NONE, null, true),
            new Aggregate(DbViewRevisions::USERDISPLAYNAME, Aggregate::NONE, null, true),
            new Aggregate('*', Aggregate::COUNT, 'Revisions'),
        );
        $editors = $this->services->getRevisionService()->getAggregatedValues($siteId, $aggregates, null, null, array($orderBy => $order), true);
        $editors->setCurrentPageNumber($page);
        $editors->setItemCountPerPage($perPage); 
        $table = PaginatedTableFactory::createEditorsTable($editors);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;
    }
    
    public function __construct(HubServiceInterface $services) 
    {
        $this->services = $services;
    }
    
    public function editorsAction()
    {
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $editors = $this->getEditorsTable($siteId, 'Revisions', Order::DESCENDING, 1, 10);
        $result = array(
            'site' => $site,
            'table' => $editors,
            'total' => $this->services->getRevisionService()->countSiteRevisions($siteId)
        );
        return new ViewModel($result);
    }  
       
    public function editorListAction()
    {
        $result = array('success' => false);
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', 'Revisions');
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }
        $table = $this->getEditorsTable($siteId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
        if ($renderer) {
            $result['success'] = true;                
            $result['content'] = $renderer(
                'partial/tables/table.phtml', 
                array(
                    'table' => $table,
                    'data' => array(
                        'total' => $this->services->getRevisionService()->countSiteRevisions($siteId)
                    )
                )
            );
        }
        return new JsonModel($result);        
    }    
}
