<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Application\Model\PageReportInterface;
use Application\Utils\DbConsts\DbPageStatus;
use Application\Utils\DbConsts\DbAuthors;

/**
 * Description of PageReportDbSqlMapper
 *
 * @author Alexander
 */
class PageReportDbSqlMapper extends ZendDbSqlMapper implements PageReportMapperInterface
{
   /**
    * {@inheritDoc}
    */
    public function save(PageReportInterface $report)
    {
        $data = $this->hydrator->extract($report);
        unset($data[$this->table]); // Neither Insert nor Update needs the ID in the array

        if ($report->getId()) {
            // ID present, it's an Update
            $action = new Update($this->table);
            $action->set($data);
            $action->where(array($this->idFieldName.' = ?' => $report->getId()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert($this->table);
            $action->values($data);
        }

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $this->logQuery($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $report->setId($newId);
            }

            return $report;
        }
        throw new \Exception("Database error");        
    }
   
   /**
    * {@inheritDoc}
    */
    public function apply(PageReportInterface $report)
    {
        if (!$report->getPage()) {
           throw new \InvalidArgumentException(sprintf("Page [Id = %d] does not exist", $report->getPageId()));
        }
        $this->dbAdapter->getDriver()->getConnection()->beginTransaction();
        try {
            $sql = new Sql($this->dbAdapter);        
            // Status, Kind, OriginalId, Fixed(true?) - update page_status
            $update = new Update(DbPageStatus::TABLE);
            $update->set([
                DbPageStatus::STATUSID => $report->getStatus(),
                DbPageStatus::KINDID => $report->getKind(),
                DbPageStatus::ORIGINALID => $report->getOriginalId(),
                DbPageStatus::FIXED => '1'
            ]);
            $update->where([DbPageStatus::PAGEID.' = ?' => $report->getPageId()]);
            $stmt = $sql->prepareStatementForSqlObject($update);
            $this->logQuery($update);
            $stmt->execute();        

            $delete = new Delete(DbAuthors::TABLE);
            $delete->where([DbPageStatus::PAGEID.' = ?' => $report->getPageId()]);
            $stmt = $sql->prepareStatementForSqlObject($delete);
            $this->logQuery($delete);
            $stmt->execute();        

            $insert = new Insert(DbAuthors::TABLE);
            $insert->values([
                    DbAuthors::PAGEID => '',//$report->getPageId(),
                    DbAuthors::USERID => '',//$contrib->getUserId(),
                    DbAuthors::ROLEID => '',//$contrib->getRole(),
                ]);
            $stmt = $sql->prepareStatementForSqlObject($insert);
            foreach ($report->getContributors() as $contrib) {
                $stmt->execute([
                    DbAuthors::PAGEID => $report->getPageId(),
                    DbAuthors::USERID => $contrib->getUserId(),
                    DbAuthors::ROLEID => $contrib->getRole(),
                ]);
            }
            $this->dbAdapter->getDriver()->getConnection()->commit();
        } catch (\Exception $e) {
            $this->dbAdapter->getDriver()->getConnection()->rollback();
            throw $e;
        }
        
       // DELETE from authors
       // INSERT into authors
    }
   
}
