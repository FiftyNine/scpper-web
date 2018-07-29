<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Application\Utils\VoteType;
use Application\Utils\Aggregate;
use Application\Utils\DateAggregate;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewVotesAll;
use Application\Utils\DbConsts\DbVoteHistory;
use Application\Utils\DbConsts\DbViewVoteHistoryAll;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\DbConsts\DbViewTags;
use Application\Utils\DbConsts\DbViewFans;

class VoteDbSqlMapper extends ZendDbSqlMapper implements VoteMapperInterface
{
    const CHART_VOTES = 'Votes';
    const CHART_DATE = 'Date';
    
    protected function buildVoteSelect(Sql $sql, $table, $conditions, $type = VoteType::ANY, 
            $withHistory = true, \DateTime $castAfter = null, \DateTime $castBefore = null, $deleted = null)
    {
        $select = $sql->select(['v' => $table]);
        if ($withHistory) {
            $select->columns([
                '' => Select::SQL_STAR, 
                'HasHistory' => new Expression(
                    sprintf('(SELECT COUNT(*) FROM %s WHERE %s = v.%s AND %s = v.%s)', 
                            DbVoteHistory::TABLE, 
                            DbVoteHistory::PAGEID, 
                            DbViewVotesAll::PAGEID, 
                            DbVoteHistory::USERID, 
                            DbViewVotesAll::USERID
                            )
                        ),
            ]);
        }
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
        $select = $this->buildVoteSelect($sql, DbViewVotesAll::TABLE, [DbViewVotesAll::SITEID.' = ?' => $siteId], $type, false, $castAfter, $castBefore, false);
        return $this->fetchCount($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findSiteVotes($siteId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildVoteSelect($sql, DbViewVotesAll::TABLE, [DbViewVotesAll::SITEID.' = ?' => $siteId], VoteType::ANY, true, null, null, false);
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
//        $select = $sql->select(DbViewVotesAll::TABLE)
//                ->where([DbViewVotesAll::PAGEID.' = ?' => $pageId]);
        $select = $this->buildVoteSelect($sql, DbViewVotesAll::TABLE, [DbViewVotesAll::PAGEID.' = ?' => $pageId]);
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
        /*$select = $sql->select(DbViewVotes::TABLE)
                ->where([
                    DbViewVotes::USERID.' = ?' => $userId,
                    DbViewVotes::SITEID.' = ?' => $siteId
                ]);
         */
        $select = $this->buildVoteSelect(
                $sql, 
                DbViewVotes::TABLE, 
                [
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
        $select = $this->buildVoteSelect($sql, DbViewVotesAll::TABLE, $conditions, VoteType::ANY, false, $castAfter, $castBefore, $deleted);
        $this->aggregateSelect($select, $aggregates);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select, true);
        }        
        return $this->fetchArray($sql, $select);
    }

    protected function buildChartAggregates()
    {
        return [
            new Aggregate(DbViewVotesAll::DELTAFROMPREV, Aggregate::SUM, self::CHART_VOTES),
            new DateAggregate(DbViewVotesAll::DATETIME, self::CHART_DATE),
        ];    
    }

    protected function getChartData($sql, $select)
    {
        $select->from(['v' => DbViewVotesAll::TABLE]);
        $aggregates = $this->buildChartAggregates();
        $this->aggregateSelect($select, $aggregates);
        
        $select2 = clone $select;
        $select2->from(['v' => DbViewVoteHistoryAll::TABLE]);
        
        $select->combine($select2);
        $total = $sql->select(['a' => $select])
                ->columns([
                    self::CHART_DATE => self::CHART_DATE,
                    self::CHART_VOTES => new Expression('SUM('.self::CHART_VOTES.')')
                ])                
                ->group(self::CHART_DATE)
                ->order(self::CHART_DATE.' ASC');
        return $this->fetchArray($sql, $total);        
    }
    
    public function getPageChartData($pageId)
    {
        $sql = new Sql($this->dbAdapter);
        $conditions = [
            DbViewVotesAll::PAGEID.' = ?' => $pageId,
            DbViewVotesAll::FROMMEMBER.' = ?' => 1
            ];
        $select = $sql->select()
            ->where($conditions);
        return $this->getChartData($sql, $select);
    }

    public function getUserChartData($userId, $siteId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select()
                ->join(['a' => DbViewAuthors::TABLE], 'a.'.DbViewAuthors::PAGEID.' = v.'.DbViewVotesAll::PAGEID, [])
                ->where([
                    'a.'.DbViewAuthors::USERID.' = ?' => $userId,
                    'a.'.DbViewAuthors::SITEID.' = ?' => $siteId,
                    'v.'.DbViewVotesAll::FROMMEMBER.' = 1',
                    'v.'.DbViewVotesAll::DELETED.' <> 1'
                ]);
        return $this->getChartData($sql, $select);       
    }
    
    /**
     * {@inheritDoc}
     */
    public function findHistory($pageId, $userId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbVoteHistory::TABLE)
                ->where([
                    DbVoteHistory::PAGEID.' = ?' => $pageId,
                    DbVoteHistory::USERID.' = ?' => $userId
                ])
                ->order(DbVoteHistory::DATETIME.' DESC');
        return iterator_to_array($this->fetchResultSet($sql, $select), false);
    }
}