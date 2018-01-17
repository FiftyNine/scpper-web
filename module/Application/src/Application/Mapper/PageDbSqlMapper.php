<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate;
use Application\Utils\Order;
use Application\Utils\PageStatus;
use Application\Utils\PageKind;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\DbConsts\DbViewPagesAll;
use Application\Utils\DbConsts\DbViewTags;
use Application\Utils\DbConsts\DbViewAuthors;

class PageDbSqlMapper extends ZendDbSqlMapper implements PageMapperInterface
{    
    /**
     * {@inheritDoc}
     */
    protected function buildPageSelect(
        Sql $sql,
        $siteId, 
        $type = PageStatus::ANY, 
        \DateTime $createdAfter = null, 
        \DateTime $createdBefore = null,
        $deleted = false)
    {
        $select = $sql->select()
                      ->from(['p' => DbViewPagesAll::TABLE])
                      ->where(['p.'.DbViewPagesAll::SITEID.' = ?' => $siteId]);
        if ($type !== PageStatus::ANY) {
            $select->where(['p.'.DbViewPagesAll::STATUSID.' = ?' => $type]);
        }
        if ($createdAfter) {
            $select->where(['p.'.DbViewPagesAll::CREATIONDATE.' >= ?' => $createdAfter->format(self::DATETIME_FORMAT)]);
        }
        if ($createdBefore) {
            $select->where(['p.'.DbViewPagesAll::CREATIONDATE.' <= ?' => $createdBefore->format(self::DATETIME_FORMAT)]);
        }
        if (!is_null($deleted)) {
            $select->where(['p.'.DbViewPagesAll::DELETED.' = ?' => (int)$deleted]);
        }
        return $select;
    }
    
