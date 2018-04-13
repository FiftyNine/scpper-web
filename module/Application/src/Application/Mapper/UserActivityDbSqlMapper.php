<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Utils\DbSelectColumns;
use Application\Utils\QueryAggregateInterface;

/**
 * Description of UserActivityDbSqlMapper
 *
 * @author Alexander
 */
class UserActivityDbSqlMapper extends ZendDbSqlMapper implements UserActivityMapperInterface
{    
    /**
     * {@inheritDoc}
     */
    public function findUserActivity($userId, $siteId) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                ->columns(DbSelectColumns::USER_ACTIVITY)
                ->where([
                    DbViewUserActivity::USERID.' = ?' => $userId,
                    DbViewUserActivity::SITEID.' = ?' => $siteId,
                ]);
        return $this->fetchObject($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findSiteActivities($siteId, $order = null, $paginated = false, $asArray = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                // ->columns('*')
                ->where([
                    DbViewUserActivity::SITEID.' = ?' => $siteId
                ]);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select, $asArray);
        }
        if ($asArray) {
            return $this->fetchArray($sql, $select);
        } else {
            return $this->fetchResultSet($sql, $select);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function findUserActivities($userId) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                ->columns(DbSelectColumns::USER_ACTIVITY)
                ->where([
                    DbViewUserActivity::USERID.' = ?' => $userId
                ]);
        return $this->fetchResultSet($sql, $select);        
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getAggregatedValues($conditions, $aggregates, $order = null, $paginated = false)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                ->columns(DbSelectColumns::USER_ACTIVITY)
                ->where($conditions);
        $this->aggregateSelect($select, $aggregates);
        if (is_array($order)) {
            $this->orderSelect($select, $order);
        }
        if ($paginated) {
            return $this->getPaginator($select, true);
        }
        return $this->fetchArray($sql, $select);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAggregatedValue($conditions, QueryAggregateInterface $aggregate)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                ->columns(['a' => $aggregate->getAggregateExpression()])
                ->where($conditions);
        $res = $this->fetchArray($sql, $select);
        if ($res) {
            return $res[0]['a'];
        }
        return null;
    }
}
