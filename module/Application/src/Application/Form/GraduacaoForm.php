<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class GraduacaoForm extends Form {

    public function __construct() { 
        parent::__construct("FormGraduacao");
        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', 
            'name' => 'id_grad',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', 
            'name' => 'sigla',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputSigla',
                'placeholder' => 'Sigla',
            ),
        ));
         $this->add(array(
            'type' => 'Text', 
            'name' => 'graduacao',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputGraduacao',
                'placeholder' => 'Graduação',
            ),
        ));
    }

}
