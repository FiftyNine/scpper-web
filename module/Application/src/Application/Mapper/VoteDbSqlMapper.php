<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Application\Utils\VoteType;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewVotesAll;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\DbConsts\DbViewTags;
use Application\Utils\DbConsts\DbViewFans;

class VoteDbSqlMapper extends ZendDbSqlMapper implements VoteMapperInterface
{
    protected function buildVoteSelect(Sql $sql, $conditions, $type = VoteType::ANY, \DateTime $castAfter = null, \DateTime $castBefore = null, $deleted = null)
    {
        $select = $sql->select(DbViewVotesAll::TABLE);
        if ($conditions) {
            $select->where($conditions);
        }
        if ($type !== VoteType::ANY) {
            $select->where([DbViewVotesAll::VALUE.' = ?' => $type]);
        }        
        if ($castAfter) {
            $select->where([DbViewVotesAll::DATETIME.' >= ?' => $castAfter->format(self::DATETIME_FORMAT)]);
        }
        if ($castBefore) {
            $select->where([DbViewVotesAll::DATETIME.' <= ?' => $castBefore->format(self::DATETIME_FORMAT)]);
        }
        if (!is_null($deleted)) {
            $select->where([DbViewVotesAll::DELETED.' = ?' => (int)$deleted]);
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
        $select = $this->buildVoteSelect($sql, [DbViewVotesAll::SITEID.' = ?' => $siteId], $type, $castAfter, $castBefore, false);
        return $this->fetchCount($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findSiteVotes($siteId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildVoteSelect($sql, [DbViewVotesAll::SITEID.' = ?' => $siteId], VoteType::ANY, null, null, false);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }        
        if ($paginated) {         
            return $this->getPaginator($select, false, null, null, true);
        }        
        return $this->fetchResultSet($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findVotesOnPage($pageId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewVotesAll::TABLE)
                ->where([DbViewVotesAll::PAGEID.' = ?' => $pageId]);
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
                ->where([
                    DbViewVotes::USERID.' = ?' => $userId,
                    DbViewVotes::SITEID.' = ?' => $siteId
                ]);
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
        $select = $sql->select(['v' => DbViewVotes::TABLE])
                ->join(['a' => DbViewAuthors::TABLE], 'a.'.DbViewAuthors::PAGEID.' = v.'.DbViewVotes::PAGEID, [])
                ->where([
                    'a.'.DbViewAuthors::USERID.' = ?' => $userId,
                    'a.'.DbViewAuthors::SITEID.' = ?' => $siteId,
                    'v.'.DbViewVotes::FROMMEMBER.' = 1',                    
                ]);
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
    public function getUserFavoriteTags($userId, $siteId, $orderByRatio, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(['v' => DbViewVotes::TABLE])
                ->columns([
                    DbViewTags::TAG => 't.'.DbViewTags::TAG,
                    'Positive' => new Expression('SUM(v.IsPositive)'),
                    'Negative' => new Expression('SUM(v.IsNegative)'),
                ], false)
                ->join(['t' => DbViewTags::TABLE], 't.'.DbViewTags::PAGEID.' = v.'.DbViewVotes::PAGEID, [])
                ->where([
                    'v.'.DbViewVotes::USERID.' = ?' => $userId,
                    'v.'.DbViewVotes::SITEID.' = ?' => $siteId
                ])                
                ->group(['t.'.DbViewTags::TAG]);
        $select = $sql->select(['a' => $select]);
        if ($orderByRatio) {
            $select->columns([
                '*',
                'Confidence' => new Expression('CI_LOWER_BOUND(Positive, Negative)')
            ])
            ->order('Confidence DESC');
        } else {
            $select->columns([
                '*',
                'Total' => new Expression('Positive - Negative')
            ])
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
    public function getUserFavoriteAuthors($userId, $siteId, $orderByRatio, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewFans::TABLE)
                ->columns([
                    DbViewFans::AUTHORID,
                    DbViewFans::AUTHORDISPLAYNAME,
                    DbViewFans::AUTHORDELETED,
                    DbViewFans::POSITIVE,
                    DbViewFans::NEGATIVE], false)
                ->where([
                    DbViewFans::USERID.' = ?' => $userId,
                    DbViewFans::SITEID.' = ?' => $siteId
                ]);
        if ($orderByRatio) {
            $select->order(new Expression('CI_LOWER_BOUND(Positive, Negative) DESC'));
        } else {
            $select->order(new Expression('Positive - Negative DESC'));
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
        $select = $sql->select(DbViewFans::TABLE)
                ->columns([
                    DbViewFans::USERID,
                    DbViewFans::USERDISPLAYNAME,
                    DbViewFans::USERDELETED,
                    DbViewFans::POSITIVE,
                    DbViewFans::NEGATIVE], false)
                ->where([
                    DbViewFans::AUTHORID.' = ?' => $userId,
                    DbViewFans::SITEID.' = ?' => $siteId
                ]);
        if ($orderByRatio) {
            $select->order(new Expression('CI_LOWER_BOUND(Positive, Negative) DESC'));
        } else {
            $select->order(new Expression('Positive - Negative DESC'));
        }
        if ($paginated) {
            return $this->getPaginator($select, true);
        }
        return $this->fetchArray($sql, $select);        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($conditions, $aggregates, \DateTime $castAfter = null, \DateTime $castBefore = null, $deleted = null, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildVoteSelect($sql, $conditions, VoteType::ANY, $castAfter, $castBefore, $deleted);
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