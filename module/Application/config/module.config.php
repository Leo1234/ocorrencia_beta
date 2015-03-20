<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'IndexController' => 'Application\Controller\IndexController',
            'DadosController' => 'Application\Controller\DadosController',
            //'AreController' => 'Application\Controller\AreaController',
            'Application\Controller\Area' => 'Application\Controller\AreaController',
            'MunicipioController' => 'Application\Controller\MunicipioController',
            'VitimaController' => 'Application\Controller\VitimaController',
            'PolicialController' => 'Application\Controller\PolicialController',
            'OcorrenciaController' => 'Application\Controller\OcorrenciaController',
            'ViaturaController' => 'Application\Controller\ViaturaController'
        ),
    ),
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/home',
            'defaults' => array(
                'controller' => 'IndexController',
                'action' => 'index',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            # literal para action index home
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'IndexController',
                        'action' => 'index',
                    ),
                ),
            ),
            'config' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/config[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'DadosController',
                        'action' => 'index',
                    ),
                ),
            ),
            # segment para controller vitimas
            'vitimas' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vitimas[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'VitimaController',
                        'action' => 'index',
                    ),
                ),
            ),
            'area' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/area[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Area',
                        'action' => 'index',
                    ),
                ),
            ),
            # segment para controller policial
            'policiais' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/policiais[/:action][/:id][/:confirm]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'PolicialController',
                        'action' => 'index',
                    ),
                ),
            ),
            # segment para controller policial
            'ocorrencia' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/ocorrencia[/:action][/:id][/:confirm]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'OcorrenciaController',
                        'action' => 'index',
                    ),
                ),
            ),
            # segment para controller viaturas
            'viaturas' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/viatura[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ViaturaController',
                        'action' => 'index',
                    ),
                ),
            ),
            # segment para controller municipio
            'Municipio' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/municipio[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MunicipioController',
                        'action' => 'index',
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
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'router' => function ($sm) {
        return new \Application\View\Helper\Router($sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch());
    },
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
