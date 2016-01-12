<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Application\Model\User;
use Application\Utils\DateGroupType;
use Application\Utils\UserType;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbConsts\DbViewRevisions;
use Application\Utils\DbConsts\DbViewAuthors;


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
        $select->from(array('u' => DbViewUsers::TABLE))
               ->join(array('m' => DbViewMembership::TABLE), 'u.'.DbViewUsers::USERID.' = m.'.DbViewMembership::USERID, array(DbViewMembership::JOINDATE, DbViewMembership::SITEID))
               ->where(array('m.'.DbViewMembership::SITEID.' = ?' => $siteId));
        if ($types & UserType::VOTER) {
            $sub = $sql->select()
                    ->from(DbViewVotes::TABLE)
                    ->columns(array(new Expression('DISTINCT('.DbViewVotes::USERID.') as '.DbViewVotes::USERID)))
                    ->where(array(DbViewVotes::SITEID.' = ?' => $siteId));
            $subText = $sub->getSqlString($this->dbAdapter->getPlatform());
            $select->where->in('u.'.DbViewUsers::USERID, array(new Expression($subText)));
        }        
        if ($types & UserType::CONTRIBUTOR) {
            $sub = $sql->select()
                    ->from(DbViewRevisions::TABLE)
                    ->columns(array(new Expression('DISTINCT('.DbViewRevisions::USERID.') as '.DbViewRevisions::USERID)))
                    ->where(array(DbViewRevisions::SITEID.' = ?' => $siteId));
            $subText = $sub->getSqlString($this->dbAdapter->getPlatform());
            $select->where->in('u.'.DbViewUsers::USERID, array(new Expression($subText)));
        }
        if ($types & UserType::POSTER) {
            $sub = $sql->select()
                    ->from(DbViewAuthors::TABLE)
                    ->columns(array(new Expression('DISTINCT('.DbViewAuthors::USERID.') as '.DbViewAuthors::USERID)))
                    ->where(array(DbViewAuthors::SITEID.' = ?' => $siteId));
            $subText = $sub->getSqlString($this->dbAdapter->getPlatform());
            $select->where->in('u.'.DbViewUsers::USERID, array(new Expression($subText)));
        }        
        if ($lastActive && $lastActive->getTimestamp() < time()) {
            $select->where->isNotNull('m.'.DbViewMembership::LASTACTIVITY);
            $select->where(array('m.'.DbViewMembership::LASTACTIVITY.' >= ?' => $lastActive->format('Y-m-d H:i:s')));                   
        }                
        if ($joinedAfter) {            
            $select->where(array('m.'.DbViewMembership::JOINDATE.' >= ?' => $joinedAfter->format('Y-m-d H:i:s')));                   
        }                
        if ($joinedBefore) {            
            $select->where(array('m.'.DbViewMembership::JOINDATE.' <= ?' => $joinedBefore->format('Y-m-d H:i:s')));                   
        }
        $select->order('m.'.DbViewMembership::JOINDATE.' ASC');
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
            $offset = 0, 
            $limit = 0
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildMemberSelect($sql, $siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
        return $this->fetchResultSet($sql, $select, $offset, $limit);
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function countSiteMembersGroup(
            $siteId, 
            $types = UserType::ANY, 
            \DateTime $lastActive = null, 
            \DateTime $joinedAfter = null, 
            \DateTime $joinedBefore = null, 
            $groupBy = DateGroupType::DAY
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildMemberSelect($sql, $siteId, $types, $lastActive, $joinedAfter, $joinedBefore);
        return $this->fetchCountGroupedByDate($sql, $select, DbViewMembership::JOINDATE, $groupBy);
    }
    
    /**
     * 
     * {@inheritDoc}
     */    
    public function findUserMembership($user) 
    {
        
    }

}