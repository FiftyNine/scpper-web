<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Application\Utils\QueryAggregateInterface;

/**
 *
 * @author Alexander
 */
interface UserActivityMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns user activity information for a specified user and site
     * @param int $userId
     * @param int $siteId
     * @return \Application\Model\UserActivityInterface
     * @throws \InvalidArgumentException
     */
    public function findUserActivity($userId, $siteId);
    
    /**
     * Returns activities information for a specified user on all sites
     * @param int $userId
     * @return \Application\Model\UserActivityInterface[]
     */
    public function findUserActivities($userId);

    /**
     * Returns activities for a specified site
     * @param int $siteId
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects* 
     * @return Zend\Paginator\Paginator|\Application\Model\UserActivityInterface[]
     */
    public function findSiteActivities($siteId, $order = null, $paginated = false, $asArray = false);
    
    /**
     * Get aggregated results from activities
     * P.e. Get a number of voters
     * @param array[string]string $conditions
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($conditions, $aggregates, $order = null, $paginated = false);

    /**
     * Get a single aggregated value (no grouping)
     * P.e. Get a number of voters
     * @param int $siteId
     * @param array[string]string $conditions
     * @param \Application\Utils\QueryAggregateInterface $aggregate
     * @return mixed
     */
    public function getAggregatedValue($conditions, QueryAggregateInterface $aggregate);
    
}
