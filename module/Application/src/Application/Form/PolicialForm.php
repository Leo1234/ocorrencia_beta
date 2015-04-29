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
use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\View\Helper\FormRadio;
use Zend \ Form \ Elemento \ Radio;
use Zend\Db\Adapter\Adapter;
use Application\Model\Policial;
use Application\Model\PolicialTable as ModelPolicial;
use Application\Model\Municpio;
use Application\Model\GraduacaoTable as ModelGraduacao;

class PolicialForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormPolicial");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_policial',
        ));


        $this->add(array(
            'type' => 'Zend\Form\Element\Select', # ou 'type' => 'ZendFormElementText'
            'name' => 'id_grad',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'id_grad',
            ),
            'options' => array(
                'label' => 'Escolha uma graduação',
                'empty_option' => 'Por favor, escolha uma graduação',
                'value_options' => $this->getOptionsForSelect()),
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Zend\Form\Element\Number', # ou 'type' => 'ZendFormElementText'
            'name' => 'numeral',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputNumeral',
                'placeholder' => 'Numeral',
            ),
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
            'name' => 'nome_guerra',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputNome_guerra',
                'placeholder' => 'Nome de guerra',
            ),
        ));
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'matricula',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputMatricula',
                'placeholder' => 'Matrícula',
            ),
        ));



        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'data_nasc',
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



        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'data_inclu',
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
    }

    public function getOptionsForSelect() {
        $selectData = $this->getGraduacaoTable()->fetchAll();
        return $selectData;
    }

    private function getGraduacaoTable() {
        $dbAdapter = $this->adapter;
        return new ModelGraduacao($dbAdapter);
    }

}
