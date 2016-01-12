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
     * Get a number of cast votes, grouped by period
     * 
     * @param int $siteId
     * @param \DateTime $castAfter
     * @param \DateTime $castBefore
     * @return array(\DateTime, int)
     */
    public function countCastVotes($siteId, \DateTime $castAfter, \DateTime $castBefore);    
}