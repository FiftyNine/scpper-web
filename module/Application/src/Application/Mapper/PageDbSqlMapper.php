<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Application\Utils\PageStatus;
use Application\Utils\PageKind;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\DbConsts\DbViewTags;
use Application\Utils\DbConsts\DbViewAuthors;

class PageDbSqlMapper extends ZendDbSqlMapper implements PageMapperInterface
{
    protected function buildPageSelect(
        Sql $sql,
        $siteId, 
        $type = PageStatus::ANY, 
        \DateTime $createdAfter = null, 
        \DateTime $createdBefore = null
    )
    {
        $select = $sql->select()
                      ->from(array('p' => DbViewPages::TABLE))
                      ->where(array('p.'.DbViewPages::SITEID.' = ?' => $siteId));
        if ($type !== PageStatus::ANY) {            
                   $select->where(array('p.'.DbViewPages::STATUSID.' = ?' => $type));
        }
        if ($createdAfter) {
            $select->where(array('p.'.DbViewPages::CREATIONDATE.' >= ?' => $createdAfter->format(self::DATETIME_FORMAT)));
        }
        if ($createdBefore) {
            $select->where(array('p.'.DbViewPages::CREATIONDATE.' <= ?' => $createdBefore->format(self::DATETIME_FORMAT)));
        }
        return $select;
    }
    
    /**
     * {@inheritDoc}
     */
    public function countSitePages($siteId, $type = PageStatus::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, $type, $createdAfter, $createdBefore);
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
            $order = null, 
            $paginated = false
    )
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, $type, $createdAfter, $createdBefore);
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
    public function findPagesByUser($userId, $siteId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('p' => DbViewPages::TABLE))
                ->join(array('a' => DbViewAuthors::TABLE), 'p.'.DbViewPages::PAGEID.' = a.'.DbViewAuthors::PAGEID, array())
                ->where(array(
                    'a.'.DbViewAuthors::USERID.' = ?' => $userId,
                    'p.'.DbViewPages::SITEID.' = ?' => $siteId,
                    '(p.'.DbViewPages::KINDID.' IS NULL OR p.'.DbViewPages::KINDID.' <> '.PageKind::SERVICE.')'
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
    public function findPagesByTags($siteId, $includeTags, $excludeTags = [], $all = true, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(array('p' => DbViewPages::TABLE))
                ->where(array(
                    'p.'.DbViewPages::SITEID.' = ?' => $siteId,
                    //'(p.'.DbViewPages::KINDID.' IS NULL OR p.'.DbViewPages::KINDID.' <> '.PageKind::SERVICE.')'
                ));
        if (count($includeTags) > 0) {        
            $includeString = vsprintf("'%s'", [implode("','", $includeTags)]);
            $includeSubSelect = $sql->select(array('t' => DbViewTags::TABLE))
                    ->columns(array(new Expression('COUNT(*)')))
                    ->where(array(
                        't.'.DbViewTags::PAGEID.' = p.'.DbViewPages::PAGEID,
                        't.'.DbViewTags::TAG.' IN ('.$includeString.')',
                    ));
            $platform = $this->dbAdapter->getPlatform();
            $query = $includeSubSelect->getSqlString($platform);                        
            if ($all) {
                $condition = vsprintf('(%s) = %d', [$query, count($includeTags)]);
            } else {
                $condition = vsprintf('(%s) >= 1', [$query]);
            }
            $select->where(array($condition));                        
        }
        if (count($excludeTags) > 0) {
            $excludeString = vsprintf("'%s'", [implode("','", $excludeTags)]);
            $excludeSubSelect = $sql->select(array('t' => DbViewTags::TABLE))                    
                    ->columns(array(new Expression('NULL')))
                    ->where(array(
                        't.'.DbViewTags::PAGEID.' = p.'.DbViewPages::PAGEID,
                        't.'.DbViewTags::TAG.' IN ('.$excludeString.')',
                    ));
            $platform = $this->dbAdapter->getPlatform();
            $query = $excludeSubSelect->getSqlString($platform);                        
            $select->where(array(vsprintf('NOT EXISTS(%s)', [$query])));                        
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
    public function findTranslations($pageId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewPages::TABLE)
                ->where(array(DbViewPages::ORIGINALID.' = ?' => $pageId));
        return $this->fetchResultSet($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findPageRank($pageId)
    {
        $sql = new Sql($this->dbAdapter);        
        $subSelect = $sql->select(DbViewPages::TABLE)
                ->columns(array(new Expression('COUNT(*)')))
                ->where(array(
                    DbViewPages::CLEANRATING.' > p.'.DbViewPages::CLEANRATING,
                    DbViewPages::SITEID.' = p.'.DbViewPages::SITEID,
                    '('.DbViewPages::KINDID.' IS NULL OR '.DbViewPages::KINDID.' <> '.PageKind::SERVICE.')'
                ));
        $select = $sql->select(array('p' => DbViewPages::TABLE))
                ->columns(array('Rank' => $subSelect), false)
                ->where(array(DbViewPages::PAGEID.' = ?' => $pageId));
        $res = $this->fetchArray($sql, $select);
        if (count($res) === 1) {
            return $res[0]['Rank'];
        }
        return -1;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore)   
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, PageStatus::ANY, $createdAfter, $createdBefore);
        $this->aggregateSelect($select, $aggregates);
        return $this->fetchArray($sql, $select);
    }
}