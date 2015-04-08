<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\GraduacaoForm;


use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

use Application\Model\Graduacao;
use Application\Model\GraduacaoTable as ModelGraduacao;

class GraduacaoController extends AbstractActionController {
    
    
   private function getGraduacaoTable() {
        $dbAdapter =  $this->getServiceLocator()->get('AdapterDb');
        return new ModelGraduacao($dbAdapter);
    }
    
    public function indexAction() {
      
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_sigla' => $this->params()->fromQuery('coluna_sigla', 'sigla'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getGraduacaoTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_sigla']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
                 
        // retonar paginação mais os params de url para view
        return new ViewModel(['graduacao' => $paginacao] + $paramsUrl  );
    }

    
    
// GET /contatos/novo
    public function novoAction() {
        $form = new GraduacaoForm();
        return ['formGraduacao' => $form];
    }
    
// POST /contatos/adicionar
    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $form = new GraduacaoForm();
            // instancia model contato com regras de filtros e validações
            $modelGraduacao = new Graduacao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelGraduacao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelGraduacao->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getGraduacaoTable()->save($modelGraduacao);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Graduação criada com sucesso!");
                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('graduacao');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formGraduacao', $form)
                                ->setTemplate('application/graduacao/novo');
            }
        }
    }

// GET /contatos/editar/id
    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Graduação não encotrada");
            // redirecionar para action index
            return $this->redirect()->toRoute('graduacao');
        }
        try {
            // variável com objeto viatura localizado em formato de array
            $graduacao = (array) $this->getGraduacaoTable()->find($id);
         
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('graduacao');
        }
        $form = new GraduacaoForm();
        $form->setData($graduacao);
        return ['formGraduacao' => $form];
    }

// POST /contatos/editar/id
    public function atualizarAction() {
         
        // obtém a requisição
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            // instancia formulário
           
            $form = new GraduacaoForm();
    
            // instancia model graduacção com regras de filtros e validações
            $modelGraduacao = new Graduacao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity graduação
              $form->setInputFilter($modelGraduacao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());     
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelGraduacao ->exchangeArray($form->getData());
                // 2 - atualizar dados do model para banco de dados
                $this->getGraduacaoTable()->update($modelGraduacao );
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Graduação editada com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('graduacao', array("action" => "detalhes", "id" => $modelGraduacao->getId_grad()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                   
                return (new ViewModel())
                                ->setVariable('formGraduacao', $form)
                                ->setTemplate('application/graduacao/editar');
            }
        }
      
    }
    
    // DELETE /contatos/deletar/id
public function deletarAction()
{
    // filtra id passsado pela url
    $id = (int) $this->params()->fromRoute('id', 0);
 
    // se id = 0 ou não informado redirecione para contatos
    if (!$id) {
        // adicionar mensagem de erro
        $this->flashMessenger()->addMessage("Viatura não encotrada");
    } else {
        // aqui vai a lógica para deletar o contato no banco
        // 1 - solicitar serviço para pegar o model responsável pelo delete
        // 2 - deleta contato
        $this->getViaturaTable()->delete($id);
        
        // adicionar mensagem de sucesso
        $this->flashMessenger()->addSuccessMessage("Viatura de ID $id deletada com sucesso");
    }
 
    // redirecionar para action index
    return $this->redirect()->toRoute('viaturas');
}



      // GET /contatos/detalhes/id
    public function detalhesAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Graduação não encotrada");

            // redirecionar para action index
            return $this->redirect()->toRoute('graduacao');
        }

        try {
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse contato encontrado
            // formulário com dados preenchidos
            $graduacao= $this->getGraduacaoTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('graduacao');
        }

        // dados eviados para detalhes.phtml
        return ['graduacao' => $graduacao];
    }
    
    
}
