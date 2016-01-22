<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Application\Utils\UserType;
use Application\Utils\DbConsts\DbViewMembership;

class UserDbSqlMapper extends ZendDbSqlMapper implements UserMapperInterface
{
    
    /**
     * Builds a select statement to get members of the site
     * @param Sql $sql
     * @param int $siteId
     * @param int $types
     * @param \DateTime $lastActive
     * @param \DateTime $joinedAfter
     * @param \DateTime $joinedBefore
     * @return Select
     */
    protected function buildMemberSelect(
            Sql $sql,
            $siteId, 
            $types = UserType::ANY,
            \DateTime $lastActive = null,  
            \DateTime $joinedAfter = null, 
            \DateTime $joinedBefore = null            
    )
    {
        $select = $sql->select();
        $select->from(array('m' => DbViewMembership::TABLE))               
               ->where(array('m.'.DbViewMembership::SITEID.' = ?' => $siteId));
        if ($types & UserType::VOTER) {
            $select->where(array('m.'.DbViewMembership::VOTES.' > 0'));
        }        
        if ($types & UserType::CONTRIBUTOR) {
            $select->where(array('m.'.DbViewMembership::REVISIONS.' > 0'));
        }
        if ($types & UserType::POSTER) {
            $select->where(array('m.'.DbViewMembership::PAGES.' > 0'));
        }        
        if ($lastActive && $lastActive->getTimestamp() < time()) {
            $select->where->isNotNull('m.'.DbViewMembership::LASTACTIVITY);
            $select->where(array('m.'.DbViewMembership::LASTACTIVITY.' >= ?' => $lastActive->format(self::DATETIME_FORMAT)));
        }                
        if ($joinedAfter) {            
            $select->where(array('m.'.DbViewMembership::JOINDATE.' >= ?' => $joinedAfter->format(self::DATETIME_FORMAT)));                   
        }                
        if ($joinedBefore) {            
            $select->where(array('m.'.DbViewMembership::JOINDATE.' <= ?' => $joinedBefore->format(self::DATETIME_FORMAT)));                   
        }
        return $select;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function countSiteMembers(
            $siteId, 
            $types = UserType::ANY,
            \DateTime $lastActive = null,  
            \DateTime $joinedAfter = null, 
            \DateTime $joinedBefore = null
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildMemberSelect($sql, $siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
        return $this->fetchCount($sql, $select);
    }

    /**
     * 
     * {@inheritDoc}
     */    
    public function findSiteMembers(
            $siteId, 
            $types = UserType::ANY, 
            \DateTime $lastActive = null, 
            \DateTime $joinedAfter = null, 
            \DateTime $joinedBefore = null, 
            $paginated = false
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildMemberSelect($sql, $siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
        if ($paginated) {
            return $this->getPaginator($select);
        }
        return $this->fetchResultSet($sql, $select);
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getAggregatedValues(
            $siteId,
            $aggregates,
            $types = UserType::ANY,
            \DateTime $lastActive = null,
            \DateTime $joinedAfter = null,
            \DateTime $joinedBefore = null
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildMemberSelect($sql, $siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
        $this->aggregateSelect($select, $aggregates);
        return $this->fetchArray($sql, $select);
    }
    
    /**
     * 
     * {@inheritDoc}
     */    
    public function findUserMembership($user) 
    {
        
    }

}