<?php

namespace Application\Service;

use Application\Mapper\RevisionMapperInterface;
use Application\Mapper\UserMapperInterface;
use Application\Utils\DbConsts\DbViewUsers;

class RevisionService implements RevisionServiceInterface 
{
    /**
     *
     * @var RevisionMapperInterface
     */
    protected $mapper;
    
    /**
     *
     * @var UserMapperInterface
     */
    protected $userMapper;
    
    public function __construct(
            RevisionMapperInterface $mapper,
            UserMapperInterface $userMapper
    )
    {
        $this->mapper = $mapper;
        $this->userMapper = $userMapper;
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
    public function findRevisionsOfPage($pageId, $paginated = false, $page = -1, $perPage = -1)
    {
        $result = $this->mapper->findRevisionsOfPage($pageId, $paginated);
        if ($paginated && $result && $page >= 0 && $perPage > 0) {            
            $result->setCurrentPageNumber($page);
            $result->setItemCountPerPage($perPage);
        } else {
            $revs = array();
            foreach ($result as $rev) {
                $revs[] = $rev;
            }
            $result = $revs;
        }
        if ($result) {
            $userIds = array();
            foreach ($result as $rev) {
                $userIds[] = $rev->getUserId();
            }
            $users = $this->userMapper->findAll(array(
                sprintf('%s IN (%s)', DbViewUsers::USERID, implode(',', $userIds))
            ));
            $userByIds = array();
            foreach ($users as $user) {
                $userByIds[$user->getId()] = $user;
            }
            foreach ($result as $rev) {
                $rev->setUser($userByIds[$rev->getUserId()]);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     */    
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $order = null, $paginated = false)
    {
        return $this->mapper->getAggregatedValues($siteId, $aggregates, $createdAfter, $createdBefore, $order, $paginated);
    }                        
}