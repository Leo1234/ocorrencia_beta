<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\AcusadoForm;
use Application\Model\Graduacao;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use \Application\Model\Endereco;
use Application\Model\EnderecoTable as ModelEndereco;
use \Application\Model\Bairro;
use Application\Model\BairroTable as ModelBairro;
use Application\Model\Acusado;
use Application\Model\AcusadoTable as ModelAcusado;

class AcusadoController extends AbstractActionController {

    private function getAcusadoTable() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelAcusado($dbAdapter);
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
        $paginacao = $this->getAcusadoTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_nome']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
       //var_dump($paginacao); die();
        // retonar paginação mais os params de url para view
        return new ViewModel(['acusado' => $paginacao] + $paramsUrl);
    }

// GET /acusados/novo
    public function novoAction(){
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new AcusadoForm($dbAdapter);
        return ['formAcusado' => $form];
    }

    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new AcusadoForm($dbAdapter);
            // instancia model acusado com regras de filtros e validações
            $modelAcusado = new Acusado();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity acusado
            $form->setInputFilter($modelAcusado->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                $bairro = $this->getBairroTable()->find($postData['id_bai']);
                 $modelEndereco = new Endereco(null, $postData['rua'], $postData['numero'], null, null, $bairro);
                $ultimo_idEnd = $this->getEnderecoTable()->saveSemLatLng($modelEndereco);
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelAcusado->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $modelAcusado->setEnd($ultimo_idEnd);
                $this->getAcusadoTable()->save($modelAcusado);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Acusado criado com sucesso!");
                // redirecionar para action index no controller acusados
                return $this->redirect()->toRoute('acusados');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formAcusado', $form)
                                ->setTemplate('application/acusado/novo');
            }
        }
    }

// GET /acusados/editar/id
    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para acusados
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Acusado não encotrado");
            // redirecionar para action index
            return $this->redirect()->toRoute('acusados');
        }
        try {
            // variável com objeto acusado localizado em formato de array
            $acusado = (array) $this->getAcusadoTable()->find($id);
        
            $acusadoObj = $this->getAcusadoTable()->find($id);
            
            $acusado['data_nasc'] = $this->getAcusadoTable()->toDateDMY($acusadoObj->getData_nasc());
            $acusado['rua'] = $acusadoObj->getEnd()->getRua();
            $acusado['numero'] = $acusadoObj->getEnd()->getNumero();
            $acusado['id_end'] = $acusadoObj->getEnd()->getId_end();
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('acusados');
        }
        // objeto form acusado vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new AcusadoForm($dbAdapter);
        //configura o campo select com valor vindo da view index

        $form->get('id_muniO')->setAttributes(array('value' => $acusadoObj->getEnd()->getId_bai()->getMunicipio()->getId_muni(), 'selected' => true));
        $form->get('id_bai')->setAttributes(array('value' => $acusadoObj->getEnd()->getId_bai()->getId_bai(), 'selected' => true));
        //$form->get('data_nasc')->setAttribute('value',"Leonildo");
        // popula objeto form viatura com objeto model viatura
        $form->setData($acusado);
        // dados eviados para editar.phtml
        return ['formAcusado' => $form];
    }

    public function atualizarAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();


        if ($request->isPost()) {

            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new AcusadoForm($dbAdapter);
            // $form = new ViaturaForm();
            // instancia model acusado com regras de filtros e validações
            $modelAcusado = new Acusado();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity acusado
            $form->setInputFilter($modelAcusado->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {

                $bairro = $this->getBairroTable()->find($postData['id_bai']);
                $modelEndereco = new Endereco($postData['id_end'], $postData['rua'], $postData['numero'],"0,0","0,0", $bairro);
                $this->getEnderecoTable()->update($modelEndereco);

                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelAcusado->acusado($form->getData());

                // 2 - atualizar dados do model para banco de dados

                $this->getAcusadoTable()->update($modelAcusado);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Acusado editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('acusados', array("action" => "detalhes", "id" => $modelAcusado->getId_acusado()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formAcusado', $form)
                                ->setTemplate('application/acusado/editar');
            }
        }
    }

    // DELETE /acusados/deletar/id

    public function deletarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para acusados
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Acusado não encotrado");
        } else {
            // aqui vai a lógica para deletar o acusado no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            // 2 - deleta acusado
            $this->getViaturaTable()->delete($id);

            // adicionar mensagem de sucesso
            $this->flashMessenger()->addSuccessMessage("Viatura de ID $id deletada com sucesso");
        }

        // redirecionar para action index
        return $this->redirect()->toRoute('viaturas');
    }

    // GET /acusados/detalhes/id
    public function detalhesAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para acusados
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Acusado não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('acusados');
        }

        try {
            // aqui vai a lógica para pegar os dados refetchAllrente ao acusado
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse acusado encontrado
            // formulário com dados preenchidos

            $acusado = $this->getAcusadoTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('acusados');
        }

        // dados eviados para detalhes.phtml
        return ['acusado' => $acusado];
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
