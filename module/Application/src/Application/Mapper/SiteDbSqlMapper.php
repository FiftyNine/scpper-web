<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Application\Utils\DbConsts\DbViewSites;

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
    public function findAll($conditions = null, $order = null, $paginated = false) {
        if (!$order) {
            $order = array(DbViewSites::SITEID => Select::ORDER_ASCENDING);
        }        
        return parent::findAll($conditions, $order, $paginated);
    }
}
