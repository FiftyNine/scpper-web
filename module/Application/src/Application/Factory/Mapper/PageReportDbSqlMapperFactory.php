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
use Application\Utils\DbConsts\DbPageReports;

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
            DbPageReports::PAGEID => 'pageId',
            DbPageReports::REPORTER => 'reporter',
            DbPageReports::STATUSID => 'status',
            DbPageReports::KINDID => 'kind',
            DbPageReports::ORIGINALID => 'originalId',
            DbPageReports::CONTRIBUTORS => 'contributorsJson']);
        $hydrator->setNamingStrategy($namingStrat);
        $hydrator->addFilter('excluded',
                function($prop) {
                    $methods = [
                        'getPage',
                        'getOriginalPage',
                        'getPageName',
                        'getSiteName',
                        'getOriginalPageName',
                        'getOriginalSiteName',
                        'hasOriginal',
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
        $hydrator->addStrategy('processed', new \Zend\Stdlib\Hydrator\Strategy\BooleanStrategy('1', '0'));
        $prototype = $serviceLocator->get('PageReportPrototype');
        return new PageReportDbSqlMapper($dbAdapter, $hydrator, $prototype, DbPageReports::TABLE, DbPageReports::ID);
    }
}
