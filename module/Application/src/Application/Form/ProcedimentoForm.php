<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class ProcedimentoForm extends Form {

    public function __construct() { 
        parent::__construct("FormProcedimento");
        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', 
            'name' => 'id_pro',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', 
            'name' => 'procedimento',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputProcedimento',
                'placeholder' => 'Procedimento',
            ),
        ));
            $this->add(array(
            'type' => 'Zend\Form\Element\Number', # ou 'type' => 'ZendFormElementText'
            'name' => 'peso',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'qtdeA',
                'placeholder' => 'Peso do procedimento',
            ),
        ));
    }

}
