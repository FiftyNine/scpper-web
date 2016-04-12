<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;
use Application\Utils\DbConsts\DbViewAuthors;
use Application\Utils\DbConsts\DbViewUserRank;
use Application\Utils\PageStatus;
use Application\Model\AuthorSummary;
use Application\Utils\AuthorSummaryConsts;
use Application\Utils\PageKind;

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
                        AuthorSummaryConsts::ORIGINALS => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::STATUSID, PageStatus::ORIGINAL)),
                        AuthorSummaryConsts::TRANSLATIONS => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::STATUSID, PageStatus::TRANSLATION)),
                        AuthorSummaryConsts::SCPS => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::KINDID, PageKind::SCP)),
                        AuthorSummaryConsts::TALES => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::KINDID, PageKind::TALE)),
                        AuthorSummaryConsts::JOKES => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::KINDID, PageKind::JOKE)),
                        AuthorSummaryConsts::GOIS => new Expression(sprintf('SUM(CASE WHEN %s = %d THEN 1 ELSE 0 END)', DbViewAuthors::KINDID, PageKind::GOI)),
                        AuthorSummaryConsts::TOTAL_RATING => new Expression(sprintf('SUM(%s)', DbViewAuthors::RATING)),
                        AuthorSummaryConsts::AVERAGE_RATING => new Expression(sprintf('SUM(%s)/COUNT(*)', DbViewAuthors::RATING)),
                        AuthorSummaryConsts::HIGHEST_RATING => new Expression(sprintf('MAX(%s)', DbViewAuthors::RATING))
                ))
                ->group(array(
                        DbViewAuthors::USERID, 
                        DbViewAuthors::SITEID
                ))
                ->where(
                    array(
                        '('.DbViewAuthors::KINDID.' IS NULL OR '.DbViewAuthors::KINDID.' <> '.PageKind::SERVICE.')'
                    )
                );
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
    public function findAuthorshipsOfUser($userId, $siteId, $order = null, $paginated = false) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewAuthors::TABLE)
                ->columns(self::COLUMNS)
                ->where(array(
                    DbViewAuthors::USERID.' = ?' => $userId,
                    DbViewAuthors::SITEID.' = ?' => $siteId,
                    '('.DbViewAuthors::KINDID.' IS NULL OR '.DbViewAuthors::KINDID.' <> '.PageKind::SERVICE.')'
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
    public function getAuthorSummary($userId, $siteId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $this->buildAuthorSummarySelect($sql);
        $select->where(array(
            DbViewAuthors::USERID.' = ?' => $userId,
            DbViewAuthors::SITEID.' = ?' => $siteId,
        ));
        return $this->fetchObject($sql, $select, $this->getSummaryHydrator(), new AuthorSummary());
    }    

    /**
     * {@inheritDoc}
     */
    public function findUserRank($userId, $siteId)
    {
        $sql = new Sql($this->dbAdapter);
        $subSelect = $sql->select(DbViewUserRank::TABLE)
                ->columns(array(new Expression('COUNT(*)')))
                ->where(array(
                    DbViewUserRank::TOTAL.' > r.'.DbViewUserRank::TOTAL,
                    DbViewUserRank::SITEID.' = r.'.DbViewUserRank::SITEID,
                ));        
        $select = $sql->select(array('r' => DbViewUserRank::TABLE))
                ->columns(array('Rank' => $subSelect), false)
                ->where(array(
                    DbViewUserRank::USERID.' = ?' => $userId,
                    DbViewUserRank::SITEID.' = ?' => $siteId
                ));
        $res = $this->fetchArray($sql, $select);
        if (count($res) === 1) {
            return $res[0]['Rank'];
        }
        return -1;        
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
