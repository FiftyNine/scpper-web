<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\DbConsts\DbViewUserActivity;

/**
 * Description of UserActivityDbSqlMapper
 *
 * @author Alexander
 */
class UserActivityDbSqlMapper extends ZendDbSqlMapper implements UserActivityMapperInterface
{
    const COLUMNS = array(
        DbViewUserActivity::USERID,
        DbViewUserActivity::SITEID,
        DbViewUserActivity::VOTES,
        DbViewUserActivity::REVISIONS,
        DbViewUserActivity::PAGES,
        DbViewUserActivity::LASTACTIVITY,
    );
    
    /**
     * {@inheritDoc}
     */
    public function findUserActivity($userId, $siteId) 
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select(DbViewUserActivity::TABLE)
                ->columns(self::COLUMNS)
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
                ->columns(self::COLUMNS)
                ->where(array(
                    DbViewUserActivity::USERID.' = ?' => $userId
                ));
        return $this->fetchResultSet($sql, $select);        
    }
}
