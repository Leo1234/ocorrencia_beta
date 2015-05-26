<?php

/**
 * namespace de localizacao do nosso formulario
 */

namespace Application\Form;

use Application\Controller\PolicialController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
//use Zend\Form\Number;
//use Zend\I18n\Validator\Float;
use Zend\Form\Element;
use Zend\Form\ElementText;
use Zend\Form\Element\Select;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\View\Helper\FormRadio;
use Zend \ Form \ Elemento \ Radio;
use Zend\Db\Adapter\Adapter;
use Application\Model\Municipio;
use Application\Model\MunicipioTable as ModelMunicipio;
use Application\Model\Bairro;
use Application\Model\BairroTable as ModelBairro;

class VitimaForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormVitima");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_vitima',
        ));

        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'end',
        ));

        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'nome',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputNome',
                'placeholder' => 'Nome',
            ),
        ));
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'telefone',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'telefone',
                'placeholder' => 'Telefone',
            ),
        ));

        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'data_nasc',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputDataNascV',
                'placeholder' => 'Data/hora Nascimento',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'sexo',
            'options' => array(
                'label' => 'Qual sexo?',
                'value_options' => array(
                    'F' => '  Feminino   ',
                    'M' => '  Masculino  ',
                ),
            ),
        ));

        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'rua',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputRua',
                'placeholder' => 'Rua',
            ),
        ));
        // elemento do tipo text
        $this->add(array(
            'type' => 'Zend\Form\Element\Number', # ou 'type' => 'ZendFormElementText'
            'name' => 'numero',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputNumeral',
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
            // 'multiple' => 'true'
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
