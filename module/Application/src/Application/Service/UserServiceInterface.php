<?php

namespace Application\Service;

use Application\Utils\UserType;
use Application\Utils\QueryAggregateInterface;

interface UserServiceInterface 
{
    /**
     * Returns a single user
     * 
     * @param int|string $id User's WikidotId
     * @return UserInterface
     * @throws \InvalidArgumentException
     */
    public function find($id);
    
    /**
     * Returns all users
     * @param array[string]string
     * @param bool $paginated
     * @return array|UserInterface[]
     */
    public function findAll($conditions = null, $paginated = false);
    
    /**
     * Return users with name matching the string
     * @param string $mask
     * @return UserInterface[]
     */
    public function findByName($mask);    
    
    /**
     * Returns all users who are members of the site
     * @param int $siteId Id of a site
     * @param int $types Bitmask - types of user to retrieve (constants from \Application\Utils\UserType)
     * @param bool $active Count only active users
     * @param \DateTime $joinedAfter Only users who joined after the date
     * @param \DateTime $joinedBefore Only users who joined before the date
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return \Zend\Paginator\Paginator|MembershipInterface[]
     */    
    public function findSiteMembers($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null, $order = null, $paginated = false);
    
    /**
     * Returns number of members of the site
     * @param int $siteId Id of a site
     * @param int $types Bitmask - types of user to retrieve (constants from \Application\Utils\UserType)
     * @param bool $active Count only active users
     * @param \DateTime $joinedAfter Only users who joined after the date
     * @param \DateTime $joinedBefore Only users who joined before the date
     * @return int
     */
    public function countSiteMembers($siteId, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);

    /**
     * Returns a list of all users who are members of site or has any kind of activity on the site
     * @param int $siteId
     * @param array[string]int $order
     * @param bool $paginated
     * @return UserInterface[]|Paginator
     */
    public function findUsersOfSite($siteId, $order = null, $paginated = false);
    
    /**
     * Get a list of authorships by user on site
     * @param int $userId
     * @param int $siteId
     * @param array[string]int $order
     * @param bool $paginated
     * @return Zend\Paginator\Paginator|AuthorshipInterface[]
     */
    public function findAuthorshipsOfUser($userId, $siteId, $order = null, $paginated = false, $page = 1, $perPage = 10);
    
    /**
     * Get an aggregated results from membership
     * P.e. Get a number of membership, average rating etc.
     * @param int $siteId Id of a site
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates Array of Aggregate objects showing how results should be aggregated and grouped
     * @param int $types Bitmask - types of user to retrieve (constants from \Application\Utils\UserType)
     * @param bool $active Count only active users
     * @param \DateTime $joinedAfter Only users who joined after the date
     * @param \DateTime $joinedBefore Only users who joined before the date
     * @return array(array(string => mixed)) Array of FieldName => Value pairs
     */    
    public function getMembershipAggregated($siteId, $aggregates, $types = UserType::ANY, $active = false, \DateTime $joinedAfter = null, \DateTime $joinedBefore = null);
    
    /**
     * Get an aggregated results from activities
     * P.e. Get a number of voters
     * @param int $siteId
     * @param array[string]string
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getActivitiesAggregated($siteId, $conditions, $aggregates, $order = null, $paginated = false);
    
    /**
     * Get a single aggregated value (no grouping)
     * P.e. Get a number of voters
     * @param int $siteId
     * @param array[string]string $conditions
     * @param \Application\Utils\QueryAggregateInterface $aggregate
     * @return mixed
     */
    public function getActivitiesAggregatedValue($siteId, $conditions, QueryAggregateInterface $aggregate);
    
    /**
     * 
     * @param int $site
     * @param array[string]int $order
     * @param bool $paginated
     * @return Zend\Paginator\Paginator|AuthorSummaryInterface[]
     */
    public function findAuthorSummaries($siteId, $order = null, $paginated = false);
}
