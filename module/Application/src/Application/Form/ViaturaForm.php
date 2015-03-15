<?php

/**
 * namespace de localizacao do nosso formulario
 */

namespace Application\Form;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Application\Model\Area;
use Application\Model\AreaTable as ModelArea;

class ViaturaForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormVtr");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_vtr',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'prefixo',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputPrefixo',
                'placeholder' => 'Prefixo',
            ),
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
                'value_options' => $this->getOptionsForSelect()),
        ));
    }

    public function getOptionsForSelect() {
        $selectData = $this->getAreaTable()->fetchAll();
        return $selectData;
    }

    private function getAreaTable() {
        $dbAdapter = $this->adapter;
        return new ModelArea($dbAdapter);
    }

}
