<?php

namespace Application\Service;

use Application\Model\PageInterface;
use Application\Utils\PageType;
use Application\Utils\DateGroupType;

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
     * Get a number of created pages, grouped by period
     * 
     * @param int $siteId
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @return array(\DateTime, int)
     */
    public function countCreatedPages($siteId, \DateTime $createdAfter, \DateTime $createdBefore);
}