<?php

namespace Application\Service;

use Application\Mapper\PageMapperInterface;
use Application\Utils\PageStatus;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\Order;

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
    public function findByName($mask, $sites, $order = null, $paginated = false)
    {
        $mask = mb_strtolower($mask);
        if (filter_var($mask, FILTER_VALIDATE_INT)) {
            $needle = sprintf('((.*[^[:alnum:]])|^)%s(([^[:alnum:]].*)|$)', $mask);
        } else {
            $needle = sprintf('.*%s.*', $mask);            
        }
        $conditions = [
            sprintf("LOWER(%s) RLIKE ?", DbViewPages::TITLE) => $needle
        ];
        if (is_array($sites)) {
            $conditions[sprintf("%s in (?)", DbViewPages::SITEID)] = implode($sites, ',');
        }
        if ($order === null) {
            $len = strlen($mask);
            $order = [sprintf("ABS(LENGTH(%s) - $len)", DbViewPages::TITLE) => Order::ASCENDING];
        }
        return $this->mapper->findAll($conditions, $order, $paginated);
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
    public function findPagesByTags($siteId, $includeTags, $excludeTags = [], $all = true, $order = null, $paginated = false)
    {
        return $this->mapper->findPagesByTags($siteId, $includeTags, $excludeTags, $all, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore);
    }            
}