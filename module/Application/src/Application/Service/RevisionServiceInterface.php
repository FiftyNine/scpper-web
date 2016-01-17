<?php

namespace Application\Service;

use Application\Model\RevisionInterface;

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
     * @param \DateTime $createdBefore Count only revisions created before date
     * @return int
     */
    public function countSiteRevisions($siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null);
    
    /**
     * Get an aggregated votes from revisions, grouped by creation date
     * P.e. Get a number of votes
     * 
     * @param int $siteId
     * @param \Application\Utils\Aggregate[] $aggregates
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore);    
}