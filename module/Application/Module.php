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
use Zend\ModuleManager\ModuleManager;
use Zend\Session\SessionManager;
use Zend\Session\Container;

use Application\Model\Viatura,
    Application\Model\ViaturaTable;
use Application\Model\Area,
    Application\Model\AreaTable;
// import Zend\Db
use Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;
use Application\View\Helper\ViaturaFilter;

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
                'AuthService' => function ($sm) {
            $adapter = $sm->get('AdapterDb');
            $dbAuthAdapter = new DbAuthAdapter($adapter, 'usuario', 'login', 'senha');

            $auth = new AuthenticationService();
            $auth->setAdapter($dbAuthAdapter);

            return $auth;
        }
            )
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'AreaTableGateway' => function ($sm) {
            // obter adapter db atraves do service manager
            $adapter = $sm->get('AdapterDb');

            // configurar ResultSet com nosso model Contato
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Area());

            // return TableGateway configurado para nosso model Viatura
            return new TableGateway('area', $adapter, null, $resultSetPrototype);
        },
                'ModelArea' => function ($sm) {
            // return instacia Model ViaturaTable
            return new AreaTable($sm->get('AreaTableGateway'));
        },
                'Zend\Session\SessionManager' => function ($sm) {
            $config = $sm->get('config');
            if (isset($config['session'])) {
                $session = $config['session'];

                $sessionConfig = null;
                if (isset($session['config'])) {
                    $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                    $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                    $sessionConfig = new $class();
                    $sessionConfig->setOptions($options);
                }

                $sessionStorage = null;
                if (isset($session['storage'])) {
                    $class = $session['storage'];
                    $sessionStorage = new $class();
                }

                $sessionSaveHandler = null;
                if (isset($session['save_handler'])) {
                    // class should be fetched from service manager since it will require constructor arguments
                    $sessionSaveHandler = $sm->get($session['save_handler']);
                }

                $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
            } else {
                $sessionManager = new SessionManager();
            }
            Container::setDefaultManager($sessionManager);
            return $sessionManager;
        },
            ),
        );
    }

    public function bootstrapSession($e) {
        $session = $e->getApplication()
                ->getServiceManager()
                ->get('Zend\Session\SessionManager');
        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init)) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $request = $serviceManager->get('Request');

            $session->regenerateId(true);
            $container->init = 1;
            $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
            $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

            $config = $serviceManager->get('Config');
            if (!isset($config['session'])) {
                return;
            }

            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators'])) {
                $chain = $session->getValidatorChain();

                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }

                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }
    


    public function verificaAutenticacao($e) {
        // vamos descobrir onde estamos?
        $controller = $e->getTarget();
        $rota = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

        if ($rota != 'login' && $rota != 'login/default') {
            $sessao = new Container('Auth');
            if (!$sessao->admin) {
                return $controller->redirect()->toRoute('auth');
            }
        }
    }

}
