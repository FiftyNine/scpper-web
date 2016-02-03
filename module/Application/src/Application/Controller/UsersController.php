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
use Application\Utils\Order;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\AuthorSummaryConsts;

/**
 * Description of UsersController
 *
 * @author Alexander
 */
class UsersController extends AbstractActionController
{
    /**
     *
     * @var HubServiceInterface 
     */
    protected $services;

    /**
     * Get a paginated table with users for a selected site
     * @param int $siteId
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return \Application\Component\PaginatedTable\TableInterface;
     */
    protected function getUsersTable($siteId, $orderBy, $order, $page, $perPage)
    {
        $users = $this->services->getUserService()->findUsersOfSite($siteId, array($orderBy => $order), true);
        $users->setItemCountPerPage($perPage);
        $users->setCurrentPageNumber($page);
        $table = \Application\Factory\Component\PaginatedTableFactory::createUsersTable($users);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;
    }

    /**
     * Get a paginated table with authors for a selected site
     * @param int $siteId
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return \Application\Component\PaginatedTable\TableInterface;
     */
    protected function getAuthorsTable($siteId, $orderBy, $order, $page, $perPage)
    {
        $authors = $this->services->getUserService()->findAuthorSummaries($siteId, array($orderBy => $order), true);
        $authors->setItemCountPerPage($perPage);
        $authors->setCurrentPageNumber($page);
        $userIds = array();
        $authorById = array();
        foreach ($authors as $author) {            
            $userIds[] = $author->getUserId();
            $authorById[$author->getUserId()] = $author;
        }
        $users = $this->services->getUserService()->findAll(array(
            sprintf('%s IN (%s)',  DbViewUsers::USERID, implode(',', $userIds))
        ));
        foreach ($users as $user) {
            $authorById[$user->getId()]->setUser($user);
        }
        $table = \Application\Factory\Component\PaginatedTableFactory::createAuthorsTable($authors);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;
    }
    
    public function __construct(HubServiceInterface $hubService) 
    {
        $this->services = $hubService;
    }

    public function usersAction()
    {
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $users = $this->getUsersTable($siteId, DbViewMembership::TABLE.'_'.DbViewMembership::JOINDATE, Order::DESCENDING, 1, 10);
        $authors = $this->getAuthorsTable($siteId, AuthorSummaryConsts::PAGES, Order::DESCENDING, 1, 10);
        $result = array(
            'site' => $site,
            'usersTable' => $users,
            'authorsTable' => $authors
        );
        return new ViewModel($result);
    }
    
    public function userListAction()
    {
        $result = array('success' => false);
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewMembership::JOINDATE);
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }
        $table = $this->getUsersTable($siteId, $orderBy, $order, $page, $perPage);
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
    
    public function authorListAction()
    {
        $result = array('success' => false);
        $siteId = (int)$this->params()->fromQuery('siteId', $this->services->getUtilityService()->getSiteId());
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', AuthorSummaryConsts::PAGES);
        $order = $this->params()->fromQuery('ascending', false);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }
        $table = $this->getAuthorsTable($siteId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
        if ($renderer) {
            $result['success'] = true;                
            $result['content'] = $renderer(
                'partial/tables/table.phtml', 
                array(
                    'table' => $table, 
                )
            );
        }
        return new JsonModel($result);        
    }    
}
