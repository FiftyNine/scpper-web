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
use Zend\Session\Container;
use Zend\Session;

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
        $eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'attachLayoutForms'], 100);        
        $eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'setLayoutTitle']);
        $this->bootstrapSession($e);
        // Initialize logger
        $serviceManager->get('EventLogger');
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
    
    public function attachLayoutForms($event)
    {
        $viewModel = $event->getViewModel();
        if (!($viewModel instanceof \Zend\View\Model\JsonModel)) {
            $utils = $event->getApplication()->getServiceManager()->get('Application\Service\UtilityServiceInterface');        
            $utils->attachSearchForm($viewModel);
        }
    }        
    
    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayoutTitle($e)
    {
        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper   = $viewHelperManager->get('headTitle');

        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');
    }
    
    public function bootstrapSession(MvcEvent $e)
    {        
        $session = $e->getApplication()
            ->getServiceManager()
            ->get('\Zend\Session\SessionManager');
        $session->start();
        
        $container = new Container('initialized');

        if (isset($container->init)) {
            return;
        }

        $serviceManager = $e->getApplication()->getServiceManager();
        $request        = $serviceManager->get('Request');

        $session->regenerateId(true);
        $container->init          = 1;
        $container->remoteAddr    = $request->getServer()->get('REMOTE_ADDR');
        $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

        $config = $serviceManager->get('Config');
        if (!isset($config['session'])) {
            return;
        }

        $sessionConfig = $config['session'];

        if (!isset($sessionConfig['validators'])) {
            return;
        }

        $chain   = $session->getValidatorChain();

        foreach ($sessionConfig['validators'] as $validator) {
            switch ($validator) {
            case Validator\HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                    case Validator\RemoteAddr::class:
                    $validator  = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }

            $chain->attach('session.validate', [$validator, 'isValid']);
        }
    }    
}
