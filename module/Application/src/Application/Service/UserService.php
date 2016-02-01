<?php

namespace Application\Service;

use Application\Mapper\UserMapperInterface;
use Application\Mapper\MembershipMapperInterface;
use Application\Utils\UserType;

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
    
    public function __construct(
            UserMapperInterface $userMapper,
            MembershipMapperInterface $membershipMapper
    ) 
    {
        $this->userMapper = $userMapper;
        $this->membershipMapper = $membershipMapper;
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
    public function findAll()
    {
        return $this->userMapper->findAll();
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
    public function getAggregatedValues($siteId, $aggregates, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null)
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
}
