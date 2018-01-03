<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class DbAdapterFactory implements FactoryInterface
{
    /**
     * Create db adapter service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Adapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $result = new Adapter($config['db']);
        if (defined('SCPPER_DEBUG')) {
            $profiler = new \Zend\Db\Adapter\Profiler\Profiler();
            $result->setProfiler($profiler);     
            if ($result->getPlatform()->getName() === 'MySQL') {                
                try {
                    $result->query('SET SESSION query_cache_type=0;', Adapter::QUERY_MODE_EXECUTE);
                } catch (\PDOException $e) {
                    // As of MySQL 5.6 query cache is disabled by default, 
                    // deprecated in 5.7 and removed in 8.0
                    // and server returns an error when you try to double-disable it
                    // because reasons.                            
                }
            }
        }
        return $result;
    }
}
