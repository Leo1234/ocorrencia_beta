<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Application\Model\Viatura,
    Application\Model\ViaturaTable;
 
// import Zend\Db
use Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;


class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Register View Helper
     */
    public function getViewHelperConfig() {
        return array(
            # registrar View Helper com injecao de dependecia
            'factories' => array(
                'menuAtivo' => function($sm) {
                    return new View\Helper\MenuAtivo($sm->getServiceLocator()->
                            get('Request'));
                 },
                         
                'message' => function($sm) {
                    return new View\Helper\Message($sm->getServiceLocator()->
                            get('ControllerPluginManager')->get('flashmessenger'));
                },
                'util' => function($sm) {
                    return new View\Helper\Util();
                },
            )
        );
    }
    
     public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ViaturaTableGateway' => function ($sm) {
                    // obter adapter db atraves do service manager
                    $adapter = $sm->get('AdapterDb');

                    // configurar ResultSet com nosso model Contato
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Viatura());

                    // return TableGateway configurado para nosso model Viatura
                    return new TableGateway('vtr', $adapter, null, $resultSetPrototype);
                },
                'ModelViatura' => function ($sm){
                    // return instacia Model ViaturaTable
                    return new ViaturaTable($sm->get('ViaturaTableGateway'));
                }
            ),
        );
    }
    
}
