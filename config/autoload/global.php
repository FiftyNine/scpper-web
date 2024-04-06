<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

 return [
     'db' => [
         'driver'         => 'Pdo',         
         'driver_options' => [
             //PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
             PDO::ATTR_EMULATE_PREPARES => false         
         ],
     ],
     'service_manager' => [
         'factories' => [
             'Zend\Db\Adapter\Adapter'
                     => 'Application\Factory\Service\DbAdapterFactory',
         ],
     ],
 ];
