<?php

namespace Application\Mapper;

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
     * Returns revisions of a page
     * @param int $pageId
     * @param array[string]int
     * @param bool $paginated
     * @return RevisionInterface[]|\Zend\Paginator\Paginator
     */
    public function findRevisionsOfPage($pageId, $order = null, $paginated = false);
    
    /**
     * Get an aggregated results from revisions
     * P.e. Get a number of revisions
     * 
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $order = null, $paginated = false);    
}