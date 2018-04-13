<?php

namespace Application\Service;

use Application\Model\PageInterface;
use Application\Utils\PageStatus;

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
     * Return pages with title or name matching the string
     * @param string $mask
     * @param int[] $sites Site Ids within which search for pages
     * @param bool $deleted Whether to select existing or deleted pages
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @param int $page Number of page of paginator
     * @param int $perPage Items per page of paginator
     * @return \Zend\Paginator\Paginator|PageInterface[]
     */
    public function findByName($mask, $sites, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10);
    
    /**
     * Returns number of site pages
     * @param int $siteId Id of a site
     * @param int $type Type of page (constant from Application\Utils\PageType)
     * @param \DateTime $createdAfter Only pages created after date
     * @param \DateTime $createdBefore Only pages created before date
     * @param bool $deleted Whether to select existing or deleted pages 
     * @return int
     */
    public function countSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false);
    
    /**
     * Returns site pages
     * @param int $siteId Id of a site
     * @param int $type Type of page (constant from Application\Utils\PageType)
     * @param \DateTime $createdAfter Only pages created after date
     * @param \DateTime $createdBefore Only pages created before date
     * @param bool $deleted Whether to select existing or deleted pages
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @param int $page Number of page of paginator
     * @param int $perPage Items per page of paginator
     * @return \Zend\Paginator\Paginator|PageInterface[]
     */
    public function findSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10);
    
    /**
     * Find all pages on the site authored by user
     * @param int $userId
     * @param int $siteId
     * @param bool $deleted Whether to select existing or deleted pages
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @param int $page Number of page of paginator
     * @param int $perPage Items per page of paginator
     * @return PageInterface[]
     */
    public function findPagesByUser($userId, $siteId, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10);
    
    /**
     * Find all pages on the site by the list of tags
     * @param int $siteId
     * @param array(string) $includeTags Tags, page should have
     * @param array(string) $excludeTags Tags, page should NOT have
     * @param bool $all Page must contain all tags
     * @param bool $deleted Whether to select existing or deleted pages
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @param int $page Number of page of paginator
     * @param int $perPage Items per page of paginator
     * @return PageInterface[]
     */
    public function findPagesByTags($siteId, $includeTags, $excludeTags = [], $all = true, $deleted = false, $order = null, $paginated = false, $page = 1, $perPage = 10);
    
    /**
     * Get an aggregated results from pages, grouped by period when created
     * P.e. Get a number of pages, average rating etc.
     * 
     * @param int $siteId
     * @param \Application\Utils\QueryAggregateInterface[] $aggregates
     * @param \DateTime $createdAfter
     * @param \DateTime $createdBefore
     * @param bool $deleted
     * @return array(array(string => mixed))
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore, $deleted = false);
    
}