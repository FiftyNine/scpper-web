<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Application\Utils\PageType;
use Application\Utils\DbConsts\DbViewPages;

class PageDbSqlMapper extends ZendDbSqlMapper implements PageMapperInterface
{
    protected function buildPageSelect(
        Sql $sql,
        $siteId, 
        $type = PageType::ANY, 
        \DateTime $createdAfter = null, 
        \DateTime $createdBefore = null
    )
    {
        $select = $sql->select()
                      ->from(array('p' => DbViewPages::TABLE))
                      ->where(array('p.'.DbViewPages::SITEID.' = ?' => $siteId));
        if ($type !== PageType::ANY) {            
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
    public function countSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null) 
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
            $type = PageType::ANY, 
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
        $select = $this->buildPageSelect($sql, $siteId, PageType::ANY, $createdAfter, $createdBefore);
        $this->aggregateSelect($select, $aggregates);
        return $this->fetchArray($sql, $select);
    }
}