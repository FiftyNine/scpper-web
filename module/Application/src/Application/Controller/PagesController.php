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
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\PageStatus;
use Application\Utils\Order;

/**
 * Description of PagesController
 *
 * @author Alexander
 */
class PagesController extends AbstractActionController
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
    protected function getPagesTable($siteId, $orderBy, $order, $page, $perPage)
    {
        $pages = $this->services->getPageService()->findSitePages($siteId, PageStatus::ANY, null, null, array($orderBy => $order), true);
        $pages->setCurrentPageNumber($page);
        $pages->setItemCountPerPage($perPage);
        $table = PaginatedTableFactory::createPagesTable($pages);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;
    }
    
    public function __construct(HubServiceInterface $services) 
    {
        $this->services = $services;
    }
    
    public function pagesAction()
    {
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $pages = $this->getPagesTable($siteId, DbViewPages::CLEANRATING, Order::DESCENDING, 1, 10);

        $result = array(
            'site' => $site,
            'table' => $pages
        );
        return new ViewModel($result);
    }  
       
    public function pageListAction()
    {
        $result = array('success' => false);
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewPages::CLEANRATING);
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }
        $table = $this->getPagesTable($siteId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
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
        return new JsonModel($result);        
    }    
}
