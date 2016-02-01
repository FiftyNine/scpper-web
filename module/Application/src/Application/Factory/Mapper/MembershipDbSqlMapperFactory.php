<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Hydrator\MembershipDbHydrator;
use Application\Hydrator\UserActivityDbHydrator;
use Application\Hydrator\UserSiteDbHydrator;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Utils\DbConsts\DbViewUsers;
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
        // Prototypes
        $membershipPrototype = $serviceLocator->get('MembershipPrototype');
        $activityPrototype = $serviceLocator->get('UserActivityPrototype');
        $userPrototype = $serviceLocator->get('UserPrototype');
        // Hydrators
        $mainHydrator = new MembershipDbHydrator();
        $membershipHydrator = new MembershipDbHydrator(DbViewMembership::TABLE);        
        $activityHydrator = new UserActivityDbHydrator(DbViewUserActivity::TABLE);        
        $userHydrator = new UserSiteDbHydrator($membershipHydrator, $membershipPrototype, $activityHydrator, $activityPrototype, DbViewUsers::TABLE);
        return new MembershipDbSqlMapper($dbAdapter, $mainHydrator, $membershipPrototype, DbViewMembership::TABLE, '', $userHydrator, $userPrototype);                        
    }

}
