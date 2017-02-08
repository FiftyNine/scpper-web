<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Application\Service\HubServiceInterface;
use Application\Factory\Component\PaginatedTableFactory;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\Order;
use Application\Form\SearchForm;

/**
 * Description of SearchController
 *
 * @author Alexander
 */
class SearchController extends AbstractActionController
{
    /**
     *
     * @var HubServiceInterface 
     */
    protected $services;
    

    /**
     * @param string $mask
     * @param int[] $siteIds
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\TableInterface
     */
    protected function getPagesTable($mask, $siteIds, $orderBy = null, $order = null, $page = 1, $perPage = 10)
    {
        if ($order === null) {
            $sortOrder = null;
        } else {
            $sortOrder = [$orderBy => $order];
        }
        $pages = $this->services->getPageService()->findByName($mask, $siteIds, $sortOrder, true);
        $pages->setItemCountPerPage($perPage);        
        $pages->setCurrentPageNumber($page);
        $table = PaginatedTableFactory::createPagesTable($pages);
        if ($siteIds === null) {
            $table->getColumns()->findColumn('Branch')->setHidden(false);
        }
        if ($sortOrder) {
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        }
        return $table;
    }

    /**
     * @param string $mask
     * @param int[] $siteId
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\TableInterface
     */
    protected function getUsersTable($mask, $siteId, $orderBy = null, $order = null, $page = 1, $perPage = 10)
    {
        if ($order === null) {
            $sortOrder = null;
        } else {
            $sortOrder = [$orderBy => $order];
        }
        if ($siteId) {
            $users = $this->services->getUserService()->findUsersOfSiteByName($siteId, $mask, $sortOrder, true);
            $table = \Application\Factory\Component\PaginatedTableFactory::createSiteUsersTable($users);
        } else {
            $users = $this->services->getUserService()->findByName($mask, $sortOrder, true);
            $table = \Application\Factory\Component\PaginatedTableFactory::createUsersTable($users);
        }
        $users->setItemCountPerPage($perPage);
        $users->setCurrentPageNumber($page);
        if ($sortOrder) {
            $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        }
        return $table;
    }
    
    public function __construct(HubServiceInterface $hubService) 
    {
        $this->services = $hubService;
    }

    public function searchAction()
    {        
        $request = $this->getRequest();
        $form = $this->services->getUtilityService()->getSearchForm();
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $result = array('form' => $form);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $text = $form->get(SearchForm::TEXT_FIELD_NAME)->getValue();
                $text = trim($text);
                if (mb_strlen($text) >= 3) {
                    $result['pages'] = $this->getPagesTable($text, [$siteId], null, null, 1, 10);
                    $result['users'] = $this->getUsersTable($text, [$siteId], null, null, 1, 10);
                    $result['site'] = $site;
                    $pageCount = $result['pages']->getPaginator()->getTotalItemCount();
                    $userCount = $result['users']->getPaginator()->getTotalItemCount();
                    if ($pageCount === 1 && $userCount === 0) {
                        $page = $result['pages']->getPaginator()->getItem(1);
                        return $this->redirect()->toUrl("/page/{$page->getId()}");
                    } elseif ($pageCount === 0 && $userCount === 1) {
                        $user = $result['users']->getPaginator()->getItem(1);
                        return $this->redirect()->toUrl("/user/{$user->getId()}");
                    }
                }                
            }
        }        
        return new ViewModel($result);
    }    

    public function pageListAction()
    {
        $result = array('success' => false);
        $siteId = $this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        if ($siteId === 'all') {
            $siteIds = null;
        } else {
            $siteIds = [(int)$siteId];
        }
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', null);
        $order = $this->params()->fromQuery('ascending', null);
        $query = $this->params()->fromQuery('query', '');
        if ($order !== null) {
            if ($order) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
        }
        $table = $this->getPagesTable($query, $siteIds, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
        if ($renderer) {
            $result['success'] = true;                
            $result['content'] = $renderer(
                'partial/tables/table.phtml', 
                array(
                    'table' => $table, 
                    'data' => array('siteIds' => $siteIds)
                )
            );
        }
        return new JsonModel($result);        
    }
    
    public function userListAction()
    {
        $result = array('success' => false);
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        if ($siteId === 'all') {
            $siteId = null;
        } else {
            $siteId = (int)$siteId;
        }        
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', null);
        $order = $this->params()->fromQuery('ascending', null);
        $query = $this->params()->fromQuery('query', '');
        if ($order !== null) {
            if ($order) {
                $order = Order::ASCENDING;
            } else {
                $order = Order::DESCENDING;
            }
        }
        $table = $this->getUsersTable($query, $siteId, $orderBy, $order, $page, $perPage);
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
    
    public function autocompleteAction()
    {
        $maxItems = 3;
        $result = ['success' => true];
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $query = $this->params()->fromQuery('query', '');
        $pages = $this->services->getPageService()->findByName($query, [$siteId], [DbViewPages::CLEANRATING => Order::DESCENDING], true);
        $users = $this->services->getUserService()->findUsersOfSiteByName($siteId, $query, null, true);
        $result['pages'] = [];
        $i = 1;        
        foreach ($pages as $page) {
            $result['pages'][] = [
                'id' => $page->getId(),
                'label' => $page->getTitle(),
                'altTitle' => $page->getAltTitle()            
            ];
            $i++;
            if ($i > $maxItems) {
                break;
            }
        }
        $result['users'] = [];
        $i = 1;
        foreach ($users as $user) {
            $result['users'][] = [
                'id' => $user->getId(),
                'label' => $user->getDisplayName()];
            $i++;
            if ($i > $maxItems) {
                break;
            }            
        }
        return new JsonModel($result);
    }    
}
