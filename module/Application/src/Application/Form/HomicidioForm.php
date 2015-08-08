<?php

namespace Application\Form;

use Application\Controller\AreaController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class HomicidioForm extends Form {

    public function __construct() { 
        parent::__construct("FormHomicidio");

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
            'name' => 'qtd',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'qtd',
                'placeholder' => 'Quantidade de assassinatos',
            ),
        ));

        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'tipo',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'tipoHomicidio',
                'placeholder' => 'Tipo de Homicídio',
            ),
        ));
        
        
         $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'presidio',
            'attributes' => array(
                'id' => 'presidio',
            ),
            'options' => array(
                'label' => 'Foi no presídio?',
                'value_options' => array(
                    'F' => '  sim   ',
                    'M' => '  não ',
                ),
            ),
        ));
        
    }

}
