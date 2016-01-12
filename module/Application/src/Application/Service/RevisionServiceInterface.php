<?php

namespace Application\Service;

use Application\Model\RevisionInterface;
use Application\Utils\DateGroupType;

interface RevisionServiceInterface
{
    /**
     * Returns a single revision
     * 
     * @param int $id Id
     * @return RevisionInterface
     * @throws \InvalidArgumentException
     */
    public function find($id);
    
    /**
     * Returns all revisions
     * 
     * @return array|RevisionInterface[]
     */
    public function findAll();
    
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
     * @return array(\DateTime, int)
     */
    public function countCreatedRevisions($siteId, \DateTime $createdAfter, \DateTime $createdBefore);    
}