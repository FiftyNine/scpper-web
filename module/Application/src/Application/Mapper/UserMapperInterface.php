<?php

namespace Application\Mapper;

use Application\Model\UserInterface;
use Application\Utils\DateGroupType;
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
     * @param int $offset
     * @param int $limit
     * @return UserInterface[]|array
     */
    public function findSiteMembers($siteId, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $offset = 0, $limit = 0);
      
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
     * Get a number of users who joined the site, grouped by period
     * @param int $siteId
     * @param int $types
     * @param \DateTime $lastActive
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @param type $groupBy
     * @return array(array(\DateTime, int))
     */
    public function countSiteMembersGroup($siteId, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $groupBy = DateGroupType::DAY);
}