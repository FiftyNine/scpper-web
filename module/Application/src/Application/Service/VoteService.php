<?php

namespace Application\Service;

use Application\Mapper\VoteMapperInterface;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewVotes;

class VoteService implements VoteServiceInterface 
{
    /**
     *
     * @var VoteMapperInterface
     */
    protected $mapper;
    
    public function __construct(VoteMapperInterface $mapper)
    {
        $this->mapper = $mapper;
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
    public function countSiteVotes($siteId, $type = VoteType::ANY, \DateTime $castAfter = null, \DateTime $castBefore = null)
    {
        return $this->mapper->countSiteVotes($siteId, $type, $castAfter, $castBefore);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedForSite($siteId, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $order = null, $paginated = false)
    {
        return $this->mapper->getAggregatedValues(array(DbViewVotes::SITEID.' = ?' => $siteId), $aggregates, $castAfter, $castBefore, $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedForPage($pageId, $aggregates, $onlyClean = false)
    {
        $conditions = array(DbViewVotes::PAGEID.' = ?' => $pageId);
        if ($onlyClean) {
            $conditions[] = DbViewVotes::FROMMEMBER.' = 1';
        }
        return $this->mapper->getAggregatedValues($conditions, $aggregates);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedForUser($userId, $aggregates)
    {
        return $this->mapper->getAggregatedValues(array(DbViewVotes::USERID.' = ?' => $userId), $aggregates);
    }
}