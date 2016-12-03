<?php

namespace Application\Service;

use Application\Mapper\VoteMapperInterface;
use Application\Mapper\UserMapperInterface;
use Application\Mapper\PageMapperInterface;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbConsts\DbViewPages;

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
    
    /**
     *
     * @var PageMapperInterface
     */
    protected $pageMapper;    
    
    /**
     * Fill Vote objects with respective Page objects
     * @param type $votes
     */
    private function fillVotesPages($votes)
    {
        $pageIds = array();
        foreach ($votes as $vote) {
            $pageIds[] = $vote->getPageId();
        }
        if (count($pageIds) > 0) {
            $pages = $this->pageMapper->findAll(array(
                sprintf('%s IN (%s)', DbViewPages::PAGEID, implode(',', $pageIds))
            ));
            $pageByIds = array();
            foreach ($pages as $page) {
                $pageByIds[$page->getId()] = $page;
            }
            foreach ($votes as $vote) {
                $vote->setPage($pageByIds[$vote->getPageId()]);
            }
        }
    }
    
    /**
     * Fill Vote objects with respective User objects
     * @param type $votes
     */
    private function fillVotesUsers($votes)
    {
        $userIds = array();
        foreach ($votes as $vote) {
            $userIds[] = $vote->getUserId();
        }
        if (count($userIds) > 0) {
            $users = $this->userMapper->findAll(array(
                sprintf('%s IN (%s)', DbViewUsers::USERID, implode(',', $userIds))
            ));
            $userByIds = array();
            foreach ($users as $user) {
                $userByIds[$user->getId()] = $user;
            }
            foreach ($votes as $vote) {
                $vote->setUser($userByIds[$vote->getUserId()]);
            }
        }
    }    
    
    public function __construct(
        VoteMapperInterface $mapper,
        UserMapperInterface $userMapper,
        PageMapperInterface $pageMapper
    )
    {
        $this->mapper = $mapper;
        $this->userMapper = $userMapper;
        $this->pageMapper = $pageMapper;
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
    public function findSiteVotes($siteId, $order = null, $paginated = false, $page = -1, $perPage = -1) 
    {
        $result = $this->mapper->findSiteVotes($siteId, $order, $paginated);
        if ($paginated && $result && $page >= 0 && $perPage > 0) {            
            $result->setCurrentPageNumber($page);
            $result->setItemCountPerPage($perPage);
        } else {
            $result = iterator_to_array($result);
        }
        if ($result) {
            $this->fillVotesPages($result);
            $this->fillVotesUsers($result);
        }        
        return $result;                
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
            $result = iterator_to_array($result);
        }
        if ($result) {
            $this->fillVotesUsers($result);
        }
        return $result;        
    }
    
    /**
     * {@inheritDoc}
     */
    public function findVotesOfUser($userId, $siteId, $order = null, $paginated = false, $page = -1, $perPage = -1)
    {
        $result = $this->mapper->findVotesOfUser($userId, $siteId, $order, $paginated);
        if ($paginated && $result && $page >= 0 && $perPage > 0) {            
            $result->setCurrentPageNumber($page);
            $result->setItemCountPerPage($perPage);
        } else {
            $result = iterator_to_array($result);
        }
        if ($result) {
            $this->fillVotesPages($result);
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
    public function getUserFavoriteAuthors($userId, $siteId, $orderByRatio, $paginated = false)
    {        
        return $this->mapper->getUserFavoriteAuthors($userId, $siteId, $orderByRatio, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUserFavoriteTags($userId, $siteId, $orderByRatio, $paginated = false)
    {
        return $this->mapper->getUserFavoriteTags($userId, $siteId, $orderByRatio, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUserBiggestFans($userId, $siteId, $orderByRatio, $paginated = false)
    {
        return $this->mapper->getUserBiggestFans($userId, $siteId, $orderByRatio, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedVotesOnUser($userId, $siteId, $aggregates, $order = null, $paginated = false)
    {        
        return $this->mapper->getAggregatedVotesOnUser($userId, $siteId, $aggregates, $order, $paginated);
    }
    
}