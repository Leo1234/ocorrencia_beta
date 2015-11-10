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
use Application\Form\LesaoForm;
use Application\Form\ApreArmaForm;
use Application\Form\ApreVeicForm;
use Application\Model\OcorrenciaCrime;
use Application\Model\OcorrenciaCrimeTable as ModelOcorrenciaCrime;
use Application\Model\Homicidio;
use Application\Model\HomicidioTable as ModelHomicidio;
use Application\Model\Lesao;
use Application\Model\LesaoTable as ModelLesao;
use Application\Model\ApreArma;
use Application\Model\ApreArmaTable as ModelArma;
use Application\Model\ApreVeic;
use Application\Model\ApreVeicTable as ModelVeiculo;
use Application\Model\DadosExtras;

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


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);

        return new ViewModel(
                array(
            'id' => $id,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
                )
        );
    }

    public function novalesaoAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'id' => $id,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
                )
        );
    }

    public function novaarmaAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $form = new ApreArmaForm();
        $form->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'id' => $id,
            'formA' => $form,
            'form' => $formOcorrencia,
                )
        );
    }

    public function novoveiculoAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $form = new ApreVeicForm();
        $form->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'id' => $id,
            'formV' => $form,
            'form' => $formOcorrencia,
                )
        );
    }

    public function novovhAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $formV = new ApreVeicForm();
        $formV->get('id')->setAttributes(array('value' => $id));

        $formH = new HomicidioForm();
        $formH->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);

        return new ViewModel(
                array(
            'formV' => $formV,
            'formH' => $formH,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novovlAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $formV = new ApreVeicForm();
        $formV->get('id')->setAttributes(array('value' => $id));

        $formL = new LesaoForm();
        $formL->get('id')->setAttributes(array('value' => $id));

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formV' => $formV,
            'formL' => $formL,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novovaAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $formV = new ApreVeicForm();
        $formV->get('id')->setAttributes(array('value' => $id));

        $formA = new ApreArmaForm();
        $formA->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formV' => $formV,
            'formA' => $formA,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novohlAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));

        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formH' => $formHomicidio,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novoalAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formArma = new ApreArmaForm();
        $formArma->get('id')->setAttributes(array('value' => $id));

        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formA' => $formArma,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novoalhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formArma = new ApreArmaForm();
        $formArma->get('id')->setAttributes(array('value' => $id));

        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));

        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formA' => $formArma,
            'formL' => $formLesao,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novovlhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $formVeiculo->get('id')->setAttributes(array('value' => $id));

        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));

        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);

        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formL' => $formLesao,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novovahAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $formVeiculo->get('id')->setAttributes(array('value' => $id));

        $formArma = new ApreArmaForm();
        $formArma->get('id')->setAttributes(array('value' => $id));

        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formA' => $formArma,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novovalAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $formVeiculo->get('id')->setAttributes(array('value' => $id));

        $formArma = new ApreArmaForm();
        $formArma->get('id')->setAttributes(array('value' => $id));

        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);



        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formA' => $formArma,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novovalhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $formVeiculo->get('id')->setAttributes(array('value' => $id));

        $formArma = new ApreArmaForm();
        $formArma->get('id')->setAttributes(array('value' => $id));

        $formLesao = new LesaoForm();
        $formLesao->get('id')->setAttributes(array('value' => $id));

        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formA' => $formArma,
            'formL' => $formLesao,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function novohaAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formHomicidio = new HomicidioForm();
        $formHomicidio->get('id')->setAttributes(array('value' => $id));

        $formArma = new ApreArmaForm();
        $formArma->get('id')->setAttributes(array('value' => $id));


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formH' => $formHomicidio,
            'formA' => $formArma,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarhomicidioAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formHomicidio = new HomicidioForm();
        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);

        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $Modelho = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);

        $formOcorrencia->setData($ModelOco);
        $formHomicidio->setData($Modelho);

        return new ViewModel(
                array(
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarlesaoAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formLesao = new LesaoForm();
        $ModelLes = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLes);


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);

        return new ViewModel(
                array(
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editararmaAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formA' => $formArma,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarveiculoAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);

        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $formHomicidio = new HomicidioForm();
        $ModelHomicidio = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
        $formHomicidio->setData($ModelHomicidio);


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);




        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvlAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $formLesao = new LesaoForm();
        $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLesao);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);



        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvaAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formV' => $formVeiculo,
            'formA' => $formArma,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarhlAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formHomicidio = new HomicidioForm();
        $Modelho = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
        $formHomicidio->setData($Modelho);

        $formLesao = new LesaoForm();
        $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLesao);


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);

        return new ViewModel(
                array(
            'formH' => $formHomicidio,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarhaAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formHomicidio = new HomicidioForm();
        $Modelho = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
        $formHomicidio->setData($Modelho);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formH' => $formHomicidio,
            'formA' => $formArma,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editaralAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formLesao = new LesaoForm();
        $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLesao);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formL' => $formLesao,
            'formA' => $formArma,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editaralhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formLesao = new LesaoForm();
        $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLesao);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);

        $formHomicidio = new HomicidioForm();
        $ModelHomicidio = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
        $formHomicidio->setData($ModelHomicidio);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formL' => $formLesao,
            'formA' => $formArma,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvlhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formLesao = new LesaoForm();
        $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLesao);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $formHomicidio = new HomicidioForm();
        $ModelHomicidio = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
        $formHomicidio->setData($ModelHomicidio);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formL' => $formLesao,
            'formV' => $formVeiculo,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvahAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $formHomicidio = new HomicidioForm();
        $ModelHomicidio = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
        $formHomicidio->setData($ModelHomicidio);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);



        return new ViewModel(
                array(
            'formA' => $formArma,
            'formV' => $formVeiculo,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvalAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        $formArma = new ApreArmaForm();
        $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
        $formArma->setData($ModelArma);

        $formVeiculo = new ApreVeicForm();
        $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
        $formVeiculo->setData($ModelVeiculo);

        $formLesao = new LesaoForm();
        $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
        $formLesao->setData($ModelLesao);

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


        return new ViewModel(
                array(
            'formA' => $formArma,
            'formV' => $formVeiculo,
            'formL' => $formLesao,
            'form' => $formOcorrencia,
            'id' => $id,
                )
        );
    }

    public function editarvalhAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        $crimes = (array) $this->getOcorrenciaCrimeTable()->crimesOcorrencia($id);     
        $crimeAux =  array();

          if (in_array(1, $crimes)) {
            $formHomicidio = new HomicidioForm();
            $ModelHomicidio = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($id);
            $formHomicidio->setData($ModelHomicidio);
            $crimeAux['formH'] = $formHomicidio;
        }

        if (in_array(2, $crimes)) {
            $formLesao = new LesaoForm();
            $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($id);
            $formLesao->setData($ModelLesao);
            $crimeAux['formL'] = $formLesao;
        }

        if (in_array(12, $crimes)) {
            $formArma = new ApreArmaForm();
            $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($id);
            $formArma->setData($ModelArma);
            $crimeAux['formA'] = $formArma;
        }
        if (in_array(13, $crimes)) {          
            $formVeiculo = new ApreVeicForm();
            $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($id);
            $formVeiculo->setData($ModelVeiculo);
            $crimeAux['formV'] = $formVeiculo;
        }


        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $formOcorrencia = new OcorrenciaForm($dbAdapter);
        $ModelOco = (array) $this->getOcorrenciaTable()->find($id);
        $formOcorrencia->setData($ModelOco);


         $crimeAux['form'] = $formOcorrencia;
         $crimeAux['id'] = $id;
         $crimeAux['crimes'] = $crimes;
         
        return new ViewModel($crimeAux
               /* array(
            'formA' => $formArma,
            'formV' => $formVeiculo,
            'formL' => $formLesao,
            'formH' => $formHomicidio,
            'form' => $formOcorrencia,
            'id' => $id,
            'crimes' => $crimes,
                )*/
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
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novohomicidio');
            }
        }
    }

    public function adicionarHLAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formH = new HomicidioForm();
            $formL = new LesaoForm();
            // instancia model contato com regras de filtros e validações
            $modelHomicidio = new Homicidio();
            $modelLesao = new Lesao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formH->setData($request->getPost());
            $formL->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formH->isValid() && $formL->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelHomicidio->exchangeArray($formH->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados do homicídio/lesão adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formH', $formH)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novohl');
            }
        }
    }

    public function adicionarALAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            // instancia model contato com regras de filtros e validações
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formA->isValid() && $formL->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getArmaTable()->addArma($modelArma, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados do arma/lesão adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novoal');
            }
        }
    }

    public function adicionarALHAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            $formH = new HomicidioForm();
            // instancia model contato com regras de filtros e validações
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());
            $formH->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formA->isValid() && $formL->isValid() && $formH->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getArmaTable()->addArma($modelArma, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados da arma/lesão/homicídio adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novoalh');
            }
        }
    }

    public function adicionarVLHAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formL = new LesaoForm();
            $formH = new HomicidioForm();
            // instancia model contato com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelLesao = new Lesao();
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formL->setData($request->getPost());
            $formH->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formL->isValid() && $formH->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelLesao->exchangeArray($formL->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelVeiculo, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados da veículo/lesão/homicídio adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formL', $formL)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novovlh');
            }
        }
    }

    public function adicionarVAHAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            $formH = new HomicidioForm();
            // instancia model contato com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelArma = new ApreArma();
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            $formH->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid() && $formH->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelVeiculo, $postData['id']);
                $this->getArmaTable()->addArma($modelArma, $postData['id']);
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados da veículo/arma/homicídio adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novovah');
            }
        }
    }

    public function adicionarVALAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            // instancia model contato com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid() && $formL->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelVeiculo, $postData['id']);
                $this->getArmaTable()->addArma($modelArma, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados da veículo/arma/lesão adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novoval');
            }
        }
    }

    public function adicionarVALHAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            $formH = new HomicidioForm();
            // instancia model contato com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());
            $formH->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid() && $formL->isValid() && $formH->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelVeiculo, $postData['id']);
                $this->getArmaTable()->addArma($modelArma, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados da veículo/arma/lesão/homicídio adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novovalh');
            }
        }
    }

    public function adicionarHAAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formH = new HomicidioForm();
            $formA = new ApreArmaForm();
            // instancia model contato com regras de filtros e validações
            $modelHomicidio = new Homicidio();
            $modelArma = new ApreArma();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formH->setData($request->getPost());
            $formA->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formH->isValid() && $formA->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelHomicidio->exchangeArray($formH->getData());
                $modelArma->exchangeArray($formA->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);
                $this->getArmaTable()->addArma($modelArma, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados do homicídio/arma adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formH', $formH)
                                ->setVariable('formA', $formA)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novoha');
            }
        }
    }

    public function adicionarLesaoAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $form = new LesaoForm();
            // instancia model contato com regras de filtros e validações
            $modelLesao = new Lesao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelLesao->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('form', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novalesao');
            }
        }
    }

    public function adicionarArmaAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $form = new ApreArmaForm();
            // instancia model contato com regras de filtros e validações
            $modelApreArma = new ApreArma();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelApreArma->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelApreArma->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getArmaTable()->addArma($modelApreArma, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                $x = $postData['id'];
                return (new ViewModel())
                                ->setVariable('form', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novaarma');
            }
        }
    }

    public function adicionarVeiculoAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $form = new ApreVeicForm();
            // instancia model contato com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelApreVeic->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelApreVeic, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('form', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novoveiculo');
            }
        }
    }

    public function adicionarVHAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formH = new HomicidioForm();
            // instancia model contato com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            $modelHomicidio = new Homicidio();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelApreVeic->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formH->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formH->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($formV->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelApreVeic, $postData['id']);
                $this->getHomicidioTable()->addHomicidio($modelHomicidio, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novovh');
            }
        }
    }

    public function adicionarVLAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formL = new LesaoForm();
            // instancia model contato com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            $modelLesao = new Lesao();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelApreVeic->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formL->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formL->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($formV->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelApreVeic, $postData['id']);
                $this->getLesaoTable()->addLesao($modelLesao, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novovl');
            }
        }
    }

    public function adicionarVAAction() {
        // obtém a requisição
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        $id = $postData['id'];

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            // instancia model contato com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            $modelArma = new ApreArma();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $formV->setInputFilter($modelApreVeic->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            //var_dump($form);
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getVeiculoTable()->addVeiculo($modelApreVeic, $postData['id']);
                $this->getArmaTable()->addArma($modelArma, $postData['id']);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras adicionados com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('ocorrencia');
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/novova');
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
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formH', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarhomicidio');
            }
        }
    }

    public function atualizarLesaoAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $form = new LesaoForm();
            // instancia model municipio com regras de filtros e validações
            $modelLesao = new Lesao();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $form->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelLesao->exchangeArray($form->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getLesaoTable()->update($modelLesao, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formL', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarlesao');
            }
        }
    }

    public function atualizarArmaAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);
        $postData = $request->getPost()->toArray();
        $x = $postData['id'];

        if ($request->isPost()) {
            // instancia formulário
            $form = new ApreArmaForm();
            // instancia model municipio com regras de filtros e validações
            $modelApreArma = new ApreArma();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $form->setInputFilter($modelApreArma->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelApreArma->exchangeArray($form->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getArmaTable()->update($modelApreArma, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formA', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editararma');
            }
        }
    }

    public function atualizarVeiculoAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $form = new ApreVeicForm();
            // instancia model municipio com regras de filtros e validações
            $modelApreVeic = new ApreVeic();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $form->setInputFilter($modelApreVeic->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($form->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getVeiculoTable()->update($modelApreVeic, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $form)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarveiculo');
            }
        }
    }

    public function atualizarVHAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formV = new ApreVeicForm();
            $formH = new HomicidioForm();
            // instancia model municipio com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            $modelHomicidio = new Homicidio();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity  ApreVeic
            $formV->setInputFilter($modelApreVeic->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formH->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formH->isValid()) {
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($formV->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - atualizar dados do model para banco de dados
                ;
                $this->getVeiculoTable()->update($modelApreVeic, $id);
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarvh');
            }
        }
    }

    public function atualizarVLAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formV = new ApreVeicForm();
            $formL = new LesaoForm();
            // instancia model municipio com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            $modelLesao = new Lesao();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity  ApreVeic
            $formV->setInputFilter($modelApreVeic->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formL->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formL->isValid()) {
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($formV->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - atualizar dados do model para banco de dados
                ;
                $this->getVeiculoTable()->update($modelApreVeic, $id);
                $this->getLesaoTable()->update($modelLesao, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarvl');
            }
        }
    }

    public function atualizarVAAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            // instancia model municipio com regras de filtros e validações
            $modelApreVeic = new ApreVeic();
            $modelArma = new ApreArma();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity  ApreVeic
            $formV->setInputFilter($modelApreVeic->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid()) {
                // 1 - popular model com valores do formulário
                $modelApreVeic->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                // 2 - atualizar dados do model para banco de dados

                $this->getVeiculoTable()->update($modelApreVeic, $id);
                $this->getArmaTable()->update($modelArma, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarva');
            }
        }
    }

    public function atualizarHLAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formH = new HomicidioForm();
            $formL = new LesaoForm();
            // instancia model municipio com regras de filtros e validações
            $modelHomicidio = new Homicidio();
            $modelLesao = new Lesao();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formH->setData($request->getPost());
            $formL->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formH->isValid() && $formL->isValid()) {
                // 1 - popular model com valores do formulário
                $modelHomicidio->exchangeArray($formH->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                $this->getLesaoTable()->update($modelLesao, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formH', $formH)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarhl');
            }
        }
    }

    public function atualizarALAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            // instancia model municipio com regras de filtros e validações
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formA->isValid() && $formL->isValid()) {
                // 1 - popular model com valores do formulário
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getArmaTable()->update($modelArma, $id);
                $this->getLesaoTable()->update($modelLesao, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editaral');
            }
        }
    }

    public function atualizarALHAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            $formH = new HomicidioForm();
            // instancia model municipio com regras de filtros e validações
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();
            $modelHomicidio = new Homicidio();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());
            $formH->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formA->isValid() && $formL->isValid() && $formH->isValid()) {
                // 1 - popular model com valores do formulário
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getArmaTable()->update($modelArma, $id);
                $this->getLesaoTable()->update($modelLesao, $id);
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editaralh');
            }
        }
    }

    public function atualizarVLHAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formV = new ApreVeicForm();
            $formL = new LesaoForm();
            $formH = new HomicidioForm();
            // instancia model municipio com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelLesao = new Lesao();
            $modelHomicidio = new Homicidio();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formL->setData($request->getPost());
            $formH->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formL->isValid() && $formH->isValid()) {
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelLesao->exchangeArray($formL->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getVeiculoTable()->update($modelVeiculo, $id);
                $this->getLesaoTable()->update($modelLesao, $id);
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formL', $formL)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarvlh');
            }
        }
    }

    public function atualizarVAHAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            $formH = new HomicidioForm();
            // instancia model municipio com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelArma = new ApreArma();
            $modelHomicidio = new Homicidio();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            $formH->setInputFilter($modelHomicidio->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            $formH->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid() && $formH->isValid()) {
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                $modelHomicidio->exchangeArray($formH->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getVeiculoTable()->update($modelVeiculo, $id);
                $this->getArmaTable()->update($modelArma, $id);
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarvah');
            }
        }
    }

    public function atualizarVALAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formV = new ApreVeicForm();
            $formA = new ApreArmaForm();
            $formL = new LesaoForm();
            // instancia model municipio com regras de filtros e validações
            $modelVeiculo = new ApreVeic();
            $modelArma = new ApreArma();
            $modelLesao = new Lesao();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formV->setInputFilter($modelVeiculo->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());
            $formL->setInputFilter($modelLesao->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formV->setData($request->getPost());
            $formA->setData($request->getPost());
            $formL->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formV->isValid() && $formA->isValid() && $formL->isValid()) {
                // 1 - popular model com valores do formulário
                $modelVeiculo->exchangeArray($formV->getData());
                $modelArma->exchangeArray($formA->getData());
                $modelLesao->exchangeArray($formL->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getVeiculoTable()->update($modelVeiculo, $id);
                $this->getArmaTable()->update($modelArma, $id);
                $this->getLesaoTable()->update($modelLesao, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarval');
            }
        }
    }

    public function atualizarVALHAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);           
        $crimes = (array) $this->getOcorrenciaCrimeTable()->crimesOcorrencia($id);
 
        $status = false; //controla a validação dos formulários
        
        $h = false;
        $l = false;
        $a = false;
        $v = false;
        
        
         // instancia formulário
        $formH = new HomicidioForm();
        $formL = new LesaoForm();
        $formA = new ApreArmaForm();
        $formV = new ApreVeicForm();
        
        
        if ($request->isPost()) {
                
            // instancia model com regras de filtros e validações
            // passa para o objeto formulário as regras de viltros e validações
            // passa para o objeto formulário os dados vindos da submissão 

            if (in_array(1, $crimes)) {
                $modelHomicidio = new Homicidio();
                $formH->setInputFilter($modelHomicidio->getInputFilter());
                $formH->setData($request->getPost());
                $h = true;

                if ($formH->isValid()) {
                    //popular model com valores do formulário
                    $modelHomicidio->exchangeArray($formH->getData());
                } else {
                    $status = true;
                }
            }

            if (in_array(2, $crimes)) {
                $modelLesao = new Lesao();
                $formL->setInputFilter($modelLesao->getInputFilter());
                $formL->setData($request->getPost());
                $l = true;

                if ($formL->isValid()) {
                    //popular model com valores do formulário
                    $modelLesao->exchangeArray($formL->getData());
                } else {
                    $status = true;
                }
            }

            if (in_array(12, $crimes)) {
                $modelArma = new ApreArma();
                $formA->setInputFilter($modelArma->getInputFilter());
                $formA->setData($request->getPost());
                $a = true;

                if ($formA->isValid()) {
                    //popular model com valores do formulário
                    $modelArma->exchangeArray($formA->getData());
                } else {
                    $status = true;
                }
            }
            if (in_array(13, $crimes)) {
                $modelVeiculo = new ApreVeic();
                $formV->setInputFilter($modelVeiculo->getInputFilter());
                $formV->setData($request->getPost());
                $v = true;

                if ($formV->isValid()) {
                    //popular model com valores do formulário
                    $modelVeiculo->exchangeArray($formV->getData());
                } else {
                    $status = true;
                }
            }

             // verifica se o formulário segue a validação proposta
            if (!$status) {
                // atualizar dados do model para banco de dados
                if($h)
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                if($l)
                $this->getLesaoTable()->update($modelLesao, $id);
                if($a)
                $this->getArmaTable()->update($modelArma, $id);
                if($v)
                $this->getVeiculoTable()->update($modelVeiculo, $id);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
               
                return (new ViewModel())
                                ->setVariable('formV', $formV)
                                ->setVariable('formA', $formA)
                                ->setVariable('formL', $formL)
                                ->setVariable('formH', $formH)
                                ->setVariable('id', $id)
                                ->setVariable('crimes', $crimes)
                                ->setTemplate('application/ocorrencia/editarvalh');
            }
        }
    }

    public function atualizarHAAction() {

        // obtém a requisição
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($request->isPost()) {
            // instancia formulário
            $formH = new HomicidioForm();
            $formA = new ApreArmaForm();
            // instancia model municipio com regras de filtros e validações
            $modelHomicidio = new Homicidio();
            $modelArma = new ApreArma();

            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity Municipio
            $formH->setInputFilter($modelHomicidio->getInputFilter());
            $formA->setInputFilter($modelArma->getInputFilter());

            // passa para o objeto formulário os dados vindos da submissão 
            $formH->setData($request->getPost());
            $formA->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($formH->isValid() && $formA->isValid()) {
                // 1 - popular model com valores do formulário
                $modelHomicidio->exchangeArray($formH->getData());
                $modelArma->exchangeArray($formA->getData());
                // 2 - atualizar dados do model para banco de dados
                //print_r($modelHomicidio);
                $this->getHomicidioTable()->update($modelHomicidio, $id);
                $this->getArmaTable()->update($modelArma, $id);
                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Dados extras editado com sucesso");
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('ocorrencia', array("action" => "index"));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formH', $formH)
                                ->setVariable('formA', $formA)
                                ->setVariable('id', $id)
                                ->setTemplate('application/ocorrencia/editarha');
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
            $form->setInputFilter($modelOcorrencia->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
            // verifica se o formulário segue a validação proposta
            if ($form->isValid()){

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
                $isLesao = $this->getLesaoTable()->isLesao($modelOcorrencia->getId_oco());
                $isArma = $this->getArmaTable()->isArma($modelOcorrencia->getId_oco());
                $isVeiculo = $this->getVeiculoTable()->isVeiculo($modelOcorrencia->getId_oco());
                
                $ModelHomicidio = new Homicidio();
                $ModelLesao = new Lesao();
                $ModelArma= new ApreArma();
                $ModelVeiculo = new ApreVeic();


                //V A L H 1/////////////////////crimes com VEÍCULO/ARMA/LESÃO/HOMICÍDIO já existente//////////////////
                if ($isHomicidio) {
                    //recupera os dados da homicídio                  
                    $ModelHomicidio = $this->getHomicidioTable()->findHomicidioOcorrencia($modelOcorrencia->getId_oco());
                    //deleta homicídio
                    $this->getOcorrenciaTable()->delHomicidioOcorrencia($modelOcorrencia->getId_oco());
                }
                if ( $isLesao) {
                    //recupera os dados da lesão                   
                    $ModelLesao = $this->getLesaoTable()->findLesaoOcorrencia($modelOcorrencia->getId_oco());
                    //deleta lesão
                    $this->getOcorrenciaTable()->delLesaoOcorrencia($modelOcorrencia->getId_oco());
                }
                if ($isArma) {
                    //recupera os dados do arma                  
                    $ModelArma = $this->getArmaTable()->findArmaOcorrencia($modelOcorrencia->getId_oco());
                    //deleta arma
                    $this->getOcorrenciaTable()->delArmaOcorrencia($modelOcorrencia->getId_oco());
                }
                if ($isVeiculo) {
                    //recupera os dados do veículo                   
                    $ModelVeiculo = $this->getVeiculoTable()->findVeiculoOcorrencia($modelOcorrencia->getId_oco());
                    //deleta veículo
                    $this->getOcorrenciaTable()->delVeiculoOcorrencia($modelOcorrencia->getId_oco());
                }

                //deleta os crimes
                $this->getOcorrenciaTable()->delCrimesOcorrencia($modelOcorrencia->getId_oco());

                //adiciona os crimes novos 
                foreach ($crimes as $cri) {
                    $this->getOcorrenciaTable()->addCrimeOcorrencia($modelOcorrencia->getId_oco(), $cri);
                }

                //adicionada os dados extra dos veículo/arma/lesão/homicídio se removidos

                if (in_array(1, $crimes) && $isHomicidio) {
                     $this->getHomicidioTable()->addhomicidio($ModelHomicidio, $modelOcorrencia->getId_oco());
                
                }
                if (in_array(2, $crimes) && $isLesao) {
                    $this->getLesaoTable()->addLesao($ModelLesao, $modelOcorrencia->getId_oco());
               
                }
                if (in_array(12, $crimes) && $isArma) {
                    $this->getArmaTable()->addArma($ModelArma, $modelOcorrencia->getId_oco());
                  
                }

                if (in_array(13, $crimes) && $isVeiculo) {
                    $this->getVeiculoTable()->addVeiculo($ModelVeiculo, $modelOcorrencia->getId_oco());
                
                }

                //redireciona para action editar veículo/arma/lesao/homicídio e pegar dados extras
                $x = $modelOcorrencia->getId_oco();

                 if (in_array(1, $crimes) || in_array(2, $crimes) || in_array(12, $crimes) || in_array(13, $crimes)) {
                  return $this->redirect()->toRoute('ocorrencia', array("action" => "editarvalh", "id" => $modelOcorrencia->getId_oco()));
                 }
                //aqui
                /*
                $formHomicidio = new HomicidioForm();
                $ModelHomicidio = (array) $this->getHomicidioTable()->findHomicidioOcorrencia($x);
                $formHomicidio->setData($ModelHomicidio);

                $formLesao = new LesaoForm();
                $ModelLesao = (array) $this->getLesaoTable()->findLesaoOcorrencia($x);
                $formLesao->setData($ModelLesao);

                $formArma = new ApreArmaForm();
                $ModelArma = (array) $this->getArmaTable()->findArmaOcorrencia($x);
                $formArma->setData($ModelArma);

                $formVeiculo = new ApreVeicForm();
                $ModelVeiculo = (array) $this->getVeiculoTable()->findVeiculoOcorrencia($x);
                $formVeiculo->setData($ModelVeiculo);

                if (in_array(1, $crimes) || in_array(2, $crimes) || in_array(12, $crimes) || in_array(13, $crimes)) {
                    $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
                    $formOcorrencia = new OcorrenciaForm($dbAdapter);
                    $ModelOco = (array) $this->getOcorrenciaTable()->find($x);
                    $formOcorrencia->setData($ModelOco);
                    
                    return (new ViewModel())
                                    ->setVariable('formA', $formArma)
                                    ->setVariable('formV', $formVeiculo)
                                    ->setVariable('formL', $formLesao)
                                    ->setVariable('formH', $formHomicidio)
                                    ->setVariable('form', $formOcorrencia)
                                    ->setVariable('id', $x)
                                    ->setVariable('crimes', $crimes)
                                    ->setTemplate('application/ocorrencia/editarvalh');
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

    private function getLesaoTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelLesao($adapter);
    }

    private function getVeiculoTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelVeiculo($adapter);
    }

    private function getArmaTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelArma($adapter);
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
            foreach ($crimes as $cri) {
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

    function crimesExtras($crimes = array()) {
        $selected = array();
        foreach ($crimes as $cri) {
            if ($cri == 1) {
                $selected[] = $cri;
                break;
            }
        }
        foreach ($crimes as $cri) {
            if ($cri == 2) {
                $selected[] = $cri;
                break;
            }
        }
        foreach ($crimes as $cri) {
            if ($cri == 12) {
                $selected[] = $cri;
                break;
            }
        }
        foreach ($crimes as $cri) {
            if ($cri == 13) {
                $selected[] = $cri;
                break;
            }
        }

        return $selected;
    }

}
