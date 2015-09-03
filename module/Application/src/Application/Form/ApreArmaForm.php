<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class ApreArmaForm extends Form {

    public function __construct() { 
        parent::__construct("FormApreArma");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));
           $this->add(array(
            'type' => 'Hidden', # ou 'type' => 'ZendFormElementHidden'
            'name' => 'id',
        ));

         $this->add(array(
            'type' => 'Zend\Form\Element\Number', # ou 'type' => 'ZendFormElementText'
            'name' => 'qtdeA',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'qtdeA',
                'placeholder' => 'Quantidade de armas',
            ),
        ));

           $this->add(array(
            'name' => 'descricaoA',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Descrição',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'descricaoA',
                'placeholder' => 'Descrição do armamento',
            )
        ));
    }

}
