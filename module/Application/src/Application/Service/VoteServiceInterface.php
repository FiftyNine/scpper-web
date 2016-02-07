<?php

namespace Application\Service;

use Application\Model\VoteInterface;
use Application\Utils\VoteType;

interface VoteServiceInterface
{  
    /**
     * Returns all votes
     * 
     * @return array|VoteInterface[]
     */
    public function findAll();
    
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
     * @param int $page Number of page of paginator
     * @param int $perPage Items per page of paginator
     * @return Zend\Paginator\Paginator|VoteInterface[]
     */
    public function findVotesOnPage($pageId, $order = null, $paginated = false, $page = -1, $perPage = -1);
    
    /**
     * Get an aggregated results from votes, grouped by period when cast
     * P.e. Get a number of votes
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param \DateTime $castAfter
     * @param \DateTime $castBefore
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getAggregatedForSite($siteId, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $order = null, $paginated = false);
    
    /**
     * Get an aggregated results from votes
     * P.e. Get a number of votes
     * @param int $pageId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param bool onlyClean
     * @return array[array[string]mixed]
     */
    public function getAggregatedForPage($pageId, $aggregates, $onlyClean = false);
    
    /**
     * Get an aggregated results from votes
     * P.e. Get a number of votes
     * @param int $userId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @return array[array[string]mixed]
     */
    public function getAggregatedForUser($userId, $aggregates);
}