<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
            ],
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
            'about' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'about',
                    ],                    
                ]
            ],
/*            'generate-consts' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/generateConsts',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'generateConsts',
                    ],
                ],
            ],            */
            'select-site' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/select-site',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action' => 'selectSite',
                    ]
                ]            
            ],
            'extension-page-info' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/extension-page-info',
                    'defaults' => [
                        'controller' => 'Application\Controller\Index',
                        'action' => 'extensionPageInfo',
                    ]
                ]            
            ],
            'search' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/search',
                    'defaults' => [
                        'controller' => 'Application\Controller\Search',
                        'action'     => 'search',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Search',
                            ],
                        ],
                    ],
                ],                                
            ],            
            'activity' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/activity',
                    'defaults' => [
                        'controller' => 'Application\Controller\Activity',
                        'action' => 'activity',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Activity',
                            ],
                        ],
                    ],
                ],                
            ],            
            'users' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/users',
                    'defaults' => [
                        'controller' => 'Application\Controller\Users',
                        'action'     => 'users',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Users',
                            ],
                        ],
                    ],
                ],                                
            ],            
            'pages' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/pages',
                    'defaults' => [
                        'controller' => 'Application\Controller\Pages',
                        'action'     => 'pages',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'deleted' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/deleted',
                            'defaults' => [
                                'controller' => 'Application\Controller\Page',
                                'action' => 'deleted'
                            ],
                        ],                        
                    ],
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Pages',
                            ],
                        ],
                    ],
                ],                                
            ],            
            'revisions' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/revisions',
                    'defaults' => [
                        'controller' => 'Application\Controller\Revisions',
                        'action'     => 'revisions',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Revisions',
                            ],
                        ],
                    ],
                ],                                
            ],            
            'votes' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/votes',
                    'defaults' => [
                        'controller' => 'Application\Controller\Votes',
                        'action'     => 'votes',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Votes',
                            ],
                        ],
                    ],
                ],                                
            ],            
            'page' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/page',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'page' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:pageId',
                            'constraints' => [                                
                                'pageId'     => '[1-9][0-9]*',
                            ],                                                
                            'defaults' => [
                                'controller' => 'Application\Controller\Page',
                                'action' => 'page'
                            ],
                        ],                        
                    ],
                    'report' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/report',
                            'defaults' => [
                                'controller' => 'Application\Controller\Page',
                                'action' => 'report'
                            ],
                        ],                        
                    ],                    
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Page',
                            ],
                        ],
                    ],
                ],                                                
            ],
            'user' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/user',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'user' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:userId',
                            'constraints' => [                                
                                'pageId'     => '[1-9][0-9]*',
                            ],                                                
                            'defaults' => [
                                'controller' => 'Application\Controller\User',
                                'action' => 'user'
                            ],
                        ],                        
                    ],
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\User',
                            ],
                        ],
                    ],
                ],                                                
            ],            
            'tags' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/tags',
                    'defaults' => [
                        'controller' => 'Application\Controller\Tags',
                        'action'     => 'tags',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\Tags',
                            ],
                        ],
                    ],
                ],                                
            ],
            'admin' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => 'Application\Controller\AdminPanel',
                        'action' => 'admin'
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action',
                            'constraints' => [                                
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'controller' => 'Application\Controller\AdminPanel',
                            ],
                        ],
                    ],
                ],                     
            ],
            'api' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/api',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'tags' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/tags',
                            'defaults' => [
                                'controller' => 'Application\Controller\Tags',
                                'action' => 'apiSearch'
                            ],
                        ],                        
                    ],                       
                    'findPages' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/find-pages',
                            'defaults' => [
                                'controller' => 'Application\Controller\Search',
                                'action' => 'apiFindPages'
                            ],
                        ],                        
                    ],
                    'findUsers' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/find-users',
                            'defaults' => [
                                'controller' => 'Application\Controller\Search',
                                'action' => 'apiFindUsers'
                            ],
                        ],                        
                    ],
                    'page' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/page',
                            'defaults' => [
                                'controller' => 'Application\Controller\Page',
                                'action' => 'apiPage'
                            ],
                        ],
                    ],
                    'user' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/user',
                            'defaults' => [
                                'controller' => 'Application\Controller\User',
                                'action' => 'apiUser'
                            ],
                        ],
                    ],                    
                ],                     
            ],            
        ],
    ],
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
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'factories' => [
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
        ],
        'delegators' => [
            'SiteMapper' => ['LazyServiceFactory'],
            'UserMapper' => ['LazyServiceFactory'],
            'PageMapper' => ['LazyServiceFactory'],
            'RevisionMapper' => ['LazyServiceFactory'],
            'VoteMapper' => ['LazyServiceFactory'],            
            'AuthorshipMapper' => ['LazyServiceFactory'],            
            'UserActivityMapper' => ['LazyServiceFactory'],            
            'MembershipMapper' => ['LazyServiceFactory'],
            'PageReportMapper' => ['LazyServiceFactory'],
        ],        
    ],
    'lazy_services' => [
        'class_map' => [
            'SiteMapper' => 'Application\Mapper\SiteDbSqlMapper',
            'UserMapper' => 'Application\Mapper\UserDbSqlMapper',
            'PageMapper' => 'Application\Mapper\PageDbSqlMapper',
            'RevisionMapper' => 'Application\Mapper\RevisionDbSqlMapper',
            'VoteMapper' => 'Application\Mapper\VoteDbSqlMapper',
            'AuthorshipMapper' => 'Application\Mapper\AuthorshipDbSqlMapper',
            'UserActivityMapper' => 'Application\Mapper\UserActivityDbSqlMapper',
            'MembershipMapper' => 'Application\Mapper\MembershipDbSqlMapper',
            'PageReportMapper' => 'Application\Mapper\PageReportDbSqlMapper',
        ],
    ],    
    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
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
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => ini_get('display_errors'),
        'display_exceptions'       => ini_get('display_errors'),
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],        
    ],
    // Placeholder for console routes
    'console' => [
        'router' => [
            'routes' => [
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Wiki',
                'type' => 'Uri',
                'uri' => '#'
            ],
            [
                'label' => 'Users',
                'route' => 'users',
            ],
            [
                'label' => 'Pages',
                'route' => 'pages',
                'pages' => [
                    [
                        'label' => 'Existing',
                        'route' => 'pages',                                                
                    ],
                    [
                        'label' => 'Deleted',
                        'route' => 'pages/deleted',                        
                    ]
                ],                
            ],
            [
                'label' => 'Revisions',
                'route' => 'revisions',
            ],            
            [
                'label' => 'Votes',
                'route' => 'votes',
            ],            
            [
                'label' => 'Tags',
                'route' => 'tags',
            ],
            [
                'label' => 'Activity',
                'route' => 'activity',
            ],
            [
                'label' => 'About',
                'route' => 'about',
            ],            
        ]
    ]
];
