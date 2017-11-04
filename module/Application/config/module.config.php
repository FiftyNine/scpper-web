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
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => 'Application\Controller\Login',
                        'action'     => 'login',
                    ],
                ],                
            ],
            'about' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/about',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'about',
                    ),                    
                )
            ),
/*            'generate-consts' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/generateConsts',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'generateConsts',
                    ),
                ),
            ),            */
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
            'extension-page-info' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/extension-page-info',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'extensionPageInfo',
                    )
                )            
            ),
            'search' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/search',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Search',
                        'action'     => 'search',
                    ),
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
                                'controller' => 'Application\Controller\Search',
                            ),
                        ),
                    ),
                ),                                
            ),            
            'activity' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/activity',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Activity',
                        'action' => 'activity',
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
                                'controller' => 'Application\Controller\Activity',
                            ),
                        ),
                    ),
                ),                
            ),            
            'users' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/users',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Users',
                        'action'     => 'users',
                    ),
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
                                'controller' => 'Application\Controller\Users',
                            ),
                        ),
                    ),
                ),                                
            ),            
            'pages' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/pages',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Pages',
                        'action'     => 'pages',
                    ),
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
                                'controller' => 'Application\Controller\Pages',
                            ),
                        ),
                    ),
                ),                                
            ),            
            'revisions' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/revisions',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Revisions',
                        'action'     => 'revisions',
                    ),
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
                                'controller' => 'Application\Controller\Revisions',
                            ),
                        ),
                    ),
                ),                                
            ),            
            'votes' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/votes',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Votes',
                        'action'     => 'votes',
                    ),
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
                                'controller' => 'Application\Controller\Votes',
                            ),
                        ),
                    ),
                ),                                
            ),            
            'page' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/page',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'page' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:pageId',
                            'constraints' => array(                                
                                'pageId'     => '[1-9][0-9]*',
                            ),                                                
                            'defaults' => array(
                                'controller' => 'Application\Controller\Page',
                                'action' => 'page'
                            ),
                        ),                        
                    ),
                    'report' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/report',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Page',
                                'action' => 'report'
                            ),
                        ),                        
                    ),                    
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action',
                            'constraints' => array(                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Page',
                            ),
                        ),
                    ),
                ),                                                
            ),
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/user',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'user' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:userId',
                            'constraints' => array(                                
                                'pageId'     => '[1-9][0-9]*',
                            ),                                                
                            'defaults' => array(
                                'controller' => 'Application\Controller\User',
                                'action' => 'user'
                            ),
                        ),                        
                    ),
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action',
                            'constraints' => array(                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\User',
                            ),
                        ),
                    ),
                ),                                                
            ),            
            'tags' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/tags',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Tags',
                        'action'     => 'tags',
                    ),
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
                                'controller' => 'Application\Controller\Tags',
                            ),
                        ),
                    ),
                ),                                
            ),
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Application\Controller\AdminPanel',
                        'action' => 'admin'
                    ),
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
                                'controller' => 'Application\Controller\AdminPanel',
                            ),
                        ),
                    ),
                ),                     
            ),
            'api' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'report' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/tags',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Tags',
                                'action' => 'apiSearch'
                            ),
                        ),                        
                    ),                       
                ),                     
            ),            
        ),
    ),
    'session' => [
           'config' => [
               'class' => 'Zend\Session\Config\SessionConfig',
               'options' => [
                   'name' => 'scpper',
               ],
           ],
           'storage' => 'Zend\Session\Storage\SessionArrayStorage',
           'validators' => [
               'Zend\Session\Validator\RemoteAddr',
               'Zend\Session\Validator\HttpUserAgent'
        ],
    ],    
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
            'Application\Service\TagServiceInterface' => 'Application\Factory\Service\TagServiceFactory',
            'Application\Service\HubServiceInterface' => 'Application\Factory\Service\HubServiceFactory',
            'EventLogger' => 'Application\Factory\Service\FileEventLoggerFactory',            
            // Mappers
            'SiteMapper' => 'Application\Factory\Mapper\SiteDbSqlMapperFactory',
            'UserMapper' => 'Application\Factory\Mapper\UserDbSqlMapperFactory',
            'PageMapper' => 'Application\Factory\Mapper\PageDbSqlMapperFactory',
            'RevisionMapper' => 'Application\Factory\Mapper\RevisionDbSqlMapperFactory',
            'VoteMapper' => 'Application\Factory\Mapper\VoteDbSqlMapperFactory',
            'AuthorshipMapper' => 'Application\Factory\Mapper\AuthorshipDbSqlMapperFactory',
            'UserActivityMapper' => 'Application\Factory\Mapper\UserActivityDbSqlMapperFactory',
            'MembershipMapper' => 'Application\Factory\Mapper\MembershipDbSqlMapperFactory',
            'TagMapper' => 'Application\Factory\Mapper\TagDbSqlMapperFactory',
            'PageReportMapper' => 'Application\Factory\Mapper\PageReportDbSqlMapperFactory',
            // Model object prototypes
            'SitePrototype' => 'Application\Factory\Prototype\SitePrototypeFactory',
            'UserPrototype' => 'Application\Factory\Prototype\UserPrototypeFactory',
            'PagePrototype' => 'Application\Factory\Prototype\PagePrototypeFactory',
            'RevisionPrototype' => 'Application\Factory\Prototype\RevisionPrototypeFactory',
            'VotePrototype' => 'Application\Factory\Prototype\VotePrototypeFactory',
            'AuthorshipPrototype' => 'Application\Factory\Prototype\AuthorshipPrototypeFactory',
            'UserActivityPrototype' => 'Application\Factory\Prototype\UserActivityPrototypeFactory',
            'MembershipPrototype' => 'Application\Factory\Prototype\MembershipPrototypeFactory',
            'PageReportPrototype' => 'Application\Factory\Prototype\PageReportPrototypeFactory',
            // Overrides
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',            
            'Zend\Db\Adapter\Adapter' => 'Application\Factory\Service\DbAdapterFactory',
            'navigation' => 'Application\Factory\Service\NavigationFactory', // Zend\Navigation\Service\DefaultNavigationFactory',         
            'LazyServiceFactory' => 'Zend\ServiceManager\Proxy\LazyServiceFactoryFactory',            
            'Zend\Session\SessionManager' => 'Application\Factory\Service\SessionManagerFactory',
            'Zend\Authentification\AuthentificationService' => 'Application\Factory\Service\AuthentificationServiceFactory'
        ),
        'delegators' => array(
            'SiteMapper' => array('LazyServiceFactory'),
            'UserMapper' => array('LazyServiceFactory'),
            'PageMapper' => array('LazyServiceFactory'),
            'RevisionMapper' => array('LazyServiceFactory'),
            'VoteMapper' => array('LazyServiceFactory'),            
            'AuthorshipMapper' => array('LazyServiceFactory'),            
            'UserActivityMapper' => array('LazyServiceFactory'),            
            'MembershipMapper' => array('LazyServiceFactory'),
            'PageReportMapper' => array('LazyServiceFactory'),
        ),        
    ),
    'lazy_services' => array(
        'class_map' => array(
            'SiteMapper' => 'Application\Mapper\SiteDbSqlMapper',
            'UserMapper' => 'Application\Mapper\UserDbSqlMapper',
            'PageMapper' => 'Application\Mapper\PageDbSqlMapper',
            'RevisionMapper' => 'Application\Mapper\RevisionDbSqlMapper',
            'VoteMapper' => 'Application\Mapper\VoteDbSqlMapper',
            'AuthorshipMapper' => 'Application\Mapper\AuthorshipDbSqlMapper',
            'UserActivityMapper' => 'Application\Mapper\UserActivityDbSqlMapper',
            'MembershipMapper' => 'Application\Mapper\MembershipDbSqlMapper',
            'PageReportMapper' => 'Application\Mapper\PageReportDbSqlMapper',
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
            'Application\Controller\Search' => 'Application\Factory\Controller\SearchControllerFactory',
            'Application\Controller\Activity' => 'Application\Factory\Controller\ActivityControllerFactory',
            'Application\Controller\Users' => 'Application\Factory\Controller\UsersControllerFactory',
            'Application\Controller\Pages' => 'Application\Factory\Controller\PagesControllerFactory',
            'Application\Controller\Revisions' => 'Application\Factory\Controller\RevisionsControllerFactory',
            'Application\Controller\Votes' => 'Application\Factory\Controller\VotesControllerFactory',
            'Application\Controller\Page' => 'Application\Factory\Controller\PageControllerFactory',
            'Application\Controller\User' => 'Application\Factory\Controller\UserControllerFactory',
            'Application\Controller\Tags' => 'Application\Factory\Controller\TagsControllerFactory',
            'Application\Controller\AdminPanel' => 'Application\Factory\Controller\AdminPanelControllerFactory',
            'Application\Controller\Login' => 'Application\Factory\Controller\LoginControllerFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => ini_get('display_errors'),
        'display_exceptions'       => ini_get('display_errors'),
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
                'label' => 'Wiki',
                'type' => 'Uri',
                'uri' => '#'
            ),
            array(
                'label' => 'Users',
                'route' => 'users',
            ),
            array(
                'label' => 'Pages',
                'route' => 'pages',
            ),
            array(
                'label' => 'Revisions',
                'route' => 'revisions',
            ),            
            array(
                'label' => 'Votes',
                'route' => 'votes',
            ),            
            array(
                'label' => 'Tags',
                'route' => 'tags',
            ),
            array(
                'label' => 'Activity',
                'route' => 'activity',
            ),
            array(
                'label' => 'About',
                'route' => 'about',
            ),            
        )
    )
);
