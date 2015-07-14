<?php

namespace Application\Form;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Application\Model\Viatura;
use Application\Model\ViaturaTable as ModelViatura;
use Application\Model\Municipio;
use Application\Model\MunicipioTable as ModelMunicipio;
use Application\Model\Bairro;
use Application\Model\BairroTable as ModelBairro;

use Application\Model\Policial;
use Application\Model\PolicialTable as ModelPolicial;
use Application\Model\Crime;
use Application\Model\CrimeTable as ModelCrime;
use Application\Model\Procedimento;
use Application\Model\ProcedimentoTable as ModelProcedimento;

class OcorrenciaForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormOcorrencia");
        
        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_ocorrencia',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'rua',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'rua',
                'placeholder' => 'Rua',
            ),
        ));
             $this->add(array(
            'type' => 'Zend\Form\Element\Number', # ou 'type' => 'ZendFormElementText'
            'name' => 'numero',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'numero',
                'placeholder' => 'Número',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_muniO',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'municipio',
                ' style' => 'width:350px',
            ),
            'options' => array(
                'label' => 'Município',
                'empty_option' => 'Escolha o município',
                'value_options' => $this->getOptionsForSelectM()),
        ));


        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_bai',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'bairro',
                ' style' => 'width:350px',
            // 'multiple' => 'true'
            ),
            'options' => array(
                'label' => 'Escolha o bairro',
                'empty_option' => 'Escolha o bairro',
                'value_options' => $this->getOptionsForSelectB()),
        ));

        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'ciops',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputCiops',
                'placeholder' => 'CIOPS',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_vtr',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'viatura',
                ' style' => 'width:350px',
            
            ),
            'options' => array(
                'label' => 'Escolha a viatura',
                'empty_option' => 'Escolha a viatura',
                'value_options' => $this->getOptionsForSelectV()),
        ));
        
   
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_composicao',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'composicao',
                ' style' => 'width:350px',
                //'multiple' => 'true'
                'multiple' => 'multiple'
            ),
            'options' => array(
                'disable_inarray_validator' => true,
                'data-placeholder' => 'Escolha a Composição',
                //'empty_option' => 'Escolha a viatura',
                'value_options' => $this->getOptionsForSelectP()),
            
          
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_crime',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'crime',
                ' style' => 'width:350px',
                //'multiple' => 'true'
                'multiple' => 'multiple'
            ),
            'options' => array(
                 'disable_inarray_validator' => true,
                'data-placeholder' => 'Escolha os crimes',
                //'empty_option' => 'Escolha a viatura',
                'value_options' => $this->getOptionsForSelectC()),
           
          
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'procedimento',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'procedimento',
                ' style' => 'width:350px',
               // 'multiple' => 'true'
                'multiple' => 'multiple'
            ),
            'options' => array(
                'disable_inarray_validator' => true,
                'data-placeholder' => 'Escolha os procedimentos',
                //'empty_option' => 'Escolha a viatura',
                'value_options' => $this->getOptionsForSelectPr()),
                
        
        ));


        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'datai',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputDatai',
                'placeholder' => 'Data/hora Início',
            ),
        ));

        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'dataf',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputDataf',
                'placeholder' => 'Data/hora Fim',
            ),
        ));


        // elemento do tipo text
        $this->add(array(
            'name' => 'narracao',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Narracão',
            ),
            'attributes' => array(
                'class' => 'form-control',
            )
        ));
/*
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'local',
            'options' => array(
                'label' => 'Qual tipo de endereço?',
                'value_options' => array(
                    'c' => '  Convencional  ',
                    'l' => '  Lat/Long  ',
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Text',
            'name' => 'dt',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'dt',
                'placeholder' => 'Data',
                'data-format' => 'dd/MM/yyyy hh:mm:ss',
            ),
        ));
 
 */

    }

    public function getOptionsForSelectV() {
        $selectData = $this->getVtrTable()->fetchAll2();
        return $selectData;
    }

    private function getVtrTable() {
        $dbAdapter = $this->adapter;
        return new ModelViatura($dbAdapter);
    }

    public function getOptionsForSelectP() {
        $selectData = $this->getPolicialTable()->fetchAll();
        return $selectData;
    }

    private function getPolicialTable() {
        $dbAdapter = $this->adapter;
        return new ModelPolicial($dbAdapter);
    }

    public function getOptionsForSelectC() {
        $selectData = $this->getCrimeTable()->fetchAll();
        return $selectData;
    }

    private function getCrimeTable() {
        $dbAdapter = $this->adapter;
        return new ModelCrime($dbAdapter);
    }

    public function getOptionsForSelectPr() {
        $selectData = $this->getProcedimentoTable()->fetchAll();
        return $selectData;
    }

    private function getProcedimentoTable() {
        $dbAdapter = $this->adapter;
        return new ModelProcedimento($dbAdapter);
    }

    public function getOptionsForSelectB() {
        $selectData = $this->getBairroTable()->fetchAll();
        return $selectData;
    }

    private function getBairroTable() {
        $dbAdapter = $this->adapter;
        return new ModelBairro($dbAdapter);
    }

    public function getOptionsForSelectM() {
        $selectData = $this->getMunicipioTable()->fetchAll();
        return $selectData;
    }

    private function getMunicipioTable() {
        $dbAdapter = $this->adapter;
        return new ModelMunicipio($dbAdapter);
    }

}
