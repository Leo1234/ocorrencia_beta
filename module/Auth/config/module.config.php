<?php

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'invokables' => array(
            'AuthController' => 'Auth\Controller\AuthController'
        ),
    ),

    # definir e gerenciar rotas
    'router' => array(
        'routes' => array(
            'auth' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/login[/:menssagem]',
                    'constraints' => array(
                        'menssagem' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'AuthController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    # definir e gerenciar servicos
    'service_manager' => array(
        'factories' => array(
            #'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    # definir e gerenciar layouts, erros, exceptions, doctype base
    'view_manager' => array(
        'display_not_found_reason'  => true,
        'display_exceptions'        => true,
        'doctype'                   => 'HTML5',
        'not_found_template'        => 'error/404',
        'exception_template'        => 'error/index',
        'template_map'              => array(
            'layout/auth'         => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/index'           => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);