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
     * @param \Application\Utils\Aggregate[] $aggregates
     * @param \DateTime $castAfter
     * @param \DateTime $castBefore
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $castAfter, \DateTime $castBefore);
}