<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\AreaForm;
use Application\Model\Viatura;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

use Application\Model\Area;
use Application\Model\AreaTable as ModelArea;

class AreaController extends AbstractActionController {
    
    
   private function getAreaTable() {
        $dbAdapter =  $this->getServiceLocator()->get('AdapterDb');
        return new ModelArea($dbAdapter);
    }
    
    public function indexAction() {
      
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_descricao' => $this->params()->fromQuery('coluna_descricao', 'descricao'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getAreaTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_descricao']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
                 
        // retonar paginação mais os params de url para view
        return new ViewModel(['area' => $paginacao] + $paramsUrl  );
    }

    
    
// GET /contatos/novo
    public function novoAction() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new AreaForm($dbAdapter);
        return ['formArea' => $form];
    }
    
    

// POST /contatos/adicionar
    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
           
            // instancia formulário
             $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new AreaForm($dbAdapter);
            // instancia model contato com regras de filtros e validações
            $modelArea = new Area();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelArea->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelArea->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getAreaTable()->save($modelArea);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Área criada com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('area');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formArea', $form)
                                ->setTemplate('application/area/novo');
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
            $this->flashMessenger()->addMessage("Área não encotrada");
            // redirecionar para action index
            return $this->redirect()->toRoute('area');
        }
        try {
            // variável com objeto viatura localizado em formato de array
            $area= (array) $this->getAreaTable()->find($id);
            // variável com objeto viatura localizado para ser usado para setar o campo area do select.
            $areaObj =  $this->getAreaTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('area');
        }
        // objeto form viatura vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new AreaForm($dbAdapter);
        //configura o campo select com valor vindo da view index
         $form->get('id_muni')->setAttributes(array('value'=>$areaObj->getMunicipio()->getId_muni(),'selected'=>true));
        // popula objeto form viatura com objeto model viatura
        $form->setData($area);
        // dados eviados para editar.phtml
        return ['formArea' => $form];
    }

// POST /contatos/editar/id
    public function atualizarAction() {
         
        // obtém a requisição
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
  
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new AreaForm($dbAdapter);
           // $form = new ViaturaForm();
            // instancia model contato com regras de filtros e validações
            $modelArea = new Area();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
              $form->setInputFilter($modelArea->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());     
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelArea->exchangeArray($form->getData());
               
                // 2 - atualizar dados do model para banco de dados
                
                $this->getAreaTable()->update($modelArea);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Área editada com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('area', array("action" => "detalhes", "id" => $modelArea->getId_area()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                   
                return (new ViewModel())
                                ->setVariable('formArea', $form)
                                ->setTemplate('application/area/editar');
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
            $this->flashMessenger()->addMessage("Área não encotrada");

            // redirecionar para action index
            return $this->redirect()->toRoute('area');
        }

        try {
            // aqui vai a lógica para pegar os dados refetchAllrente ao contato
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse contato encontrado
            // formulário com dados preenchidos
            $area = $this->getAreaTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('area');
        }

        // dados eviados para detalhes.phtml
        return ['area' => $area];
    }
    
    
}