    /**
     * {@inheritDoc}
     */
    public function countSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = false) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, $type, $createdAfter, $createdBefore, $deleted);
        return $this->fetchCount($sql, $select);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findSitePages(
            $siteId, 
            $type = PageStatus::ANY, 
            \DateTime $createdAfter = null, 
            \DateTime $createdBefore = null, 
            $deleted = false,
            $order = null, 
            $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, $type, $createdAfter, $createdBefore, $deleted);
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
    public function findPagesByUser($userId, $siteId, $deleted = false, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(['p' => DbViewPagesAll::TABLE])
                ->join(['a' => DbViewAuthors::TABLE], 'p.'.DbViewPagesAll::PAGEID.' = a.'.DbViewAuthors::PAGEID, [])
                ->where([
                    'a.'.DbViewAuthors::USERID.' = ?' => $userId,
                    'p.'.DbViewPagesAll::SITEID.' = ?' => $siteId,
                    '(p.'.DbViewPagesAll::KINDID.' IS NULL OR p.'.DbViewPagesAll::KINDID.' <> '.PageKind::SERVICE.')'
                ]);
        if (!is_null($deleted)) {
            $select->where(['p.'.DbViewPagesAll::DELETED.' = ?' => (int)$deleted]);
        }
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
    public function findPagesByName($sites, $mask, $deleted = false, $order = null, $paginated = false)
    {
        $lower = mb_strtolower($mask);
        $regexp = sprintf('(^|[^[:alnum:]])%s($|[^[:alnum:]])', $lower);
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(['p' => DbViewPagesAll::TABLE])
                ->columns([
                    '' => Select::SQL_STAR, 
                    'InTitle' => new Expression(sprintf('CASE WHEN %s RLIKE ? THEN 1 ELSE 0 END', DbViewPagesAll::TITLE), [$regexp]),
                    'InAltTitle' => new Expression(sprintf('CASE WHEN %s RLIKE ? THEN 1 ELSE 0 END', DbViewPagesAll::ALTTITLE), [$regexp]),
                    'Relevance' => new Expression(sprintf('MATCH (%s, %s) AGAINST (? IN NATURAL LANGUAGE MODE)', DbViewPagesAll::TITLE, DbViewPagesAll::ALTTITLE), [$lower])
                ])
                ->where(new Predicate\Expression(sprintf('MATCH (%s, %s) AGAINST (? IN NATURAL LANGUAGE MODE)', DbViewPagesAll::TITLE, DbViewPagesAll::ALTTITLE), [$lower]));
        if (is_array($sites)) {
            $select = $select->where(new Predicate\In(DbViewPagesAll::SITEID, $sites));
        }
        if (!is_null($deleted)) {
            $select->where(['p.'.DbViewPagesAll::DELETED.' = ?' => (int)$deleted]);
        }        
        if (!is_array($order)) {
            $order = [
                'InTitle' => Order::DESCENDING,
                'InAltTitle' => Order::DESCENDING,
                'Relevance' => Order::DESCENDING,
                DbViewPagesAll::CLEANRATING => Order::DESCENDING, 
                DbViewPagesAll::CREATIONDATE => Order::ASCENDING
            ];
        }
        $this->orderSelect($select, $order);
        if ($paginated) {
            return $this->getPaginator($select);
        }
        return $this->fetchResultSet($sql, $select);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findPagesByTags($siteId, $includeTags, $excludeTags = [], $all = true, $deleted = false, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(['p' => DbViewPagesAll::TABLE])
                ->where([
                    'p.'.DbViewPagesAll::SITEID.' = ?' => $siteId,
                    //'(p.'.DbViewPagesAll::KINDID.' IS NULL OR p.'.DbViewPagesAll::KINDID.' <> '.PageKind::SERVICE.')'
                ]);
        if (!is_null($deleted)) {
            $select->where(['p.'.DbViewPagesAll::DELETED.' = ?' => (int)$deleted]);
        }          
        if (count($includeTags) > 0) {        
            $includeString = vsprintf("'%s'", [implode("','", $includeTags)]);
            $includeSubSelect = $sql->select(['t' => DbViewTags::TABLE])
                    ->columns([new Expression('COUNT(*)')])
                    ->where([
                        't.'.DbViewTags::PAGEID.' = p.'.DbViewPagesAll::PAGEID,
                        't.'.DbViewTags::TAG.' IN ('.$includeString.')',
                    ]);
            $platform = $this->dbAdapter->getPlatform();
            $query = $includeSubSelect->getSqlString($platform);                        
            if ($all) {
                $condition = vsprintf('(%s) = %d', [$query, count($includeTags)]);
            } else {
                $condition = vsprintf('(%s) >= 1', [$query]);
            }
            $select->where([$condition]);
        }
        if (count($excludeTags) > 0) {
            $excludeString = vsprintf("'%s'", [implode("','", $excludeTags)]);
            $excludeSubSelect = $sql->select(['t' => DbViewTags::TABLE])
                    ->columns([new Expression('NULL')])
                    ->where([
                        't.'.DbViewTags::PAGEID.' = p.'.DbViewPagesAll::PAGEID,
                        't.'.DbViewTags::TAG.' IN ('.$excludeString.')',
                    ]);
            $platform = $this->dbAdapter->getPlatform();
            $query = $excludeSubSelect->getSqlString($platform);                        
            $select->where([vsprintf('NOT EXISTS(%s)', [$query])]);
        }       
        $this->orderSelect($select, $order);
        if ($paginated) {
            return $this->getPaginator($select);
        }
        return $this->fetchResultSet($sql, $select);        
    }
    
    /**
     * {@inheritDoc}
     */
    public function findTranslations($pageId)
    {
        $sql = new Sql($this->dbAdapter);
        // Only undeleted pages
        $select = $sql->select(DbViewPages::TABLE)
                ->where([DbViewPages::ORIGINALID.' = ?' => $pageId]);
        return $this->fetchResultSet($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findPageRank($pageId)
    {
        $sql = new Sql($this->dbAdapter);    
        // Only undeleted pages
        $subSelect = $sql->select(DbViewPages::TABLE)
                ->columns([new Expression('COUNT(*)')])
                ->where([
                    DbViewPages::CLEANRATING.' > p.'.DbViewPages::CLEANRATING,
                    DbViewPages::SITEID.' = p.'.DbViewPages::SITEID,
                    '('.DbViewPages::KINDID.' IS NULL OR '.DbViewPages::KINDID.' <> '.PageKind::SERVICE.')'
                ]);
        $select = $sql->select(['p' => DbViewPagesAll::TABLE])
                ->columns(['Rank' => $subSelect], false)
                ->where([DbViewPagesAll::PAGEID.' = ?' => $pageId]);
        $res = $this->fetchArray($sql, $select);
        if (count($res) === 1) {
            return $res[0]['Rank'];
        }
        return -1;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore, $deleted = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, PageStatus::ANY, $createdAfter, $createdBefore, $deleted);
        $this->aggregateSelect($select, $aggregates);
        return $this->fetchArray($sql, $select);
    }
}