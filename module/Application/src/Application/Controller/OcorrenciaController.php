<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Ocorrencia;
use Application\Model\OcorrenciaTable as ModelOcorrencia;
use Application\Model\PolicialTable as ModelPolicial;
use Application\Model\ViaturaTable as ModelViatura;
use Application\Model\AreaTable as ModelArea;
use Application\Model\VitimaTable as ModelVitima;
use \Application\Model\Endereco;
use Application\Model\EnderecoTable as ModelEndereco;
use \Application\Model\Bairro;
use Application\Model\BairroTable as ModelBairro;
use Application\Form\OcorrenciaForm;
use Application\Form\HomicidioForm;
use Application\Model\Homicidio;
use Application\Model\HomicidioTable as ModelHomicidio;
use Application\Model\OcorrenciaCrime;
use Application\Model\OcorrenciaCrimeTable as ModelOcorrenciaCrime;

class OcorrenciaController extends AbstractActionController {

    //função que retorna uma instancia da classe OcorrenciaTable 
    private function getOcorrenciaTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelOcorrencia($adapter);
    }

    public function indexAction() {

        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_datai' => $this->params()->fromQuery('coluna_datai', 'datai'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getOcorrenciaTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_datai']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );

        // retonar paginação mais os params de url para view
        return new ViewModel(['ocorrencia' => $paginacao] + $paramsUrl);
    }

    public function novoAction() {
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new OcorrenciaForm($dbAdapter);
        return ['formOcorrencia' => $form];
    }

    public function novohomicidioAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));

        return new ViewModel(
                array(
            'id' => $id,
            'form' => $formHomicidio,
                )
        );
    }

    public function editarhomicidioAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $formHomicidio = new HomicidioForm();

        $Modelho = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);

        $formHomicidio->setData($Modelho);

        return new ViewModel(
                array(
            'form' => $formHomicidio,
            'id' => $id,
                )
        );
    }

    public function adicionarhomicidioAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $form = new HomicidioForm();
            // instancia model contato com regras de filtros e validações
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelHomicidio->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados do homicídio adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('form', $form)
                                ->setTemplate('application/ocorrencia/homicidio');
            }
        }
    }

    public function adicionarAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();

        if ($request->isPost()) {
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new OcorrenciaForm($dbAdapter);
            // instancia model Ocorrência com regras de filtros e validações
            $modelOcorrencia = new Ocorrencia();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity ocorrência
            //$form->setInputFilter($modelOcorrencia->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                $bairro = $this->getBairroTable()->find($postData['id_bai']);
                $modelEndereco = new Endereco(null, $postData['rua'], $postData['numero'], $bairro);
                $ultimo_idEnd = $this->getEnderecoTable()->save($modelEndereco);

                $modelOcorrencia->exchangeArray($form->getData());
                $modelOcorrencia->setEnd($ultimo_idEnd);

                $policiais = $postData['id_composicao'];
                $crimes = $postData['id_crime'];
                $procedimentos = $postData['procedimento'];

                print_r(crimes);

                $ultimo_id = $this->getOcorrenciaTable()->save($modelOcorrencia);

                if (count($policiais)) {
                    foreach ($policiais as $idp) {
                        $this->getOcorrenciaTable()->addPolicialOcorrencia($ultimo_id, $idp);
                    }
                }
                if (count($crimes)) {
                    foreach ($crimes as $idc) {
                        $this->getOcorrenciaTable()->addCrimeOcorrencia($ultimo_id, $idc);
                    }
                }

                if (count($procedimentos)) {
                    foreach ($procedimentos as $idpro) {
                        $this->getOcorrenciaTable()->addProcedimentoOcorrencia($ultimo_id, $idpro);
                    }
                }

                $this->flashMessenger()->addSuccessMessage("Ocorrência cadastrada com sucesso");
                // redirecionar para action index no controller 

                if (count($crimes)) {
                    foreach ($crimes as $cri) {
                        if ($cri == 1) {
                            return $this->redirect()->toRoute('ocorrencia', array('action' => 'homicidio', 'id' => $ultimo_id));
                            break;
                        }
                    }
                }

                return $this->redirect()->toRoute('ocorrencia', array('action' => 'index', 'id' => $ultimo_id));
                //return $this->redirect()->toRoute('ocorrencia');
            } else {
                // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formOcorrencia', $form)
                                ->setTemplate('application/ocorrencia/novo');
            }
        }
    }

    public function atualizarHomicidioAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário

            $form = new HomicidioForm();
            // instancia model municipio com regras de filtros e validações
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $form->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelHomicidio->exchangeArray($form->getData());

                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getHomicidioTable()->update($modelHomicidio, $id);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados do homicídio editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formHomicidio', $form)
                                ->setTemplate('application/ocorrencia/xxx');
            }
        }
    }

    public function atualizarAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();

        if ($request->isPost()) {
            // instancia formulário
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new OcorrenciaForm($dbAdapter);
            // $form = new ViaturaForm();
            // instancia model ocorrencia com regras de filtros e validações
            $modelOcorrencia = new Ocorrencia();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity ocorrencia
            // $form->setInputFilter($modelOcorrencia->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {

                $modelOcorrencia->ocorrencia($form->getData());
                $bairro = $this->getBairroTable()->find($postData['id_bai']);
                $modelEndereco = new Endereco($postData['id_end'], $postData['rua'], $postData['numero'], $bairro);

                $this->getEnderecoTable()->update($modelEndereco);

                $policiais = $postData['id_composicao'];
                $crimes = $postData['id_crime'];
                $procedimentos = $postData['procedimento'];

                // 1 - popular model com valores do formulário
                // 2 - atualizar dados do model para banco de dados
                $this->getOcorrenciaTable()->update($modelOcorrencia);

                if (count($policiais)) {
                    $this->getOcorrenciaTable()->delPoliciaisOcorrencia($modelOcorrencia->getId_oco());
                    foreach ($policiais as $idp) {
                        $this->getOcorrenciaTable()->addPolicialOcorrencia($modelOcorrencia->getId_oco(), $idp);
                    }
                }
                if (count($procedimentos)) {
                    $this->getOcorrenciaTable()->delProcedimentosOcorrencia($modelOcorrencia->getId_oco());
                    foreach ($procedimentos as $idpro) {
                        $this->getOcorrenciaTable()->addProcedimentoOcorrencia($modelOcorrencia->getId_oco(), $idpro);
                    }
                }
                ///////////////////////////editar dados extras da ocorrencia///////////////////////// 

                $isHomicidio = $this->getHomicidioTable()->isHomicidio($modelOcorrencia->getId_oco());
                
                
                ///////////////////////crimes, mas sem homicidios//////////////////
                if (!$this->isPostHomicidio($crimes)) {
                    if ($isHomicidio){
                        $this->getOcorrenciaTable()->delHomicidioOcorrencia($modelOcorrencia->getId_oco());
                        $this->getOcorrenciaTable()->delCrimesOcorrencia($modelOcorrencia->getId_oco());
                        foreach ($crimes as $cri) {
                            $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                        }
                    }

                    $this->getOcorrenciaTable()->delCrimesOcorrencia($modelOcorrencia->getId_oco());
                    foreach ($crimes as $cri) {
                        $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                    }
                } else if ($this->isPostHomicidio($crimes) && $isHomicidio) {
                    $Modelho = $this->getHomicidioTable()->findHomicidioOcorrencia($modelOcorrencia->getId_oco());
                    $this->getOcorrenciaTable()->delHomicidioOcorrencia($modelOcorrencia->getId_oco());
                    $this->getOcorrenciaTable()->delCrimesOcorrencia($modelOcorrencia->getId_oco());
                    foreach ($crimes as $cri) {
                        $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                    }
                    foreach ($crimes as $cri) {
                        if ($cri == 1) {
                            $this->getHomicidioTable()->addHomicidio($Modelho, $modelOcorrencia->getId_oco());
                            break;
                        }
                    }
                    if ($isHomicidio) {
                        $x = $modelOcorrencia->getId_oco();
                        return $this->redirect()->toRoute('ocorrencia', array('action' => 'editarhomicidio', 'id' => $x));
                    }
                } else {
                    $this->getOcorrenciaTable()->delCrimesOcorrencia($modelOcorrencia->getId_oco());
                    foreach ($crimes as $cri) {
                        $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                    }
                    $x = $modelOcorrencia->getId_oco();
                    return $this->redirect()->toRoute('ocorrencia', array('action' => 'novohomicidio', 'id' => $x));
                }


                /*
                if (count($crimes)) {
                    foreach ($crimes as $cri) {
                        if ($cri == 1 && $isHomicidio) {
                            // recupera os dados do homicidio
                            $Modelho = $this->getHomicidioTable()->findHomicidioOcorrencia($modelOcorrencia->getId_oco());
                            //deleta os dados do homicidio
                            $this->getOcorrenciaTable()->delHomicidioOcorrencia($modelOcorrencia->getId_oco());
                            break;
                        }
                    }
                    //deleta o relacionamento na tabela crime da ocorrencia/homicidio
                    $this->getOcorrenciaTable()->delCrimesOcorrencia($modelOcorrencia->getId_oco());


                    //restabelece o no novo relacionamento com os novos crimes
                    foreach ($crimes as $cri) {
                        $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                    }

                    //se não foi modificado homicidio add novamente os dados que fora recuperado após apagado
                    foreach ($crimes as $cri) {
                        if ($cri == 1 && $isHomicidio) {
                            $this->getHomicidioTable()->addHomicidio($Modelho, $modelOcorrencia->getId_oco());
                            break;
                        }
                    }
                }

                //print_r($isHomicidio);
                //print($Modelho);
/////////////////////////casos edição homicídio tabela verdade 1 1 /////////////////////////
                $x = $modelOcorrencia->getId_oco();
                if (count($crimes)) {
                    foreach ($crimes as $cri) {
                        if ($cri == 1 && $isHomicidio) {
                            return $this->redirect()->toRoute('ocorrencia', array('action' => 'editarhomicidio', 'id' => $x));
                            break;
                        }
                    }
                }

/////////////////////////casos edição homicídio tabela verdade 1 0 /////////////////////////
                if (count($crimes)) {
                    foreach ($crimes as $cri) {
                        if ($cri == 1 && !$isHomicidio) {
                            return $this->redirect()->toRoute('ocorrencia', array('action' => 'novohomicidio', 'id' => $x));
                            break;
                        }
                    }
                }

                /////////////////////////casos edição homicídio tabela verdade 0 1 /////////////////////////
                if (count($crimes)) {
                    foreach ($crimes as $cri) {
                        if ($cri != 1 && $isHomicidio) {
                            $this->getOcorrenciaTable()->delHomicidioOcorrencia($modelOcorrencia->getId_oco());
                            $this->getOcorrenciaTable()->delCrimeHomicidioOcorrencia($modelOcorrencia->getId_oco(), 1);
                            $this->flashMessenger()
                                    ->addSuccessMessage("Dados do homicídio editado com sucesso");

                            return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
                            break;
                        }
                        $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                    }
                }

*/

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Ocorrencia editada com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "detalhes", "id" => $modelOcorrencia->getId_oco()));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formOcorrencia', $form)
                                ->setTemplate('application/ocorrencia/editar');
            }
        }
    }

    public function detalhesAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Ocorrencia não encontrada");

            // redirecionar para action index
            return $this->redirect()->toRoute('ocorrencia');
        }

        // enviar para view o array com key policial e value com todos os policias
        $oc = $this->getOcorrenciaTable()->find($id);
        $pols = $this->getPolicialTable()->findByOcorrecia($id);

        $total_vitimas = $this->getOcorrenciaTable()->totalVitimasOcorrencia($id);
        $total_crimes = $this->getOcorrenciaTable()->totalCrimesOcorrencia($id);
        $total_acusados = $this->getOcorrenciaTable()->totalAcusadosOcorrencia($id);

        return new ViewModel(
                array(
            'ocorrencia' => $oc,
            'pols' => $pols,
            'tVitimas' => $total_vitimas,
            'tAcusados' => $total_acusados,
            'tCrimes' => $total_crimes
                )
        );
    }

    public function editarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        // se id = 0 ou não informado redirecione para ocorrências
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Ocorrência não encotrada");
            // redirecionar para action index
            return $this->redirect()->toRoute('ocorrencia');
        }
        try {
            // variável com objetoo corrência localizado em formato de array
            $ocorrencia = (array) $this->getOcorrenciaTable()->find($id);
            $ocorrenciaObj = $this->getOcorrenciaTable()->find($id);

            $ocorrencia['datai'] = $this->getOcorrenciaTable()->toDateDMY($ocorrenciaObj->getDatai());
            $ocorrencia['dataf'] = $this->getOcorrenciaTable()->toDateDMY($ocorrenciaObj->getDataf());
            $ocorrencia['id_end'] = $ocorrenciaObj->getEnd()->getId_end();
            $ocorrencia['rua'] = $ocorrenciaObj->getEnd()->getRua();
            $ocorrencia['numero'] = $ocorrenciaObj->getEnd()->getNumero();

            $crimes_oco = $this->getOcorrenciaTable()->crimesOcorrencia($id);
            $policiais_oco = $this->getOcorrenciaTable()->policiaisOcorrencia($id);
            $procedimentos_oco = $this->getOcorrenciaTable()->procedimentosOcorrencia($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            // redirecionar para action index
            return $this->redirect()->toRoute('ocorrencia');
        }
        // objeto form viatura vazio
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new OcorrenciaForm($dbAdapter);
        //configura o campo select com valor vindo da view index

        $form->get('id_muniO')->setAttributes(array('value' => $ocorrenciaObj->getEnd()->getId_bai()->getMunicipio()->getId_muni(), 'selected' => true));
        $form->get('id_bai')->setAttributes(array('value' => $ocorrenciaObj->getEnd()->getId_bai()->getId_bai(), 'selected' => true));
        $form->get('id_vtr')->setAttributes(array('value' => $ocorrenciaObj->getVtr()->getId_vtr(), 'selected' => true));
        $form->get('id_crime')->setAttributes(array('value' => $this->selectedCrimes($crimes_oco)));
        $form->get('id_composicao')->setAttributes(array('value' => $this->selectedPoliciais($policiais_oco)));
        $form->get('procedimento')->setAttributes(array('value' => $this->selectedProcedimentos($procedimentos_oco)));


        // popula objeto form ocorrencia com objeto model ocorrencia
        $form->setData($ocorrencia);
        // dados eviados para editar.phtml 
        return ['formOcorrencia' => $form];
    }

    public function deletarAction() {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);
        $confirm = (int) $this->params()->fromRoute('confirm', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Ocorrência não encontrada");
        } else {

            if ($confirm) {
                if ($this->getOcorrenciaTable()->deleteOcorrencia($id)) {

                    $this->flashMessenger()->addSuccessMessage("Ocorrência de ID $id deletada com sucesso");
                    // redirecionar para action index
                    return $this->redirect()->toRoute('ocorrencia');
                } else {
                    $this->flashMessenger()->addErrorMessage("Erro na Exclusão da Ocorrência. A mesma deve está vinculada a outras entidades.");
                    // redirecionar para action detalhes
                    return $this->redirect()->toRoute('ocorrencia', array("action" => "deletar", "id" => $id));
                }
            } else {
                // enviar para view o array com key policial e value com todos os policias
                $oc = $this->getOcorrenciaTable()->find($id);
                $pols = $this->getPolicialTable()->findByOcorrecia($id);

                return new ViewModel(array('ocorrencia' => $oc, 'pols' => $pols));
            }
        }
    }

    public function vitimasAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new VitimaForm($dbAdapter);


        $countPerPage = "5";
        $ocorrencias = $this->getVitimaTable()->vitimasOcorrencia($id);

        return new ViewModel(array('vitimas' => $ocorrencias, 'id_ocorrencia' => $id, 'formVitima' => $form));
    }

    //função que retorna uma instancia da classe GraduacaoTable 
    private function getPolicialTable() {
        // localizar adapter do banco
        $adapter = $this->getServiceLocator()->get('AdapterDb');

        // return model PolicialTable
        return new ModelPolicial($adapter); // alias para GraduacaoTable
    }

    private function getViaturaTable() {
        // localizar adapter do banco
        $adapter = $this->getServiceLocator()->get('AdapterDb');

        // return model PolicialTable
        return new ModelViatura($adapter); // alias para GraduacaoTable
    }

    private function getAreaTable() {
        // localizar adapter do banco
        $adapter = $this->getServiceLocator()->get('AdapterDb');

        // return model PolicialTable
        return new ModelArea($adapter); // alias para GraduacaoTable
    }

    private function getVitimaTable() {
        // localizar adapter do banco
        $adapter = $this->getServiceLocator()->get('AdapterDb');

        // return model PolicialTable
        return new ModelVitima($adapter); // alias para GraduacaoTable
    }

    private function getEnderecoTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelEndereco($adapter);
    }

    private function getOcorrenciaCrimeTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelOcorrenciaCrime($adapter);
    }

    private function getBairroTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelBairro($adapter);
    }

    private function getHomicidioTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelHomicidio($adapter);
    }

    function selectedCrimes($crimes = array()) {
        $selected = array();
        foreach ($crimes as $key => $co) {
            $selected[] = $key;
        }
        return $selected;
    }

    function selectedPoliciais($policiais = array()) {
        $selected = array();
        foreach ($policiais as $key => $co) {
            $selected[] = $key;
        }
        return $selected;
    }
       function isPostHomicidio($crimes = array()) {
        $status = false;
        if (count($crimes)) {
            foreach ($crimes as $cri){
                if ($cri == 1) {
                    $status = true;
                    break;
                }    
            }    
        }
        return $status;
    }

    function selectedProcedimentos($procedimentos = array()) {
        $selected = array();
        foreach ($procedimentos as $key => $co) {
            $selected[] = $key;
        }
        return $selected;
    }

}
