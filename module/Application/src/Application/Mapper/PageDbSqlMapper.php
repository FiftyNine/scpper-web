<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\PageType;
use Application\Utils\DateGroupType;
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\DbConsts\DbViewPageStatus;

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
            $select->join(array('s' => DbViewPageStatus::TABLE), 'p.'.DbViewPages::PAGEID.' = s.'.DbViewPageStatus::PAGEID, array())
                   ->where(array('s.'.DbViewPageStatus::STATUSID.' = ?' => $type));
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
     * 
     * {@inheritDoc}
     */
    public function countSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, $type, $createdAfter, $createdBefore);
        return $this->fetchCount($sql, $select);
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function findSitePages($siteId, $type = PageType::ANY, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $offset = 0, $limit = 0)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, $type, $createdAfter, $createdBefore);
        return $this->fetchResultSet($sql, $select, $offset, $limit);        
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore, $groupBy = DateGroupType::DAY)   
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildPageSelect($sql, $siteId, PageType::ANY, $createdAfter, $createdBefore);
        $this->aggregateSelect($select, $aggregates);
        $this->groupSelectByDate($select, DbViewPages::CREATIONDATE, $groupBy);
        return $this->fetchArray($sql, $select);
    }
}