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
use Application\Utils\DbConsts\DbViewPages;


/**
 * Description of UserController
 *
 * @author Alexander
 */
class UserController extends AbstractActionController 
{
    /**
     *
     * @var Application\Service\ServiceHubInterface
     */    
    protected $services;
    
    protected function getPagesTable($userId, $siteId, $orderBy, $order, $page, $perPage)
    {
        $pages = $this->services->getPageService()->findPagesByUser($userId, $siteId, array($orderBy => $order), true);
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

    public function userAction()
    {
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $userId = (int)$this->params()->fromRoute('userId');
        $user = $this->services->getUserService()->find($userId);
        if (!$user) {
            return $this->notFoundAction();
        }
        return new ViewModel(array(
            'user' => $user,
            'site' => $site,
            'pages' => $this->getPagesTable($userId, $siteId, DbViewPages::CREATIONDATE, ORDER::DESCENDING, 1, 10)
        ));
    }
    
    public function ratingChartAction()
    {
        $result = array('success' => false);
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $user = $this->services->getUserService()->find($userId);
        if ($user) {
            $byDate = new DateAggregate(DbViewVotes::DATETIME, 'Date');
            $count = new Aggregate(DbViewVotes::VALUE, Aggregate::SUM, 'Votes');
            $votes = $this->services->getVoteService()->getAggregatedForUser($userId, $siteId, array($byDate, $count));
            $resVotes = array();
            foreach ($votes as $vote) {
                $resVotes[] = array($vote['Date']->format(\DateTime::ISO8601), (int)$vote['Votes']);
            }        
            $authorships = $this->services->getUserService()->findAuthorshipsOfUser($userId, $siteId);
            $milestones = array();
            foreach ($authorships as $auth) {
                $name = $auth->getPage()->getTitle();
                if (strlen($name) > 11) {
                    $name = substr($name, 0, 8).'...';
                }
                $milestones[] = array(
                    $auth->getPage()->getCreationDate()->format(\DateTime::ISO8601), 
                    array(
                        'name' => $name,
                        'text' => $auth->getPage()->getTitle().' on '.$auth->getPage()->getCreationDate()->format('Y-m-d')
                    )
                );
            }
            $result = array(
                'success' => true,
                'votes' => $resVotes,
                'milestones' => $milestones,
            );
        }
        return new JsonModel($result);
    }    
    
    public function pageListAction()
    {
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewPages::CREATIONDATE);
        $order = $this->params()->fromQuery('ascending', false);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }    
        $table = $this->getPagesTable($userId, $siteId, $orderBy, $order, $page, $perPage);
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
