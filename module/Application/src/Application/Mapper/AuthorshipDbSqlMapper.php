<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\PageType;
use Application\Model\AuthorSummary;
use Application\Utils\AuthorSummaryConsts;

/**
 * Description of AuthorshipMapper
 *
 * @author Alexander
 */
class AuthorshipDbSqlMapper extends ZendDbSqlMapper implements AuthorshipMapperInterface
{
    const COLUMNS = array(
        DbViewAuthors::PAGEID, 
        DbViewAuthors::USERID,
        DbViewAuthors::ROLEID        
    );
    
    /**
     *
     * @var Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected $summaryHydrator;
    
    /**
     * 
     * @param type $sql
     * @return Select
     */
    protected function buildAuthorSummarySelect(Sql $sql)
    {
        $select = $sql->select(DbViewAuthors::TABLE)
                ->columns(array(
                        DbViewAuthors::USERID => DbViewAuthors::USERID,
                        DbViewAuthors::SITEID => DbViewAuthors::SITEID,
                        AuthorSummaryConsts::PAGES => new Expression('COUNT(*)'),
                        AuthorSummaryConsts::ORIGINALS => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::STATUSID, PageType::ORIGINAL)),
                        AuthorSummaryConsts::TRANSLATIONS => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::STATUSID, PageType::TRANSLATION)),
                        AuthorSummaryConsts::TOTAL_RATING => new Expression(sprintf('SUM(%s)', DbViewAuthors::RATING)),
                        AuthorSummaryConsts::HIGHEST_RATING => new Expression(sprintf('MAX(%s)', DbViewAuthors::RATING))
                ))
                ->group(array(
                        DbViewAuthors::USERID, 
                        DbViewAuthors::SITEID
                ));
        return $select;
    }
    
    /**
     * @return Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected function getSummaryHydrator()
    {
        if (!isset($this->summaryHydrator)) {
            $this->summaryHydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
        }
        return $this->summaryHydrator;
    }
    
    /**
     * {@inheritDoc}
     */
    public function findAuthorshipsOfPage($pageId) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewAuthors::TABLE)
                ->columns(self::COLUMNS)
                ->where(array(DbViewAuthors::PAGEID.' = ?' => $pageId));
        return $this->fetchResultSet($sql, $select);        
    }

    /**
     * {@inheritDoc}
     */
    public function findAuthorshipsOfUser($userId, $siteId = -1) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewAuthors::TABLE)
                ->columns(self::COLUMNS)
                ->where(array(DbViewAuthors::USERID.' = ?' => $userId));
        if ($siteId > 0) {
            $select->where(array(DbViewAuthors::SITEID.' = ?' => $siteId));
        }
        return $this->fetchResultSet($sql, $select);        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorSummary($userId, $siteId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildAuthorSummarySelect($sql);
        $select->where(array(
            DbViewAuthors::USERID.' = ?' => $userId,
            DbViewAuthors::SITEID.' = ?' => $siteId,
        ));
        $this->fetchObject($sql, $select, $this->getSummaryHydrator(), new AuthorSummary());
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorSummaries($siteId, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildAuthorSummarySelect($sql);
        $select->where(array(
            DbViewAuthors::SITEID.' = ?' => $siteId,
        ));
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select, false, $this->getSummaryHydrator(), new AuthorSummary());
        } else {
            return $this->fetchResultSet($sql, $select, $this->getSummaryHydrator(), new AuthorSummary());
        }
    }
}
