<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\DbConsts\DbViewRevisionsAll;

class RevisionDbSqlMapper extends ZendDbSqlMapper implements RevisionMapperInterface
{
    protected function buildRevisionSelect(Sql $sql, $siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $deleted = null)
    {
        $select = $sql->select()
                      ->from(['r' => DbViewRevisionsAll::TABLE])
                      ->where(['r.'.DbViewRevisionsAll::SITEID.' = ?' => $siteId]);
        if (!is_null($deleted)) {
            $select->where(['r.'.DbViewRevisionsAll::DELETED.' = ?' => (int)$deleted]);
        }          
        if ($createdAfter && $createdAfter->getTimestamp() <= time()) {
            $select->where(['r.'.DbViewRevisionsAll::DATETIME.' >= ?' => $createdAfter->format(self::DATETIME_FORMAT)]);
        }
        if ($createdBefore) {
            $select->where(['r.'.DbViewRevisionsAll::DATETIME.' <= ?' => $createdBefore->format(self::DATETIME_FORMAT)]);
        }
        return $select; 
   }
    
    /**
     * {@inheritdoc}
     */
    public function countSiteRevisions($siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildRevisionSelect($sql, $siteId, $createdAfter, $createdBefore, false);
        return $this->fetchCount($sql, $select);
    }

    /**
     * {@inheritdoc}
     */    
    public function findRevisionsOfPage($pageId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewRevisionsAll::TABLE)
                ->where([DbViewRevisionsAll::PAGEID.' = ?' => $pageId]);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select);
        }
        return $this->fetchResultSet($sql, $select);
    }
    
    /**
     * {@inheritdoc}
     */    
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter = null, \DateTime $createdBefore = null, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildRevisionSelect($sql, $siteId, $createdAfter, $createdBefore, false);
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