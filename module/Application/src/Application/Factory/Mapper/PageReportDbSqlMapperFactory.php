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
use Application\Mapper\PageReportDbSqlMapper;
use Application\Utils\DbConsts\DbViewPageReports;

/**
 * Description of PageReportDbSqlMapperFactory
 *
 * @author Alexander
 */

class PageReportDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $namingStrat = new \Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy([
            DbViewPageReports::PAGEID => 'pageId',
            DbViewPageReports::REPORTER => 'reporter',
            DbViewPageReports::DATE => 'date',
            DbViewPageReports::STATUSID => 'status',
            DbViewPageReports::OLDSTATUSID => 'oldStatus',
            DbViewPageReports::OLDSTATUS => '',
            DbViewPageReports::KINDID => 'kind',
            DbViewPageReports::OLDKINDID => 'oldKind',
            DbViewPageReports::OLDKIND => '',
            DbViewPageReports::ORIGINALID => 'originalId',
            DbViewPageReports::CONTRIBUTORS => 'contributorsJson']);
        $hydrator->setNamingStrategy($namingStrat);
        $hydrator->addFilter('excluded',
                function($prop) {
                    $methods = [
                        'getOldStatus',
                        'getOldKind',
                        'getDate',
                        'getPage',
                        'getOriginalPage',                        
                        'getPageName',
                        'getSiteName',
                        'hasOriginal',
                        'getOriginalPageName',
                        'getOriginalSiteName',
                        'getOriginalSiteId',                        
                        'getContributors'];
                    $pos = strpos($prop, '::');
                    if ($pos !== false) {
                        $pos += 2;
                    } else {
                        $pos = 0;
                    }
                    $method = substr($prop, $pos);                    
                    return false === array_search($method, $methods);
                },
                \Zend\Stdlib\Hydrator\Filter\FilterComposite::CONDITION_AND);
        $hydrator->addStrategy(DbViewPageReports::DATE, new \Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $prototype = $serviceLocator->get('PageReportPrototype');
        return new PageReportDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewPageReports::TABLE, DbViewPageReports::ID);
    }
}
