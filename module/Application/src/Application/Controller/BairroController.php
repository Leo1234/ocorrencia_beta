<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Model\Area;
use Application\Form\AreaForm;
use Application\Model\AreaTable as ModelArea;

use Application\Model\Bairro;
use Application\Form\BairroForm;
use Application\Model\BairroTable as BairroArea;

use Application\Model\Viatura;
use Application\Form\ViaturaForm;
use Application\Model\ViaturaTable as ModelViatura;

use Application\Model\Municipio;
use Application\Form\MunicipioForm;
use Application\Model\MunicipioTable as ModelMunicipio;

use Application\Model\BairroTable as ModelBairro;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

;

class BairroController extends AbstractActionController {
    
    
   private function getBairroTable() {
        $dbAdapter =  $this->getServiceLocator()->get('AdapterDb');
        return new ModelBairro($dbAdapter);
    }
    

// GET /application/bairro/search?query=[id_nome]
public function searchAction()
{
    //$id_muni = $this->params()->fromQuery('id_muni', null);
    $id_muni = $_POST['id_muni'];
   
    if (isset($id_muni)) {
        $result = $this->getBairroTable()->search($id_muni);   
    } else  {
        $result = [];  
    }
    
    
    return new \Zend\View\Model\JsonModel($result);
}
    // GET /application/bairro/search?query=[id_nome]
public function searchBairroAction()
{
    //$id_muni = $this->params()->fromQuery('id_muni', null);
    $id_muni = $_POST['id_muni'];
   
    if (isset($id_muni)) {
        $result = $this->getBairroTable()->searchBairro($id_muni);   
    } else  {
        $result = [];  
    }
    
    
    return new \Zend\View\Model\JsonModel($result);
}
        // GET /application/bairro/search?query=[id_nome]
public function searchBairroViaturaAction()
{
    //$id_muni = $this->params()->fromQuery('id_muni', null);
    $id_bai = $_POST['id_bai'];
   
    if (isset($id_bai)) {
        $result = $this->getBairroTable()->searchBairroViatura($id_bai);   
    } else  {
        $result = [];  
    }
    
    
    return new \Zend\View\Model\JsonModel($result);
}
   
    
    public function indexAction() {
      
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_bairro' => $this->params()->fromQuery('coluna_bairro', 'bairro'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getBairroTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_bairro']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
                 
        // retonar paginação mais os params de url para view
        return new ViewModel(['bairro' => $paginacao] + $paramsUrl  );
    }

    
    
// GET /application/bairro/novo
     public function novoAction() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new BairroForm($dbAdapter);
        return ['formBairro' => $form];
    }
    
// GET /application/bairro/adicionar
    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
           
            // instancia formulário
             $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new BairroForm($dbAdapter);
            // instancia model bairro com regras de filtros e validações
            $modelBairro = new Bairro();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity bairro
            $form->setInputFilter($modelBairro->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelBairro->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getBairroTable()->save($modelBairro);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Bairro criado com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('bairro');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formBairro', $form)
                                ->setTemplate('application/bairro/novo');
            }
        }
    }

    
    
    
// GET /application/bairro/editar/id
    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para bairro
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Bairro não encotrado");
            // redirecionar para action index
            return $this->redirect()->toRoute('bairro');
        }
        try {
            // variável com objeto bairro localizado em formato de array
            $bairro= (array) $this->getBairroTable()->find($id);
            // variável com objeto bairro localizado para ser usado para setar o campo area do select.
            $bairroObj =  $this->getBairroTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('bairro');
        }
        // objeto form bairro vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new BairroForm($dbAdapter);
        //configura o campo select com valor vindo da view index
         $form->get('id_muni')->setAttributes(array('value'=>$bairroObj->getMunicipio()->getId_muni(),'selected'=>true));
         $form->get('id_area')->setAttributes(array('value'=>$bairroObj->getArea()->getId_area(),'selected'=>true));
        // popula objeto form bairro com objeto model viatura
        $form->setData($bairro);
        // dados eviados para editar.phtml
        return ['formBairro' => $form];
    }

// POST /application/bairro/editar/id
    public function atualizarAction() { 
        // obtém a requisição
        $request = $this->getRequest();
        if ($request->isPost()) {  
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new BairroForm($dbAdapter);
           
            // instancia model contato com regras de filtros e validações
            $modelBairro = new Bairro();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity bairro
              $form->setInputFilter($modelBairro->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());     
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelBairro->exchangeArray($form->getData());
               
                // 2 - atualizar dados do model para banco de dados
                
                $this->getBairroTable()->update($modelBairro);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Bairro editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('bairro', array("action" => "detalhes", "id" => $modelBairro->getId_bai()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                   
                return (new ViewModel())
                                ->setVariable('formBairro', $form)
                                ->setTemplate('application/bairro/editar');
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

        // se id = 0 ou não informado redirecione para bairro
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Bairro não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('bairro');
        }

        try {
         
            $bairro = $this->getBairroTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('bairro');
        }

        // dados eviados para detalhes.phtml
        return ['bairro' => $bairro];
    }
    
    
}
