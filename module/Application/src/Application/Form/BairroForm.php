<?php

/**
 * namespace de localizacao do nosso formulario
 */

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

use Application\Model\Area;
use Application\Model\AreaTable as ModelArea;

use Application\Model\Municipio;
use Application\Model\MunicipioTable as ModelMunicipio;

class BairroForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormBairro");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_bai',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'bairro',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputBairro',
                'placeholder' => 'Bairro',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select', # ou 'type' => 'ZendFormElementText'
            'name' => 'id_muni',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'id_muni',
            ),
            'options' => array(
                'label' => 'Escolha o ID Município',
                'empty_option' => 'Por favor, escolha um município',
                'value_options' => $this->getOptionsForSelect()),
        ));
        
            $this->add(array(
            'type' => 'Zend\Form\Element\Select', # ou 'type' => 'ZendFormElementText'
            'name' => 'id_area',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'id_area',
            ),
            'options' => array(
                'label' => 'Escolha o ID Área',
                'empty_option' => 'Por favor, escolha uma área',
               // 'value_options' => $this->getOptionsForSelectA()),
       )));
    }

    public function getOptionsForSelect() {
        $selectData = $this->getMunicipioTable()->fetchAll();
        return $selectData;
    }

  
   private function getMunicipioTable() {
        $dbAdapter = $this->adapter;
        return new ModelMunicipio($dbAdapter);
    }
    
        public function getOptionsForSelectA() {
        $selectData = $this->getAreaTable()->fetchAll();
        return $selectData;
    }

  
   private function getAreaTable() {
        $dbAdapter = $this->adapter;
        return new ModelArea($dbAdapter);
    }



}
