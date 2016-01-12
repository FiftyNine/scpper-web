<?php

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\DateGroupType;
use Application\Utils\DbConsts\DbViewRevisions;

class RevisionDbSqlMapper extends ZendDbSqlMapper implements RevisionMapperInterface
{
    protected function buildRevisionSelect(Sql $sql, $siteId, \DateTime $createdAfter = null, \DateTime $createdBefore = null)
    {
        $select = $sql->select()
                      ->from(array("r" => DbViewRevisions::TABLE))
                      ->where(array('r.'.DbViewRevisions::SITEID.' = ?' => $siteId));
        if ($createdAfter && $createdAfter->getTimestamp() <= time()) {
            $select->where(array('r.'.DbViewRevisions::DATETIME.' >= ?' => $createdAfter->format('Y-m-d H:i:s')));
        }
        if ($createdBefore) {
            $select->where(array('r.'.DbViewRevisions::DATETIME.' <= ?' => $createdBefore->format('Y-m-d H:i:s')));
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
    public function countCreatedRevisions($siteId, \DateTime $createdAfter, \DateTime $createdBefore, $groupBy = DateGroupType::DAY)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildRevisionSelect($sql, $siteId, $createdAfter, $createdBefore);
        return $this->fetchCountGroupedByDate($sql, $select, DbViewRevisions::DATETIME, $groupBy);                
    }
}