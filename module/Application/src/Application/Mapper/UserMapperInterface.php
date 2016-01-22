<?php

namespace Application\Mapper;

use Application\Model\UserInterface;
use Application\Utils\UserType;

interface UserMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns array of site ids which have user as their member
     * @param int|UserInterface $user
     * @return UserInterface
     */
    public function findUserMembership($user);
        
    /**
     * Returns all users who are members of the site
     * @param int $siteId
     * @param int $types
     * @param \DateTime $lastActive
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @param bool $paginated
     * @return \Zend\Paginator\Paginator|UserInterface[]
     */
    public function findSiteMembers($siteId, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $paginated = false);
      
    /**
     * Returns number of members of the site
     * @param int $siteId
     * @param int $types
     * @param \DateTime $lastActive
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @return int
     */
    public function countSiteMembers($siteId, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);
        
    /**
     * Get an aggregated results from users
     * P.e. Get a number of users, average rating etc.
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param int $types     
     * @param \DateTime $lastActive
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);
}