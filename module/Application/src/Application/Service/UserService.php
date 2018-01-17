<?php

namespace Application\Service;

use Application\Mapper\UserMapperInterface;
use Application\Mapper\MembershipMapperInterface;
use Application\Mapper\AuthorshipMapperInterface;
use Application\Mapper\UserActivityMapperInterface;
use Application\Utils\QueryAggregateInterface;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\Order;
use Application\Utils\UserType;
use Application\Mapper\PageMapperInterface;

class UserService implements UserServiceInterface
{        
    
    /**
     *
     * @var UserMapperInterface
     */
    protected $userMapper;
    
    /**
     *
     * @var MembershipMapperInterface
     */
    protected $membershipMapper;    
    
    /**
     *
     * @var UserActivityMapperInterface
     */
    protected $activityMapper;
    
    /**
     *
     * @var AuthorshipMapperInterface
     */
    protected $authorshipMapper;    
    
    /**
     *
     * @var PageMapperInterface
     */
    protected $pageMapper;
    
    public function __construct(
            UserMapperInterface $userMapper,
            MembershipMapperInterface $membershipMapper,
            UserActivityMapperInterface $activityMapper,
            AuthorshipMapperInterface $authorshipMapper,
            PageMapperInterface $pageMapper
    ) 
    {
        $this->userMapper = $userMapper;
        $this->membershipMapper = $membershipMapper;
        $this->activityMapper = $activityMapper;
        $this->authorshipMapper = $authorshipMapper;
        $this->pageMapper = $pageMapper;
    }

    /**
     * Get a date after which user counts as active
     * @return \DateTime
     */
    protected function getActiveDate()
    {
        $lastActive = new \DateTime();
        $interval = new \DateInterval(sprintf("P%dM", UserType::ACTIVITY_SPAN));
        $lastActive->sub($interval);
        return $lastActive;
    }
        
    /**
     * 
     * {@inheritDoc}
     */
    public function find($id) 
    {
        return $this->userMapper->find($id);
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function findAll($conditions = null, $paginated = false)
    {
        return $this->userMapper->findAll($conditions, null, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findByName($mask, $order = null, $paginated = false)
    {
        $needle = sprintf('%%%s%%', mb_strtolower($mask));            
        $conditions = [
            sprintf("LOWER(%s) LIKE ?", DbViewUsers::DISPLAYNAME) => $needle,
            // sprintf("%s in (?)", DbViewPages::SITEID) => implode($sites, ',')
        ];
        if ($order === null) {
            $len = strlen($mask);
            $order = [sprintf("ABS(LENGTH(%s) - $len)", DbViewUsers::DISPLAYNAME) => Order::ASCENDING];
        }
        return $this->userMapper->findAll($conditions, $order, $paginated);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function countSiteMembers($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null)
    {
        $lastActive = null;
        if ($active) {            
            $lastActive = $this->getActiveDate();
        }
        return $this->membershipMapper->countSiteMembers($siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getMembershipAggregated($siteId, $aggregates, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null)
    {
        $lastActive = null;
        if ($active) {
            $lastActive = $this->getActiveDate();
        }
        return $this->membershipMapper->getAggregatedValues($siteId, $aggregates, $types, $lastActive, $joinedAfter, $joinedBefore);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function findSiteMembers($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $order = null, $paginated = false)
    {        
        $lastActive = null;
        if ($active) {
            $lastActive = $this->getActiveDate();
        }
        return $this->membershipMapper->findSiteMembers($siteId, $types, $lastActive, $joinedAfter, $joinedBefore, $order, $paginated);
    }    
    
    /**
     * {@inheritdoc}
     */
    public function findUsersOfSite($siteId, $order = null, $paginated = false)
    {
        return $this->userMapper->findUsersOfSite($siteId, $order, $paginated);
    }

    /**
     * {@inheritdoc}
     */
    public function findUsersOfSiteByName($siteId, $name, $order = null, $paginated = false)
    {
        return $this->userMapper->findUsersOfSiteByName($siteId, $name, $order, $paginated);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getActivitiesAggregated($siteId, $conditions, $aggregates, $order = null, $paginated = false)
    {
        $conditions[DbViewUserActivity::SITEID.' = ?'] = $siteId;
        return $this->activityMapper->getAggregatedValues($conditions, $aggregates, $order, $paginated);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getActivitiesAggregatedValue($siteId, $conditions, QueryAggregateInterface $aggregate)
    {
        $conditions[DbViewUserActivity::SITEID.' = ?'] = $siteId;
        return $this->activityMapper->getAggregatedValue($conditions, $aggregate);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findAuthorSummaries($siteId, $order = null, $paginated = false)
    {
        return $this->authorshipMapper->getAuthorSummaries($siteId, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findAuthorshipsOfUser($userId, $siteId, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10)
    {
        $result = $this->authorshipMapper->findAuthorshipsOfUser($userId, $siteId, $deleted, $order, $paginated);
        if ($paginated) {
            $result->setCurrentPageNumber($page);
            $result->setItemCountPerPage($perPage);
        } else {
            $result = iterator_to_array($result);
        }
        if ($result) {
            $pageIds = [];
            foreach ($result as $auth) {
                $pageIds[] = $auth->getPageId();
            }
            $pages = $this->pageMapper->findAll([
                sprintf('%s IN (%s)', DbViewPages::PAGEID, implode(',', $pageIds))
            ]);
            $pageByIds = [];
            foreach ($pages as $page) {
                $pageByIds[$page->getId()] = $page;
            }
            foreach ($result as $auth) {
                $auth->setPage($pageByIds[$auth->getPageId()]);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     */
    public function countAuthorshipsOfUser($userId, $siteId, $deleted = false)
    {
        return $this->authorshipMapper->countAuthorshipsOfUser($userId, $siteId, $deleted);
    }
}
