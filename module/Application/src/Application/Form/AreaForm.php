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

use Application\Model\Municpio;
use Application\Model\MunicipioTable as ModelMunicipio;

class AreaForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormArea");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_area',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'descricao',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputDescricao',
                'placeholder' => 'Descrição',
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
    }

    public function getOptionsForSelect() {
        $selectData = $this->getMunicipioTable()->fetchAll();
        return $selectData;
    }

  
   private function getMunicipioTable() {
        $dbAdapter = $this->adapter;
        return new ModelMunicipio($dbAdapter);
    }


}
