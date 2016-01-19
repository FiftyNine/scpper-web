<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'generate-consts' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/generateConsts',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'generateConsts',
                    ),
                ),
            ),            
            'select-site' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/select-site',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'selectSite',
                    )
                )            
            ),
            'recent' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/recent',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Recent',
                        'action' => 'recent',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action',
                            'constraints' => array(                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Recent',
                            ),
                        ),
                    ),
                ),                
            ),            
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            // Services
            'Application\Service\SiteServiceInterface' => 'Application\Factory\Service\SiteServiceFactory',
            'Application\Service\UserServiceInterface' => 'Application\Factory\Service\UserServiceFactory',
            'Application\Service\PageServiceInterface' => 'Application\Factory\Service\PageServiceFactory',
            'Application\Service\RevisionServiceInterface' => 'Application\Factory\Service\RevisionServiceFactory',
            'Application\Service\VoteServiceInterface' => 'Application\Factory\Service\VoteServiceFactory',
            'Application\Service\UtilityServiceInterface' => 'Application\Factory\Service\UtilityServiceFactory',
            'Application\Service\HubServiceInterface' => 'Application\Factory\Service\HubServiceFactory',
            'EventLogger' => 'Application\Factory\Service\FileEventLoggerFactory',
            'Zend\Db\Adapter\Adapter' => 'Application\Factory\Service\DbAdapterFactory',
            // Mappers
            'SiteMapper' => 'Application\Factory\Mapper\SiteDbSqlMapperFactory',
            'UserMapper' => 'Application\Factory\Mapper\UserDbSqlMapperFactory',
            'PageMapper' => 'Application\Factory\Mapper\PageDbSqlMapperFactory',
            'RevisionMapper' => 'Application\Factory\Mapper\RevisionDbSqlMapperFactory',
            'VoteMapper' => 'Application\Factory\Mapper\VoteDbSqlMapperFactory',
            // Zend
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',            
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',                       
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Application\Controller\Index' => 'Application\Factory\Controller\IndexControllerFactory',
            'Application\Controller\Recent' => 'Application\Factory\Controller\RecentControllerFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),        
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Main',
                'route' => 'home',
            ),
            array(
                'label' => 'Recent',
                'route' => 'recent',
            ),
        )
    )
);