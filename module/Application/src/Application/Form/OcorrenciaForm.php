<?php

namespace Application\Form;

use Application\Controller\ViaturaController;
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
            'name' => 'id_oco',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'id_end',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputEnd',
                'placeholder' => 'endereço',
            ),
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
                'id' => 'chosen-select',
               ' style' => 'width:350px',
               // 'multiple' => 'true'
         
   
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
                'multiple' => 'true'
            ),
            'options' => array(
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
                'multiple' => 'true'
            ),
            'options' => array(
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
                'multiple' => 'true'
            ),
            'options' => array(
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
        
        /*
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'data',
            'options' => array(
                'label' => 'Appointment Date/Time',
                'format' => 'Y-m-d'
            ),
            'attributes' => array(
                'class' => 'form-control',
            // 'min' => '1940-01-01T00:00:00Z',
            //'max' => '1997-01-01T00:00:00Z',
            )
        ));
         */
        
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
    }

    public function getOptionsForSelect() {
        $selectData = $this->getMunicipioTable()->fetchAll();
        return $selectData;
    }

    private function getMunicipioTable() {
        $dbAdapter = $this->adapter;
        return new ModelMunicipio($dbAdapter);
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
}
