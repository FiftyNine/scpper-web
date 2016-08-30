<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Model\Tag;
use Application\Mapper\TagDbSqlMapper;
use Application\Utils\DbConsts\DbViewTags;

/**
 * Description of TagDbSqlMapperFactory
 *
 * @author Alexander
 */
class TagDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        return new TagDbSqlMapper($dbAdapter, $hydrator, new Tag(), DbViewTags::TABLE, DbViewTags::__ID);
    }    
}
