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
use Application\Utils\DbConsts\DbViewFans;
use Application\Utils\DbConsts\DbViewPagesAll;


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
    
    protected function getFans($userId, $siteId, $byRatio, $limit = 10)
    {
        $fans = $this->services->getVoteService()->getUserBiggestFans($userId, $siteId, $byRatio, true);
        $fans->setCurrentPageNumber(1);
        $fans->setItemCountPerPage($limit);
        $result = [];
        $userPrototype = new \Application\Model\User($this->getServiceLocator()->get('UserActivityMapper'), $this->getServiceLocator()->get('MembershipMapper'));
        foreach ($fans as $fan) {
            $user = clone $userPrototype;
            $user->setId($fan[DbViewFans::USERID]);
            $user->setDisplayName($fan[DbViewFans::USERDISPLAYNAME]);
            $user->setDeleted($fan[DbViewFans::USERDELETED]);
            $result[] = [                
                'user' => $user,
                'positive' => $fan[DbViewFans::POSITIVE],
                'negative' => $fan[DbViewFans::NEGATIVE]
            ];
        }
        return $result;
    }

    protected function getFavoriteAuthors($userId, $siteId, $byRatio, $limit = 10)
    {
        $favs = $this->services->getVoteService()->getUserFavoriteAuthors($userId, $siteId, $byRatio, true);
        $favs->setCurrentPageNumber(1);
        $favs->setItemCountPerPage($limit);
        $result = [];
        $userPrototype = new \Application\Model\User($this->getServiceLocator()->get('UserActivityMapper'), $this->getServiceLocator()->get('MembershipMapper'));
        foreach ($favs as $fav) {
            $user = clone $userPrototype;
            $user->setId($fav[DbViewFans::AUTHORID]);
            $user->setDisplayName($fav[DbViewFans::AUTHORDISPLAYNAME]);
            $user->setDeleted($fav[DbViewFans::AUTHORDELETED]);
            $result[] = [
                'user' => $user,
                'positive' => $fav[DbViewFans::POSITIVE],
                'negative' => $fav[DbViewFans::NEGATIVE]
            ];
        }
        return $result;
    }
    
    protected function getFavoriteTags($userId, $siteId, $byRatio, $limit = 10)
    {
        $tags = $this->services->getVoteService()->getUserFavoriteTags($userId, $siteId, $byRatio, true);
        $tags->setCurrentPageNumber(1);
        $tags->setItemCountPerPage($limit);
        $result = [];
        foreach ($tags as $tag) {
            $result[] = [
                'tag' => $tag['Tag'],
                'positive' => $tag['Positive'],
                'negative' => $tag['Negative']
            ];
        }
        return $result;
    }
    
    protected function getPagesTable($userId, $siteId, $deleted, $orderBy, $order, $page, $perPage)
    {
        $pages = $this->services->getPageService()->findPagesByUser($userId, $siteId, $deleted, [$orderBy => $order], true, $page, $perPage);
        $pages->setCurrentPageNumber($page);
        $pages->setItemCountPerPage($perPage);
        $table = PaginatedTableFactory::createPagesTable($pages);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
        return $table;
    }

    protected function getVotesTable($userId, $siteId, $orderBy, $order, $page, $perPage)
    {
        $votes = $this->services->getVoteService()->findVotesOfUser($userId, $siteId, [$orderBy => $order], true, $page, $perPage);
        $votes->setCurrentPageNumber($page);
        $votes->setItemCountPerPage($perPage);
        $table = PaginatedTableFactory::createUserVotesTable($votes);
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
        try {
            $user = $this->services->getUserService()->find($userId);
        } catch (\InvalidArgumentException $e) {
            return $this->notFoundAction();
        }
        if (!$user) {
            return $this->notFoundAction();
        }
        return new ViewModel([
            'user' => $user,
            'site' => $site,
            'pages' => $this->getPagesTable($userId, $siteId, null, DbViewPagesAll::CREATIONDATE, ORDER::DESCENDING, 1, 10),
            'deleted' => $this->services->getUserService()->countAuthorshipsOfUser($userId, $siteId, true),
            'fans' => $this->getFans($userId, $siteId, true),
            'tags' => $this->getFavoriteTags($userId, $siteId, true),
            'authors' => $this->getFavoriteAuthors($userId, $siteId, true),
            'allFavorites' => false,
            'votes' => $this->getVotesTable($userId, $siteId, DbViewVotes::DATETIME, ORDER::DESCENDING, 1, 10)
        ]);
    }

    public function apiUserAction()
    {
        $userId = (int)$this->params()->fromQuery('id');
        try {
            $user = $this->services->getUserService()->find($userId);
        } catch (\InvalidArgumentException $e) {
            return new JsonModel(['error' => 'User not found']);
        }
        return new JsonModel($user->toArray());
    }
    
    public function ratingChartAction()
    {
        $result = ['success' => false];
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $user = $this->services->getUserService()->find($userId);
        if ($user) {
            $byDate = new DateAggregate(DbViewVotes::DATETIME, 'Date');
            $count = new Aggregate(DbViewVotes::VALUE, Aggregate::SUM, 'Votes');
            $votes = $this->services->getVoteService()->getAggregatedVotesOnUser($userId, $siteId, [$byDate, $count]);
            $resVotes = [];
            foreach ($votes as $vote) {
                $resVotes[] = [$vote['Date']->format(\DateTime::ISO8601), (int)$vote['Votes']];
            }        
            $authorships = $this->services->getUserService()->findAuthorshipsOfUser($userId, $siteId, false);
            $milestones = [];
            foreach ($authorships as $auth) {
                $name = $auth->getPage()->getTitle();
                if (mb_strlen($name) > 11) {
                    $name = mb_substr($name, 0, 8).'...';
                }
                $milestones[] = [
                    $auth->getPage()->getCreationDate()->format(\DateTime::ISO8601), 
                    [
                        'name' => $name,
                        'text' => $auth->getPage()->getTitle()
                    ]
                ];
            }
            $result = [
                'success' => true,
                'votes' => $resVotes,
                'milestones' => $milestones,
            ];
        }
        return new JsonModel($result);
    }    
    
    public function pageListAction()
    {
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewPagesAll::CREATIONDATE);
        $order = $this->params()->fromQuery('ascending', false);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }    
        $table = $this->getPagesTable($userId, $siteId, null, $orderBy, $order, $page, $perPage);
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
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewVotes::DATETIME);
        $order = $this->params()->fromQuery('ascending', false);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }    
        $table = $this->getVotesTable($userId, $siteId, $orderBy, $order, $page, $perPage);
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
    
    public function favoritesAction()
    {
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $site = $this->services->getSiteService()->find($siteId);
        $orderByRatio = (bool)$this->params()->fromQuery('orderByRatio');
        $all = (bool)$this->params()->fromQuery('all');
        if ($all) {
            $limit = 100000;
        } else {
            $limit = 10;
        }
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
        if ($renderer) {
            $result['success'] = true;   
            $fans = $this->getFans($userId, $siteId, $orderByRatio, $limit);
            $favorites = $this->getFavoriteAuthors($userId, $siteId, $orderByRatio, $limit);
            $tags = $this->getFavoriteTags($userId, $siteId, $orderByRatio, $limit);
            $result['content'] = $renderer(
                'application/user/partial/favorites.phtml', 
                [
                    'byRatio' => $orderByRatio,
                    'hasVotes' => count($favorites),
                    'hideVotes' => $site->getHideVotes(),
                    'isAuthor' => count($fans),
                    'authors' => $favorites,
                    'tags' => $tags,
                    'fans' => $fans,
                    'all' => $all
                ]
            );
        }
        return new JsonModel($result);                                
    }   
    
    public function searchAction()
    {
        $maxItems = 50;
        $result = ['success' => true];
        $query = $this->params()->fromQuery('query', '');
        $users = $this->services->getUserService()->findByName($query, null, true);
        $users->setItemCountPerPage($maxItems);
        $result['users'] = [];
        foreach ($users as $user) {
            $result['users'][] = [
                'id' => $user->getId(),
                'label' => $user->getDisplayName()];
        }
        return new JsonModel($result);
    }      
}
