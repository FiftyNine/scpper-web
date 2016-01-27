<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Application\Utils\UserType;

/**
 *
 * @author Alexander
 */
interface MembershipMapperInterface extends SimpleMapperInterface
{    
    /**
     * Returns all memberships of a user
     * @param int $userId
     * @return MembershipInterface[]
     */
    public function findUserMemberships($userId);    
    
    /**
     * Returns all users who are members of the site
     * @param int $siteId Id of a site
     * @param int $types Bitmask - types of user to retrieve (constants from \Application\Utils\UserType)
     * @param \DateTime $lastActive Only users who were active after the date
     * @param \DateTime $joinedAfter Only users who joined after the date
     * @param \DateTime $joinedBefore Only users who joined before the date
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return \Zend\Paginator\Paginator|MembershipInterface[]
     */
    public function findSiteMembers($siteId, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $order = null, $paginated = false);    
                  
    /**
     * Returns number of members of the site
     * @param int $siteId Id of a site
     * @param int $types Bitmask - types of user to retrieve (constants from \Application\Utils\UserType)
     * @param \DateTime $lastActive Only users who were active after the date
     * @param \DateTime $joinedAfter Only users who joined after the date
     * @param \DateTime $joinedBefore Only users who joined before the date
     * @return int
     */
    public function countSiteMembers($siteId, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);
        
    /**
     * Get an aggregated results from memberships
     * P.e. Get a number of users, average rating etc.
     * @param int $siteId Id of a site
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates Array of Aggregate objects showing how results should be aggregated and grouped
     * @param int $types Bitmask - types of user to retrieve (constants from \Application\Utils\UserType)
     * @param \DateTime $lastActive Only users who were active after the date
     * @param \DateTime $joinedAfter Only users who joined after the date
     * @param \DateTime $joinedBefore Only users who joined before the date
     * @return array(array(string => mixed)) Array of FieldName => Value pairs
     */
    public function getAggregatedValues($siteId, $aggregates, $types = UserType::ANY, \DateTime $lastActive = null, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);    
}
