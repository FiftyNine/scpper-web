<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\PageType;
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
     * 
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