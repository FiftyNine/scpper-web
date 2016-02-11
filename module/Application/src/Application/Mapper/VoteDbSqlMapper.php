<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewAuthors;

class VoteDbSqlMapper extends ZendDbSqlMapper implements VoteMapperInterface
{
    protected function buildVoteSelect(Sql $sql, $conditions, $type = VoteType::ANY, \DateTime $castAfter = null, \DateTime $castBefore = null)
    {
        $select = $sql->select(DbViewVotes::TABLE);
        if ($conditions) {
            $select->where($conditions);
        }
        if ($type !== VoteType::ANY) {
            $select->where(array(DbViewVotes::VALUE.' = ?' => $type));
        }
        if ($castAfter) {
            $select->where(array(DbViewVotes::DATETIME.' >= ?' => $castAfter->format(self::DATETIME_FORMAT)));
        }
        if ($castBefore) {
            $select->where(array(DbViewVotes::DATETIME.' <= ?' => $castBefore->format(self::DATETIME_FORMAT)));
        }
        return $select;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function countSiteVotes($siteId, $type = VoteType::ANY, \DateTime $castAfter = null, \DateTime $castBefore = null) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildVoteSelect($sql, array(DbViewVotes::SITEID.' = ?' => $siteId), $type, $castAfter, $castBefore);
        return $this->fetchCount($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findVotesOnPage($pageId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewVotes::TABLE)
                ->where(array(DbViewVotes::PAGEID.' = ?' => $pageId));
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select);
        }
        return $this->fetchResultSet($sql, $select);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedVotesOnUser($userId, $siteId, $aggregates, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('v' => DbViewVotes::TABLE))
                ->join(array('a' => DbViewAuthors::TABLE), 'a.'.DbViewAuthors::PAGEID.' = v.'.DbViewVotes::PAGEID, array())
                ->where(array(
                    'a.'.DbViewAuthors::USERID.' = ?' => $userId,
                    'a.'.DbViewAuthors::SITEID.' = ?' => $siteId,
                    'v.'.DbViewVotes::FROMMEMBER.' = 1'
                ));
        $this->aggregateSelect($select, $aggregates, 'v');
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select, true);
        }
        return $this->fetchArray($sql, $select);
    }

    /**
     * Get a list of favorite authors of user
     * @param int $userId
     * @param int $siteId
     * @param bool $paginated Return a \Zend\Paginator\Paginator object instead of actual objects
     * @return array(array(string => mixed))
     */
    public function getFavoriteAuthors($userId, $siteId, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('v' => DbViewVotes::TABLE))
                ->columns(array(
                    DbViewAuthors::USERID => 'a.'.DbViewAuthors::USERID,
                    DbViewAuthors::USERDISPLAYNAME => 'a.'.DbViewAuthors::USERDISPLAYNAME,
                    'Votes' => new Expression('COUNT(*)'),
                    'Rating' => new Expression('SUM(v.'.DbViewVotes::VALUE.')')
                ), false)
                ->join(array('a' => DbViewAuthors::TABLE), 'a.'.DbViewAuthors::PAGEID.' = v.'.DbViewVotes::PAGEID, array())
                ->where(array(
                    'v.'.DbViewVotes::USERID.' = ?' => $userId,
                    'a.'.DbViewAuthors::SITEID.' = ?' => $siteId
                ))                
                ->group(array('a.'.DbViewAuthors::USERID, 'a.'.DbViewAuthors::USERDISPLAYNAME))
                ->order('Rating DESC');
        if ($paginated) {
            return $this->getPaginator($select, true);
        }
        return $this->fetchArray($sql, $select);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($conditions, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildVoteSelect($sql, $conditions, VoteType::ANY, $castAfter, $castBefore);
        $this->aggregateSelect($select, $aggregates);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select, true);
        }        
        return $this->fetchArray($sql, $select);
    }
}