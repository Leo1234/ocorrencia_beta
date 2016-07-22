<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\VitimaForm;
use Application\Model\Graduacao;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use \Application\Model\Endereco;
use Application\Model\EnderecoTable as ModelEndereco;
use \Application\Model\Bairro;
use Application\Model\BairroTable as ModelBairro;
use Application\Model\Vitima;
use Application\Model\VitimaTable as ModelVitima;

class VitimaController extends AbstractActionController {

    private function getVitimaTable() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelVitima($dbAdapter);
    }

    public function indexAction(){

        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_nome' => $this->params()->fromQuery('coluna_nome', 'nome'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getVitimaTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_nome']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
       //var_dump($paginacao); die();
        // retonar paginação mais os params de url para view
        return new ViewModel(['vitima' => $paginacao] + $paramsUrl);
    }

// GET /vitimas/novo
    public function novoAction() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new VitimaForm($dbAdapter);
        return ['formVitima' => $form];
    }

    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new VitimaForm($dbAdapter);
            // instancia model vitima com regras de filtros e validações
            $modelVitima = new Vitima();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity vitima
            $form->setInputFilter($modelVitima->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                $bairro = $this->getBairroTable()->find($postData['id_bai']);
                $modelEndereco = new Endereco(null, $postData['rua'], $postData['numero'], $bairro);
                $ultimo_idEnd = $this->getEnderecoTable()->save($modelEndereco);
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelVitima->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $modelVitima->setEnd($ultimo_idEnd);
                $this->getVitimaTable()->save($modelVitima);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Vitima criada com sucesso!");
                // redirecionar para action index no controller vitimas
                return $this->redirect()->toRoute('vitimas');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formVitima', $form)
                                ->setTemplate('application/vitima/novo');
            }
        }
    }

// GET /vitimas/editar/id
    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para vitimas
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Vitima não encotrado");
            // redirecionar para action index
            return $this->redirect()->toRoute('vitimas');
        }
        try {
            // variável com objeto vitima localizado em formato de array
            $vitima = (array) $this->getVitimaTable()->find($id);
        
            $vitimaObj = $this->getVitimaTable()->find($id);
            
            $vitima['data_nasc'] = $this->getVitimaTable()->toDateDMY($vitimaObj->getData_nasc());
            $vitima['rua'] = $vitimaObj->getEnd()->getRua();
            $vitima['numero'] = $vitimaObj->getEnd()->getNumero();
            $vitima['id_end'] = $vitimaObj->getEnd()->getId_end();
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('vitimas');
        }
        // objeto form vitima vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new VitimaForm($dbAdapter);
        //configura o campo select com valor vindo da view index

        $form->get('id_muniO')->setAttributes(array('value' => $vitimaObj->getEnd()->getId_bai()->getMunicipio()->getId_muni(), 'selected' => true));
        $form->get('id_bai')->setAttributes(array('value' => $vitimaObj->getEnd()->getId_bai()->getId_bai(), 'selected' => true));
        //$form->get('data_nasc')->setAttribute('value',"Leonildo");
        // popula objeto form viatura com objeto model viatura
        $form->setData($vitima);
        // dados eviados para editar.phtml
        return ['formVitima' => $form];
    }

    public function atualizarAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();


        if ($request->isPost()) {

            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new VitimaForm($dbAdapter);
            // $form = new ViaturaForm();
            // instancia model vitima com regras de filtros e validações
            $modelVitima = new Vitima();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity vitima
            $form->setInputFilter($modelVitima->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {

                $bairro = $this->getBairroTable()->find($postData['id_bai']);
                $modelEndereco = new Endereco($postData['id_end'], $postData['rua'], $postData['numero'],"0,0","0,0", $bairro);
                $this->getEnderecoTable()->update($modelEndereco);

                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelVitima->vitima($form->getData());

                // 2 - atualizar dados do model para banco de dados

                $this->getVitimaTable()->update($modelVitima);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Vitima editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('vitimas', array("action" => "detalhes", "id" => $modelVitima->getId_vitima()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formVitima', $form)
                                ->setTemplate('application/vitima/editar');
            }
        }
    }

    // DELETE /vitimas/deletar/id

    public function deletarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para vitimas
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Vitima não encotrado");
        } else {
            // aqui vai a lógica para deletar o vitima no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            // 2 - deleta vitima
            $this->getViaturaTable()->delete($id);

            // adicionar mensagem de sucesso
            $this->flashMessenger()->addSuccessMessage("Viatura de ID $id deletada com sucesso");
        }

        // redirecionar para action index
        return $this->redirect()->toRoute('viaturas');
    }

    // GET /vitimas/detalhes/id
    public function detalhesAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para vitimas
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Vitima não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('vitimas');
        }

        try {
            // aqui vai a lógica para pegar os dados refetchAllrente ao vitima
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse vitima encontrado
            // formulário com dados preenchidos

            $vitima = $this->getVitimaTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('vitimas');
        }

        // dados eviados para detalhes.phtml
        return ['vitima' => $vitima];
    }

    private function getEnderecoTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelEndereco($adapter);
    }

    private function getBairroTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelBairro($adapter);
    }

}
