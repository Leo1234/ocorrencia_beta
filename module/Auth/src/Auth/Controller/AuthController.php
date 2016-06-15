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
        $senha = $request->getPost('senha');

        $loginForm->setData($request->getPost());

        $zendDb = $this->getServiceLocator()->get('AdapterDb');
       

        $authAdapter = new DbTable(
                $zendDb,
                'usuario',
                'login', 
                'senha'
        );       
        
        //$authAdapter->setCredentialTreatment('md5(?) AND senha != "compromised"');
        $authAdapter->setIdentity($login);
        $authAdapter->setCredential($senha);

        $authService = new AuthenticationService();

        $result = $authService->authenticate($authAdapter);

        if ($result->isValid()) {
            
             // Se validou damos um get nos dados autenticados usando o $result->getIdentity()
            $identity = $result->getIdentity();
            
            // Login para autenticação
            $auth = new AuthenticationService();
            $auth->setStorage(new SessionStorage($identity));
            $auth->authenticate($authAdapter);
   

            /* Imprimindo os dados na tela para confirmar os dados autenticados
             * pronto, se aparecer os dados isso quer dizer que o usuario está autenticado no sistema
             */
            
           // exit(var_dump($identity));
            $this->redirect()->toRoute('application', array('action'=>'index'));

        } else {
            /* Caso falhe a autenticação, será gerado o log abaixo que será impresso&nbsp;
             * na tela do computador para você sabe do problema ocorrido.
             * os erros listados abaixo são os erros mais comuns que podem ocorrer.
             */
            switch ($result->getCode()) {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                     $mensagem = 'Login inválido';
                    $this->redirect()->toRoute('auth', array('menssagem' => $mensagem ));
                    break;
                case Result::FAILURE_CREDENTIAL_INVALID:
                     $mensagem = 'Login e senha não correspondem';
                    break;
                default: $mensagem = 'Houve algum erro com a conexão do sistema. Favor, entrar em contato.';
                            break;
                  
            }
        } 
        
    
    }
    
    public function sairAction() {
        $sessao = new Container('Auth');
        $sessao->getManager()->getStorage()->clear();  
        return $this->redirect()->toRoute('/auth/index');
    }

}
