<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class ApreVeicForm extends Form {

    public function __construct() { 
        parent::__construct("FormApreVeic");

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
            'name' => 'qtdeV',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'qtdeV',
                'placeholder' => 'Quantidade de veículos',
            ),
        ));

           $this->add(array(
            'name' => 'descricaoV',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Descrição',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'qtdeV',
                'placeholder' => 'Descrição',
            )
        ));
    }

}
