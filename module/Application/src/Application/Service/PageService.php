<?php

namespace Application\Service;

use Application\Mapper\PageMapperInterface;
use Application\Utils\PageStatus;

class PageService implements PageServiceInterface 
{
    /**
     *
     * @var PageMapperInterface
     */
    protected $mapper;
    
    public function __construct(PageMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }
    
    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->mapper->find($id);
    }
    
    /**
     * {@inheritDoc}
     */    
    public function findAll()
    {
        return $this->mapper->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function findByName($mask, $sites, $deleted = false, $order = null, $paginated = false)
    {
        return $this->mapper->findPagesByName($sites, $mask, $deleted, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function countSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false)
    {
        return $this->mapper->countSitePages($siteId, $type, $createdAfter, $createdBefore, $deleted);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false, $order = null, $paginated = false)
    {
        return $this->mapper->findSitePages($siteId, $type, $createdAfter, $createdBefore, $deleted, $order, $paginated);
    }
        
    /**
     * {@inheritDoc}
     */
    public function findPagesByUser($userId, $siteId, $deleted = false, $order = null, $paginated = false)
    {
        return $this->mapper->findPagesByUser($userId, $siteId, $deleted, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findPagesByTags($siteId, $includeTags, $excludeTags = [], $all = true, $deleted = false, $order = null, $paginated = false)
    {
        return $this->mapper->findPagesByTags($siteId, $includeTags, $excludeTags, $all, $deleted, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore, $deleted = false)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore, $deleted);
    }            
}