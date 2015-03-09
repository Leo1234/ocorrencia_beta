<?php

/**
 * namespace de localizacao do nosso formulario
 */
namespace Application\Form;
// import Form
use Zend\Form\Form;
// import Element
use Zend\Form\Element;
use Zend\Form\ElementText;

class ViaturaForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        
        // config form atributes
        $this->setAttributes(array(
            'method'    => 'POST',
            'class'     => 'form-horizontal',
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
                'class'         => 'form-control',
                'id'            => 'inputPrefixo',
                'placeholder'   => 'Prefixo',
            ),
        ));
        
            $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'id_area',
            'attributes' => array(
                'class'         => 'form-control',
                'id'            => 'inputPrefixo',
                'placeholder'   => 'Id Área',
            ),
        ));                     
    }
}
