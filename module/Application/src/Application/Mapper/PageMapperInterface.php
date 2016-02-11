<?php

namespace Application\Mapper;

interface PageMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns number of site pages
     * @param int $siteId Id of a site
     * @param int $type Type of page (constant from Application\Utils\PageType)
     * @param \DateTime $createdAfter Only pages created after date
     * @param \DateTime $createdBefore Only pages created before date
     * @return int
     */
    public function countSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null);
    
    /**
     * Returns site pages
     * @param int $siteId Id of a site
     * @param int $type Type of page (constant from Application\Utils\PageType)
     * @param \DateTime $createdAfter Only pages created after date
     * @param \DateTime $createdBefore Only pages created before date
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return \Zend\Paginator\Paginator|PageInterface[]
     */
    public function findSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $order = null, $paginated = false);
        
    /**
     * Find all pages on the site authored by user
     * @param int $userId
     * @param int $siteId
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return PageInterface[]
     */
    public function findPagesByUser($userId, $siteId, $order = null, $paginated = false);
    
    /**
     * Find translation of a page from other wikis
     * @param int $pageId
     * @return PageInterface[]
     */
    public function findTranslations($pageId);
    
    /**
     * Find page's rank on the site
     * @param int pageId
     * @return int
     */
    public function findPageRank($pageId);
    
    /**
     * Find page tags
     * @param $pageId
     * @return string[]
     */
    public function findPageTags($pageId);
    
    /**
     * Get an aggregated results from pages
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