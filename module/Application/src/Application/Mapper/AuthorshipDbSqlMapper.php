<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Application\Utils\DbConsts\DbViewAuthors;

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
}
