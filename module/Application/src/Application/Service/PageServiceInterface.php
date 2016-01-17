<?php

namespace Application\Service;

use Application\Model\PageInterface;
use Application\Utils\PageType;

interface PageServiceInterface
{
    /**
     * Returns a single page
     * 
     * @param int|string $id Page's WikidotId
     * @return PageInterface
     * @throws \InvalidArgumentException
     */
    public function find($id);
    
    /**
     * Returns all pages
     * 
     * @return array|PageInterface[]
     */
    public function findAll();
    
    /**
     * Returns number of site pages
     * @param int $siteId
     * @param int $type PageType::constant
     * @param \DateTime $createdAfter Count only pages created after date
     * @return int
     */
    public function countSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null);
    
    /**
     * Returns site pages
     * @param int $siteId
     * @param int $type PageType::constant
     * @param \DateTime $createdAfter Only pages created after date
     * @param \DateTime $createdBefore Only pages created before date
     * @return array|PageInterface[]
     */
    public function findSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $offset = 0, $limit = 0);    
    
    /**
     * Get an aggregated results from pages, grouped by period when created
     * P.e. Get a number of pages, average rating etc.
     * 
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore);
    
}