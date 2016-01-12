<?php

namespace Application\Mapper;

use Application\Utils\VoteType;
use Application\Utils\DateGroupType;

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
     * Get a number of cast votes, grouped by period
     * 
     * @param int $siteId
     * @param \DateTime $castAfter
     * @param \DateTime $castBefore
     * @param int $groupBy
     * @return array(\DateTime, int)
     */
    public function countCastVotes($siteId, \DateTime $castAfter, \DateTime $castBefore, $groupBy = DateGroupType::DAY);
}