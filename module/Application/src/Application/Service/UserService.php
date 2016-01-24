<?php

namespace Application\Service;

use Application\Mapper\UserMapperInterface;
use Application\Utils\UserType;

class UserService implements UserServiceInterface
{    
    const ACTIVITY_SPAN = 6; // Months
    
    /**
     *
     * @var UserMapperInterface
     */
    protected $mapper;
    
    protected function getActiveDate()
    {
        $lastActive = new \DateTime();
        $lastActive->sub(new \DateInterval(sprintf("P%dM", array(self::ACTIVITY_SPAN))));
        return $lastActive;
    }
    
    public function __construct(UserMapperInterface $mapper) 
    {
        $this->mapper = $mapper;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function find($id) 
    {
        return $this->mapper->find($id);
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->mapper->findAll();
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
        return $this->mapper->countSiteMembers($siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
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
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $types, $lastActive, $joinedAfter, $joinedBefore);
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
        return $this->mapper->findSiteMembers($siteId, $types, $lastActive, $joinedAfter, $joinedBefore, $order, $paginated);
    }    
}
