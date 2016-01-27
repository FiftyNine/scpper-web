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
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Model\Membership;
use Application\Mapper\MembershipDbSqlMapper;

/**
 * Description of MembershipDbSqlMapperFactory
 *
 * @author Alexander
 */
class MembershipDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods(); 
        $hydrator->addStrategy(DbViewMembership::JOINDATE, new DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $siteMapper = $serviceLocator->get('SiteMapper');        
        $userMapper = $serviceLocator->get('UserMapper');        
        $prototype = new Membership($siteMapper, $userMapper);
        return new MembershipDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewMembership::TABLE, '');                        
    }

}
