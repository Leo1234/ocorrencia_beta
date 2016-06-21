<?php


namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\RelatoriosForm;

use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction() {
         $this->redirecionaUsuarioNaoLogado();
        $sessao = new Container('Auth');
        $usuario = $sessao->usuario;
        
        
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new RelatoriosForm($dbAdapter);

        return array('usuario' => $usuario, 'formRelarorio' => $form);      
    }
    
          protected function redirecionaUsuarioNaoLogado() {
        $sessao = new Container('Auth');
        if (!$sessao->admin) {
            return $this->redirect()->toRoute('auth', array('action' => 'sair'));
        }
    }

}
