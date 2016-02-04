<?php

namespace Application\Service;

use Application\Mapper\RevisionMapperInterface;

class RevisionService implements RevisionServiceInterface 
{
    /**
     *
     * @var RevisionMapperInterface
     */
    protected $mapper;
    
    public function __construct(RevisionMapperInterface $mapper)
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
    public function countSiteRevisions($siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null)
    {
        return $this->mapper->countSiteRevisions($siteId, $createdAfter, $createdBefore);
    }
    
    /**
     * {@inheritDoc}
     */    
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $order = null, $paginated = false)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore, $order, $paginated);
    }                        
}