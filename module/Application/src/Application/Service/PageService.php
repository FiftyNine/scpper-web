<?php

namespace Application\Service;

use Application\Mapper\PageMapperInterface;
use Application\Utils\PageType;

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
    public function countSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null)
    {
        return $this->mapper->countSitePages($siteId, $type, $createdAfter, $createdBefore);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $offset = 0, $limit = 0)
    {
        return $this->mapper->findSitePages($siteId, $type, $createdAfter, $createdBefore, $offset, $limit);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore);
    }            
}