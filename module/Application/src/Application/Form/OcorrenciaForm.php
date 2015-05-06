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
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_vtr[]',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'chosen-select',
               ' style' => 'width:350px',
                'multiple' => 'true'
         
   
            ),
            'options' => array(
                'label' => 'Escolha a viatura',
                'empty_option' => 'Escolha a viatura',
                'value_options' => $this->getOptionsForSelectV()),
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'hora',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputHora',
                'placeholder' => 'Hora',
            ),
        ));
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

}
