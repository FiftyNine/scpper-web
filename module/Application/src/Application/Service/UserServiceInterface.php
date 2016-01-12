<?php

namespace Application\Service;

use Application\Utils\UserType;

interface UserServiceInterface 
{
    /**
     * Returns a single user
     * 
     * @param int|string $site User's WikidotId
     * @return UserInterface
     * @throws \InvalidArgumentException
     */
    public function find($id);
    
    /**
     * Returns all users
     * 
     * @return array|UserInterface[]
     */
    public function findAll();
    
    /**
     * Returns all users who are members of the site
     * @param int $siteId
     * @param int $types
     * @param bool $active Count only active users
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @param int $offset
     * @param int $limit
     * @return UserInterface[]|array
     */
    public function findSiteMembers($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $offset = 0, $limit = 0);
    
    /**
     * Returns number of members of the site
     * @param int $siteId
     * @param int $types
     * @param bool $active Count only active users
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @return int
     */    
    public function countSiteMembers($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);
    
    /**
     * Get a number of users who joined the site, grouped by period
     * @param int $siteId
     * @param int $types
     * @param bool $active Count only active users
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @param type $groupBy
     * @return array(array(\DateTime, int))
     */    
    public function countSiteMembersGroup($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);
}
