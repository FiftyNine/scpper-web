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
     * Get a number of created revisions, grouped by period
     * 
     * @param int $siteId
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @param int $groupBy
     * @return array(\DateTime, int)
     */
    public function countCreatedRevisions($siteId, \DateTime $createdAfter, \DateTime $createdBefore, $groupBy = DateGroupType::DAY);    
}