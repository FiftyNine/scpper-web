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
     * Return votes for a certain site
     * @param int $siteId
     * @param array[string]int
     * @param bool $paginated
     * @return Zend\Paginator\Paginator|VoteInterface[]
     */
    public function findSiteVotes($siteId, $order = null, $paginated = false, $page = -1, $perPage = -1);    
    
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
     * Return votes by a suer
     * @param int $userId
     * @param int $siteId
     * @param array[string]int
     * @param bool $paginated
     * @param int $page Number of page of paginator
     * @param int $perPage Items per page of paginator
     * @return Zend\Paginator\Paginator|VoteInterface[]
     */
    public function findVotesOfUser($userId, $siteId, $order = null, $paginated = false, $page = -1, $perPage = -1);
    
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
     * Gets favorite authors of user
     * @param int $userId
     * @param int $siteId
     * @param bool $orderByRatio Order by total or by ratio
     * @param bool $paginated
     * @return array[array[string]mixed]
     */
    public function getUserFavoriteAuthors($userId, $siteId, $orderByRatio, $paginated = false);
    
    /**
     * Get favorite tags of user
     * @param int $userId
     * @param int $siteId
     * @param bool $orderByRatio Order by total or by ratio
     * @param bool $paginated
     * @return array[array[string]mixed]
     */
    public function getUserFavoriteTags($userId, $siteId, $orderByRatio, $paginated = false);
    
    /**
     * Get biggest fans of user
     * @param int $userId
     * @param int $siteId
     * @param bool $orderByRatio Order by total or by ratio
     * @param bool $paginated
     * @return array[array[string]mixed]
     */
    public function getUserBiggestFans($userId, $siteId, $orderByRatio, $paginated = false);
    
    /**
     * Get an aggregated results from votes
     * P.e. Get a number of votes
     * @param int $userId
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param bool $paginated
     * @return array[array[string]mixed]
     */
    public function getAggregatedVotesOnUser($userId, $siteId, $aggregates, $order = null, $paginated = false);    
    
    
    /**
     * Get an aggregated results from votes for a rating chart
     * @param int $pageId
     * @return array[array[string]mixed]
     */
    public function getChartDataForPage($pageId);

    /**
     * Get an aggregated results from votes for a rating chart
     * @param int $userId
     * @param int $siteId
     * @return array[array[string]mixed]
     */
    public function getChartDataForUser($userId, $siteId);
}
