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
    public function getAggregatedValues($siteId, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $order = null, $paginated = false);
}