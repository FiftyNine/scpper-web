<?php

namespace Application\Service;

use Application\Mapper\PageMapperInterface;
use Application\Utils\PageStatus;
use Application\Utils\DbConsts\DbViewPages;

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
    public function findByName($mask)
    {
        $mask = mb_strtolower($mask);
        if (filter_var($mask, FILTER_VALIDATE_INT)) {
            $needle = sprintf('((.*[^[:alnum:]])|^)%s(([^[:alnum:]].*)|$)', $mask);
        } else {
            $needle = sprintf('.*%s.*', $mask);            
        }
        $conditions = array(sprintf("LOWER(%s) RLIKE ?", DbViewPages::TITLE) => $needle);
        return $this->mapper->findAll($conditions);
    }
    
    /**
     * {@inheritDoc}
     */
    public function countSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null)
    {
        return $this->mapper->countSitePages($siteId, $type, $createdAfter, $createdBefore);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $order = null, $paginated = false)
    {
        return $this->mapper->findSitePages($siteId, $type, $createdAfter, $createdBefore, $order, $paginated);
    }
        
    /**
     * {@inheritDoc}
     */
    public function findPagesByUser($userId, $siteId, $order = null, $paginated = false)
    {
        return $this->mapper->findPagesByUser($userId, $siteId, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore);
    }            
}