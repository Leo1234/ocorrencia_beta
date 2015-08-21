<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class LesaoForm extends Form {

    public function __construct() { 
        parent::__construct("FormLesao");

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
            'name' => 'qtdeL',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'qtdeL',
                'placeholder' => 'Quantidade de lesionados',
            ),
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'tipo_les',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'tipoLesao',
                'placeholder' => 'Tipo de lesão',
            ),
        ));     
    }

}
