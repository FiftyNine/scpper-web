<?php

namespace Application\Mapper;

use Application\Utils\VoteType;

interface VoteMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns number of site votes
     * @param int $siteId
     * @param int $type
     * @param \DateTime $castAfter Count only votes cast after date
     * @return int
     */
    public function countSiteVotes($siteId, $type = VoteType::ANY, \DateTime $castAfter = null, \DateTime $castBefore = null);
    
    /**
     * Return votes for a certain page
     * @param int $pageId
     * @param array[string]int
     * @param bool $paginated
     * @return Zend\Paginator\Paginator|VoteInterface[]
     */
    public function findVotesOnPage($pageId, $order = null, $paginated = false);
    
    /**
     * Get an aggregated results from votes
     * @param array[string]string
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param \DateTime $castAfter
     * @param \DateTime $castBefore
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($conditions, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $order = null, $paginated = false);
    
    /**
     * Get an aggregated results from votes on specific author
     * @param int $userId
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getAggregatedVotesOnUser($userId, $siteId, $aggregates, $order = null, $paginated = false);
    
    /**
     * Get a list of favorite authors of user
     * @param int $userId
     * @param int $siteId
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getFavoriteAuthors($userId, $siteId, $paginated = false);    
}