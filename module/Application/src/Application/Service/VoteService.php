<?php

namespace Application\Service;

use Application\Mapper\VoteMapperInterface;
use Application\Mapper\UserMapperInterface;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewUsers;

class VoteService implements VoteServiceInterface 
{
    /**
     *
     * @var VoteMapperInterface
     */
    protected $mapper;
    
    /**
     *
     * @var UserMapperInterface
     */
    protected $userMapper;
    
    public function __construct(
        VoteMapperInterface $mapper,
        UserMapperInterface $userMapper
    )
    {
        $this->mapper = $mapper;
        $this->userMapper = $userMapper;
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
    public function findVotesOnPage($pageId, $order = null, $paginated = false, $page = -1, $perPage = -1)
    {
        $result = $this->mapper->findVotesOnPage($pageId, $order, $paginated);
        if ($paginated && $result && $page >= 0 && $perPage > 0) {            
            $result->setCurrentPageNumber($page);
            $result->setItemCountPerPage($perPage);
        } else {
            $votes = array();
            foreach ($result as $vote) {
                $votes[] = $vote;
            }
            $result = $votes;
        }
        if ($result) {
            $userIds = array();
            foreach ($result as $vote) {
                $userIds[] = $vote->getUserId();
            }
            $users = $this->userMapper->findAll(array(
                sprintf('%s IN (%s)', DbViewUsers::USERID, implode(',', $userIds))
            ));
            $userByIds = array();
            foreach ($users as $user) {
                $userByIds[$user->getId()] = $user;
            }
            foreach ($result as $vote) {
                $vote->setUser($userByIds[$vote->getUserId()]);
            }
        }
        return $result;        
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