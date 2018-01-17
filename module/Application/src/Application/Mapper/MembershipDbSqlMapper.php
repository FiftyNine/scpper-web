<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\UserType;
use Application\Utils\DbSelectColumns;

/**
 * Description of MembershipDbSqlMapper
 *
 * @author Alexander
 */
class MembershipDbSqlMapper extends ZendDbSqlMapper implements MembershipMapperInterface
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
        $select->from(['m' => DbViewMembership::TABLE])               
               ->where(['m.'.DbViewMembership::SITEID.' = ?' => $siteId]);
        if ($types & UserType::VOTER) {
            $select->where(['m.'.DbViewMembership::VOTES.' > 0']);
        }        
        if ($types & UserType::CONTRIBUTOR) {
            $select->where(['m.'.DbViewMembership::REVISIONS.' > 0']);
        }
        if ($types & UserType::POSTER) {
            $select->where(['m.'.DbViewMembership::PAGES.' > 0']);
        }        
        if ($lastActive && $lastActive->getTimestamp() < time()) {
            $select->where->isNotNull('m.'.DbViewMembership::LASTACTIVITY);
            $select->where(['m.'.DbViewMembership::LASTACTIVITY.' >= ?' => $lastActive->format(self::DATETIME_FORMAT)]);
        }                
        if ($joinedAfter) {            
            $select->where(['m.'.DbViewMembership::JOINDATE.' >= ?' => $joinedAfter->format(self::DATETIME_FORMAT)]);                   
        }                
        if ($joinedBefore) {            
            $select->where(['m.'.DbViewMembership::JOINDATE.' <= ?' => $joinedBefore->format(self::DATETIME_FORMAT)]);                   
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
            $order = null,
            $paginated = false
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildMemberSelect($sql, $siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
        $select->columns(DbSelectColumns::MEMBERSHIP);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
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
     * {@inheritdoc}
     */
    public function findUserMemberships($userId) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewMembership::TABLE)
                ->columns(DbSelectColumns::MEMBERSHIP)
                ->where([DbViewMembership::USERID.' = ?' => $userId]);
        return $this->fetchResultSet($sql, $select);        
    }
}
