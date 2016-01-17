<?php

namespace Application\Mapper;

use Application\Utils\DateGroupType;

interface RevisionMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns number of site revisions
     * @param int $siteId
     * @param \DateTime $createdAfter Count only revisions created after date
     * @return int
     */
    public function countSiteRevisions($siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null);        
    
    /**
     * Get an aggregated results from revisions, grouped by creation date
     * P.e. Get a number of revisions
     * 
     * @param int $siteId
     * @param \Application\Utils\Aggregate[] $aggregates
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @param int $groupBy
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore, $groupBy = DateGroupType::DAY);    
}