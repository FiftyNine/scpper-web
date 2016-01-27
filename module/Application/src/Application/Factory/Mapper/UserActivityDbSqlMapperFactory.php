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
use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Model\UserActivity;
use Application\Mapper\UserActivityDbSqlMapper;

/**
 * Description of UserActivityDbSqlMapperFactory
 *
 * @author Alexander
 */
class UserActivityDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods(); 
        $namingStrat = new MapNamingStrategy(array(
            DbViewUserActivity::VOTES => 'voteCount',
            DbViewUserActivity::REVISIONS => 'revisionCount',
            DbViewUserActivity::PAGES => 'authorshipCount',
        ));        
        $hydrator->setNamingStrategy($namingStrat);
        $hydrator->addStrategy(DbViewUserActivity::LASTACTIVITY, new DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $siteMapper = $serviceLocator->get('SiteMapper');        
        $userMapper = $serviceLocator->get('UserMapper');        
        $voteMapper = $serviceLocator->get('VoteMapper');
        $revisionMapper = $serviceLocator->get('RevisionMapper');
        $authorMapper = $serviceLocator->get('AuthorshipMapper');
        $prototype = new UserActivity($siteMapper, $userMapper, $voteMapper, $revisionMapper, $authorMapper);
        return new UserActivityDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewUserActivity::TABLE, '');                        
    }
}
