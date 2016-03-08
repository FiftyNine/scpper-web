<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Application\Utils\DbConsts\DbSites;

/**
 * Description of SiteDbSqlMapper
 *
 * @author Alexander
 */
class SiteDbSqlMapper extends ZendDbSqlMapper implements SiteMapperInterface
{
    /**
     * 
     * {@inheritDoc}
     */
    public function findAll($conditions = null, $paginated = false) {
        $sql = new Sql($this->dbAdapter);        
        $select = $sql->select($this->table);
        if (is_array($conditions)) {
            $select->where($conditions);
        }
        $select->order(sprintf('%s %s', DbSites::WIKIDOTID, Select::ORDER_ASCENDING));
        if ($paginated) {
            $result = $this->getPaginator($select);
        } else {
            $result = $this->fetchResultSet($sql, $select);
        }
        if (!$result) {
            $result = array();
        }                    
        return $result;
    }
}
