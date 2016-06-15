<?php

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=sgo;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'AdapterDb' => 'Zend\Db\Adapter\AdapterServiceFactory', // new Zend\Db\Adapter\AdapterServiceFactory
        ),
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'myapp',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
    ),
);
