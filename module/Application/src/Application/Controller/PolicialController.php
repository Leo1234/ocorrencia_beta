<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PolicialForm;
use Application\Model\Graduacao;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

use Application\Model\Policial;
use Application\Model\PolicialTable as ModelPolicial;

class PolicialController extends AbstractActionController {
    
    
   private function getPolicialTable() {
        $dbAdapter =  $this->getServiceLocator()->get('AdapterDb');
        return new ModelPolicial($dbAdapter);
    }
    
    public function indexAction() {
      
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_nome' => $this->params()->fromQuery('coluna_nome', 'nome'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getPolicialTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_nome']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
                 
        // retonar paginação mais os params de url para view
        return new ViewModel(['policial' => $paginacao] + $paramsUrl  );
    }

    
    
// GET /policials/novo
    public function novoAction() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new PolicialForm($dbAdapter);
        return ['formPolicial' => $form];
    }
    
    

// POST /policials/adicionar
    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
          
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new PolicialForm($dbAdapter);
            // instancia model policial com regras de filtros e validações
            $modelPolicial = new Policial();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity policial
            $form->setInputFilter($modelPolicial->getInputFilter());
         
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
             
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
           
            if ($form->isValid()) {
                
                 
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelPolicial->exchangeArray($form->getData());
                
              
                // 2 - persistir dados do model para banco de dados
                $this->getPolicialTable()->save($modelPolicial);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Policial criado com sucesso!");

                // redirecionar para action index no controller policials
                return $this->redirect()->toRoute('policiais');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formPolicial', $form)
                                ->setTemplate('application/policial/novo');
            }
        }
    }

    
    
// GET /policials/editar/id
    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para policials
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Policial não encotrado");
            // redirecionar para action index
            return $this->redirect()->toRoute('policiais');
        }
        try {
            // variável com objeto viatura localizado em formato de array
            $policial= (array) $this->getPolicialTable()->find($id);
            // variável com objeto viatura localizado para ser usado para setar o campo policial do select.
            $policialObj =  $this->getPolicialTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('policiais');
        }
        // objeto form viatura vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new PolicialForm($dbAdapter);
        //configura o campo select com valor vindo da view index
         $form->get('id_grad')->setAttributes(array('value'=>$policialObj->getGraduacao()->getId_grad(),'selected'=>true));
        // popula objeto form viatura com objeto model viatura
        $form->setData($policial);
        // dados eviados para editar.phtml
        return ['formPolicial' => $form];
    }

// POST /policiais/editar/id
    public function atualizarAction() {
         
        // obtém a requisição
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
  
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new PolicialForm($dbAdapter);
           // $form = new ViaturaForm();
            // instancia model policial com regras de filtros e validações
            $modelPolicial = new Policial();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity policial
              $form->setInputFilter($modelPolicial->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());     
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelPolicial->exchangeArray($form->getData());
               
                // 2 - atualizar dados do model para banco de dados
                
                $this->getPolicialTable()->update($modelPolicial);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Policial editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('policiais', array("action" => "detalhes", "id" => $modelPolicial->getId_policial()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                   
                return (new ViewModel())
                                ->setVariable('formPolicial', $form)
                                ->setTemplate('application/policiais/editar');
            }
        }
      
    }
    
    // DELETE /policials/deletar/id
public function deletarAction()
{
    // filtra id passsado pela url
    $id = (int) $this->params()->fromRoute('id', 0);
 
    // se id = 0 ou não informado redirecione para policials
    if (!$id) {
        // adicionar mensagem de erro
        $this->flashMessenger()->addMessage("Policial não encotrado");
    } else {
        // aqui vai a lógica para deletar o policial no banco
        // 1 - solicitar serviço para pegar o model responsável pelo delete
        // 2 - deleta policial
        $this->getViaturaTable()->delete($id);
        
        // adicionar mensagem de sucesso
        $this->flashMessenger()->addSuccessMessage("Viatura de ID $id deletada com sucesso");
    }
 
    // redirecionar para action index
    return $this->redirect()->toRoute('viaturas');
}



      // GET /policials/detalhes/id
    public function detalhesAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para policials
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Policial não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('policiais');
        }

        try {
            // aqui vai a lógica para pegar os dados refetchAllrente ao policial
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse policial encontrado
            // formulário com dados preenchidos
            $policial = $this->getPolicialTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('policiais');
        }

        // dados eviados para detalhes.phtml
        return ['policial' => $policial];
    }
    
    
}
