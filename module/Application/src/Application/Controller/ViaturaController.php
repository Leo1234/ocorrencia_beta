<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ViaturaForm;
use Application\Model\Viatura;

use Application\Model\Area;
use Application\Model\AreaTable;

class ViaturaController extends AbstractActionController {

    private function getViaturaTable() {
        return $this->getServiceLocator()->get('ModelViatura');
    }
    
   private function getAreaTable() {
        return $this->getServiceLocator()->get('ModelArea');
    }
    
    
    public function indexAction() {
        //return new ViewModel(array('vtr' => $this->getViaturaTable()->fetchAll()));
        //$paginacao = $this->getViaturaTable()->fetchPaginator();
        //return new ViewModel(['vtr' => $paginacao]);
        // colocar parametros da url em um array
        
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_prefixo' => $this->params()->fromQuery('coluna_prefixo', 'prefixo'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getViaturaTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_prefixo']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
                 
        // retonar paginação mais os params de url para view
        return new ViewModel(['vtr' => $paginacao] + $paramsUrl  );
    }

// GET /contatos/novo
    public function novoAction() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new ViaturaForm($dbAdapter);
        return ['formViatura' => $form];
    }
    
    

// POST /contatos/adicionar
    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
           
            // instancia formulário
             $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new ViaturaForm($dbAdapter);
            // instancia model contato com regras de filtros e validações
            $modelViatura = new Viatura();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelViatura->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelViatura->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getViaturaTable()->save($modelViatura);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Viatura criada com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('viaturas');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formViatura', $form)
                                ->setTemplate('application/viatura/novo');
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
            $this->flashMessenger()->addMessage("Viatura não encotrada");
            // redirecionar para action index
            return $this->redirect()->toRoute('viaturas');
        }
        try {
            // variável com objeto viatura localizado em formato de array
            $viatura = (array) $this->getViaturaTable()->find($id);
            // variável com objeto viatura localizado para ser usado para setar o campo area do select.
            $viaturaObj =  $this->getViaturaTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('viaturas');
        }
        // objeto form viatura vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new ViaturaForm($dbAdapter);
        //configura o campo select com valor vindo da view index
         $form->get('id_area')->setAttributes(array('value'=>$viaturaObj->getArea()->getId_area(),'selected'=>true));
        // popula objeto form viatura com objeto model viatura
        $form->setData($viatura);
        // dados eviados para editar.phtml
        return ['formViatura' => $form];
    }

// POST /contatos/editar/id
    public function atualizarAction() {
         
        // obtém a requisição
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
  
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new ViaturaForm($dbAdapter);
           // $form = new ViaturaForm();
            // instancia model contato com regras de filtros e validações
            $modelViatura = new Viatura();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
              $form->setInputFilter($modelViatura->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());     
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelViatura->exchangeArray($form->getData());
               
                // 2 - atualizar dados do model para banco de dados
                
                $this->getViaturaTable()->update($modelViatura);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Viatura editada com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('viaturas', array("action" => "detalhes", "id" => $modelViatura->getId_vtr()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                   
                return (new ViewModel())
                                ->setVariable('formViatura', $form)
                                ->setTemplate('application/viatura/editar');
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
            $this->flashMessenger()->addMessage("Viatura não encotrada");

            // redirecionar para action index
            return $this->redirect()->toRoute('viaturas');
        }

        try {
            // aqui vai a lógica para pegar os dados refetchAllrente ao contato
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse contato encontrado
            // formulário com dados preenchidos
            $viatura = $this->getViaturaTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('viaturas');
        }

        // dados eviados para detalhes.phtml
        return ['viatura' => $viatura];
    }
    
    /*
       private function getAreaTable(){
        // localizar adapter do banco
        $adapter = $this->getServiceLocator()->get('AdapterDb');

        // return model PolicialTable
        return new ModelArea($adapter); // alias para GraduacaoTable
    }
*/
    
    
    /*
      public function editarAction() {
      // obtém a requisição
      $request = $this->getRequest();

      // verifica se a requisição é do tipo post
      if ($request->isPost()) {
      // obter e armazenar valores do post
      $postData = $request->getPost()->toArray();
      $formularioValido = true;

      // verifica se o formulário segue a validação proposta
      if ($formularioValido) {
      // aqui vai a lógica para editar os dados à tabela no banco
      // 1 - solicitar serviço para pegar o model responsável pela atualização
      // 2 - editar dados no banco pelo model
      // adicionar mensagem de sucesso
      $this->flashMessenger()->addSuccessMessage("Vítima editado com sucesso");

      // redirecionar para action detalhes
      return $this->redirect()->toRoute('vitimas', array("action" => "detalhes", "id" => $postData['id'],));
      } else {
      // adicionar mensagem de erro
      $this->flashMessenger()->addErrorMessage("Erro ao editar a vítima");

      // redirecionar para action editar
      return $this->redirect()->toRoute('vitimas', array('action' => 'editar', "id" => $postData['id'],));
      }
      }

      // filtra id passsado pela url
      $id = (int) $this->params()->fromRoute('id', 0);

      // se id = 0 ou não informado redirecione para contatos
      if (!$id) {
      // adicionar mensagem de erro
      $this->flashMessenger()->addMessage("Contato não encotrado");

      // redirecionar para action index
      return $this->redirect()->toRoute('vitimas');
      }

      // aqui vai a lógica para pegar os dados referente ao contato
      // 1 - solicitar serviço para pegar o model responsável pelo find
      // 2 - solicitar form com dados desse contato encontrado
      // formulário com dados preenchidos
      $form = array(
      'nome' => 'Igor Rocha',
      "telefone" => "(085) 8585-8585",
      "data_nascimento" => "(085) 8585-8585",
      );

      // dados eviados para editar.phtml
      return array('id' => $id, 'form' => $form);
      //return new ViewModel();
      }

    

    public function deletarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Vitima não encotrada");
        } else {
            // aqui vai a lógica para deletar o contato no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            // 2 - deleta contato
            // adicionar mensagem de sucesso
            $this->flashMessenger()->addSuccessMessage("Vitima de ID $id deletada com sucesso");
        }

        // redirecionar para action index
        return $this->redirect()->toRoute('vitimas');
    }
     *  */
     

    /*  private function getViaturaTable()
      {
      // obter instacia tableGateway configurada
      $tableGateway = $this->getServiceLocator()->get('ViaturaTableGateway');

      // return model ViaturaTable
      return new ModelViatura($tableGateway); // alias para ViaturaTable
      }
     */
}
