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
                ->where(array(
                    DbViewUserActivity::USERID.' = ?' => $userId,
                    DbViewUserActivity::SITEID.' = ?' => $siteId,
                ));
        return $this->fetchObject($sql, $select);
    }

    /**
     * {@inheritDoc}
     */
    public function findUserActivities($userId) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                ->columns(DbSelectColumns::USER_ACTIVITY)
                ->where(array(
                    DbViewUserActivity::USERID.' = ?' => $userId
                ));
        return $this->fetchResultSet($sql, $select);        
    }
}
