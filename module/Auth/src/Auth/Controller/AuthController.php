<?php

namespace Auth\Controller;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;

class AuthController extends AbstractActionController {

    public function indexAction() {

        $form = new LoginForm();
        return ['formLogin' => $form];
    }

   public function autenticacaoAction() {
         
           // Login form
        $loginForm = new LoginForm();
        $request = $this->getRequest();
        
        $login = $request->getPost('login');
        $senha = (int)$request->getPost('senha');   
        

        $loginForm->setData($request->getPost());

        $zendDb = $this->getServiceLocator()->get('AdapterDb');
       

        $authAdapter = new DbTable(
                $zendDb,
                'usuario',
                'login', 
                'senha',
                'MD5(?)'
        );       

        $authAdapter->setIdentity($login);
        $authAdapter->setCredential(md5($senha));
        //$authAdapter->setCredentialTreatment("MD5(?)");

        //var_dump(md5($senha)); die();


        $authService = new AuthenticationService();

        $result = $authService->authenticate($authAdapter);

        if ($result->isValid()) {
            
             // Se validou damos um get nos dados autenticados usando o $result->getIdentity()
            $identity = $result->getIdentity();
            
            // Login para autenticação
            $auth = new AuthenticationService();
            $auth->setStorage(new SessionStorage($identity));
            $auth->authenticate($authAdapter);

            $this->redirect()->toRoute('application', array('action'=>'index'));

        } else {
            /* Caso falhe a autenticação, será gerado o log abaixo que será impresso&nbsp;
             */
            switch ($result->getCode()) {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                     $mensagem = 'Login inválido';
                    $this->redirect()->toRoute('auth', array('menssagem' => $mensagem ));
                    break;
                case Result::FAILURE_CREDENTIAL_INVALID:
                     $mensagem = md5($senha);
                    break;
                default: $mensagem = 'Houve algum erro com a conexão do sistema. Favor, entrar em contato.';
                            break;
                  
            }
        } 
        
    
    }
    
    public function sairAction(){
        $sessao = new Container('Auth');
        $sessao->getManager()->getStorage()->clear();  
        return $this->redirect()->toRoute('/auth/index');
    }
}
