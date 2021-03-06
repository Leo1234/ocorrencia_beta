<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class ArmaForm extends Form {

    public function __construct() { 
        parent::__construct("FormArma");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

        // elemento do tipo hidden
        $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id_arma',
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'arma',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputArma',
                'placeholder' => 'Arma',
            ),
        ));
    }

}
