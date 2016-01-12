<?php

namespace Application\Service;

use Application\Mapper\VoteMapperInterface;
use Application\Utils\VoteType;
use Application\Utils\DateGroupType;

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
    public function countCastVotes($siteId, \DateTime $castAfter, \DateTime $castBefore)
    {
        $group = DateGroupType::getBestGroupType($castAfter, $castBefore);
        return $this->mapper->countCastVotes($siteId, $castAfter, $castBefore, $group);        
    }
}