<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\DbConsts\DbViewTags;

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
    public function findVotesOfUser($userId, $siteId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewVotes::TABLE)
                ->where(array(
                    DbViewVotes::USERID.' = ?' => $userId,
                    DbViewVotes::SITEID.' = ?' => $siteId
                ));
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
     * {@inheritDoc}
     */
    public function getUserFavoriteAuthors($userId, $siteId, $orderByRatio, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('v' => DbViewVotes::TABLE))
                ->columns(array(
                    DbViewAuthors::USERID => 'a.'.DbViewAuthors::USERID,
                    DbViewAuthors::USERDISPLAYNAME => 'a.'.DbViewAuthors::USERDISPLAYNAME,
                    'Positive' => new Expression('SUM(v.IsPositive)'),
                    'Negative' => new Expression('SUM(v.IsNegative)'),
                ), false)
                ->join(array('a' => DbViewAuthors::TABLE), 'a.'.DbViewAuthors::PAGEID.' = v.'.DbViewVotes::PAGEID, array())
                ->where(array(
                    'v.'.DbViewVotes::USERID.' = ?' => $userId,
                    'a.'.DbViewAuthors::SITEID.' = ?' => $siteId
                ))                
                ->group(array('a.'.DbViewAuthors::USERID, 'a.'.DbViewAuthors::USERDISPLAYNAME));
        $select = $sql->select(array('a' => $select));
        if ($orderByRatio) {
            $select->columns(array(
                '*',
                'Confidence' => new Expression('CI_LOWER_BOUND(Positive, Negative)')
            ))
            ->order('Confidence DESC');
        } else {
            $select->columns(array(
                '*',
                'Total' => new Expression('Positive - Negative')
            ))
            ->order('Total DESC');
        }
        if ($paginated) {
            return $this->getPaginator($select, true);
        }
        return $this->fetchArray($sql, $select);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUserFavoriteTags($userId, $siteId, $orderByRatio, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('v' => DbViewVotes::TABLE))
                ->columns(array(
                    DbViewTags::TAG => 't.'.DbViewTags::TAG,
                    'Positive' => new Expression('SUM(v.IsPositive)'),
                    'Negative' => new Expression('SUM(v.IsNegative)'),
                ), false)
                ->join(array('t' => DbViewTags::TABLE), 't.'.DbViewTags::PAGEID.' = v.'.DbViewVotes::PAGEID, array())
                ->where(array(
                    'v.'.DbViewVotes::USERID.' = ?' => $userId,
                    'v.'.DbViewVotes::SITEID.' = ?' => $siteId
                ))                
                ->group(array('t.'.DbViewTags::TAG));
        $select = $sql->select(array('a' => $select));
        if ($orderByRatio) {
            $select->columns(array(
                '*',
                'Confidence' => new Expression('CI_LOWER_BOUND(Positive, Negative)')
            ))
            ->order('Confidence DESC');
        } else {
            $select->columns(array(
                '*',
                'Total' => new Expression('Positive - Negative')
            ))
            ->order('Total DESC');
        }        
        if ($paginated) {
            return $this->getPaginator($select, true);
        }
        return $this->fetchArray($sql, $select);        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUserBiggestFans($userId, $siteId, $orderByRatio, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('v' => DbViewVotes::TABLE))
            ->columns(array(
                DbViewVotes::USERID => 'v.'.DbViewVotes::USERID,
                DbViewVotes::USERDISPLAYNAME => 'v.'.DbViewVotes::USERDISPLAYNAME,
                'Positive' => new Expression('SUM(v.IsPositive)'),
                'Negative' => new Expression('SUM(v.IsNegative)'),                    
            ), false)
            ->join(array('a' => DbViewAuthors::TABLE), 'a.'.DbViewAuthors::PAGEID.' = v.'.DbViewVotes::PAGEID, array())
            ->where(array(
                'a.'.DbViewAuthors::USERID.' = ?' => $userId,
                'a.'.DbViewAuthors::SITEID.' = ?' => $siteId,
                'v.'.DbViewVotes::FROMMEMBER.' = 1'
            ))
            ->group(array(
                'v.'.DbViewVotes::USERID,
                'v.'.DbViewVotes::USERDISPLAYNAME,
            ));
        $select = $sql->select(array('a' => $select));
        if ($orderByRatio) {
            $select->columns(array(
                '*',
                'Confidence' => new Expression('CI_LOWER_BOUND(Positive, Negative)')
            ))
            ->order('Confidence DESC');
        } else {
            $select->columns(array(
                '*',
                'Total' => new Expression('Positive - Negative')
            ))
            ->order('Total DESC');
        }                
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