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
use Application\Model\Authorship;
use Application\Mapper\AuthorshipDbSqlMapper;
use Application\Utils\DbConsts\DbViewAuthors;

/**
 * Description of AuthorshipDbSqlMapperFactory
 *
 * @author Alexander
 */
class AuthorshipDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $names = new \Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy(array(
            DbViewAuthors::ROLEID => 'role'
        ));
        $hydrator->setNamingStrategy($names);
        $userMapper = $serviceLocator->get('UserMapper');        
        $pageMapper = $serviceLocator->get('PageMapper');
        $prototype = new Authorship($userMapper, $pageMapper);
        return new AuthorshipDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewAuthors::TABLE, '');
    }
}
