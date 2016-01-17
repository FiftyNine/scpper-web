<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\DbConsts\DbViewRevisions;

class RevisionDbSqlMapper extends ZendDbSqlMapper implements RevisionMapperInterface
{
    protected function buildRevisionSelect(Sql $sql, $siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null)
    {
        $select = $sql->select()
                      ->from(array("r" => DbViewRevisions::TABLE))
                      ->where(array('r.'.DbViewRevisions::SITEID.' = ?' => $siteId));
        if ($createdAfter && $createdAfter->getTimestamp() <= time()) {
            $select->where(array('r.'.DbViewRevisions::DATETIME.' >= ?' => $createdAfter->format(self::DATETIME_FORMAT)));
        }
        if ($createdBefore) {
            $select->where(array('r.'.DbViewRevisions::DATETIME.' <= ?' => $createdBefore->format(self::DATETIME_FORMAT)));
        }
        return $select; 
   }
    
    /**
     * {@inheritDoc}
     */
    public function countSiteRevisions($siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildRevisionSelect($sql, $siteId, $createdAfter, $createdBefore);
        return $this->fetchCount($sql, $select);
    }

    /**
     * {@inheritDoc}
     */    
    public function getAggregatedValues($siteId, $aggregates, \DateTime $createdAfter, \DateTime $createdBefore)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildRevisionSelect($sql, $siteId, $createdAfter, $createdBefore);
        $this->aggregateSelect($select, $aggregates);
        return $this->fetchArray($sql, $select);
    }
}