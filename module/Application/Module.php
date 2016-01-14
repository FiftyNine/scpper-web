<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $eventManager = $app->getEventManager();        
        $serviceManager = $app->getServiceManager();
        $serviceManager->addInitializer(new \Application\Utils\PostInitializer());
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);        
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'attachLayoutForms'), 100);        
        // Initialize logger
        $serviceManager->get('EventLogger');
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
//                    'JPGraph' => __DIR__ . '/../../vendor/jpgraph'
                ),
            ),
        );
    }
    
    public function attachLayoutForms($event)
    {
        $viewModel = $event->getViewModel();        
        $utils = $event->getApplication()->getServiceManager()->get('Application\Service\UtilityServiceInterface');
        $utils->attachSiteSelectorForm($viewModel);
    }        
}
