<?php

/* 
 * Copyright (C) 2015 schurix
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace XelaxSiteConfig;

use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Eye4web\SiteConfig\Options\ModuleOptions as EyeOptions;
use BjyAuthorize\Provider;
use BjyAuthorize\Guard;

$routerConfig = array(
	'zfcadmin' => array(
		'child_routes' => array(
			'siteconfig'  => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/config[/:action]',
					'defaults' => array(
						'controller' => Controller\SiteConfigController::class,
						'action' => 'index',
					),
					'constraints' => array(
						'action' => '(index|email)',
					),
				),
			),
		),
	),
);

$guardConfig = array(
	['route' => 'zfcadmin/siteconfig' ,          'roles' => ['administrator']],
);

$ressources = array(
	'siteconfig', // navigation for administration
);

$ressourceAllowRules = array(
	[['moderator'],     'siteconfig', 'list'],
	[['moderator'],     'siteconfig', 'email/list'],
	[['administrator'], 'siteconfig', 'email/edit'],
);


return array(
	'controllers' => array(
		'invokables' => array(
			Controller\SiteConfigController::class => Controller\SiteConfigController::class,
		),
	),
	
	'router' => array(
		'routes' => $routerConfig,
	),
	
	'bjyauthorize' => array(
		// resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            Provider\Resource\Config::class => $ressources,
        ),

		
		'rule_providers' => array(
			Provider\Rule\Config::class => array(
                'allow' => $ressourceAllowRules,
                'deny' => array(),
            )
		),
		
        'guards' => array(
            Guard\Route::class => $guardConfig
		),
	),

	// language options
	'translator' => array(
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),

	'service_manager' => array(
		'factories' => array(
			'goaliomailservice_options' => Options\Service\TransportOptionsFactory::class,
			Options\SiteEmailOptions::class => Options\Service\SiteEmailOptionsFactory::class,
			Reader\DoctrineORMReader::class => function (ServiceManager $sm){
				$objectManager = $sm->get(EntityManager::class);
				$options = $sm->get(EyeOptions::class);
				$reader = new Reader\DoctrineORMReader($objectManager, $options);
				return $reader;
			}
		),
	),
				
	// view options
	'view_manager' => array(
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
	
	// doctrine config
	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__ . '_driver' => array(
				'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class, // use AnnotationDriver
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity') // entity path
			),
			'eye4web_siteconfig_driver' => null,
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
					'Eye4web\SiteConfig' => null,
				)
			)
		),
	),
				
				
    'eye4web' => array(
        'site-config' => array(
            'doctrineORMEntityClass' => Entity\SiteConfig::class,
            'readerClass' => Reader\DoctrineORMReader::class,
        )
    ),
				
);