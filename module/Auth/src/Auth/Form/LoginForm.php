<?php

namespace Auth\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;


class LoginForm extends Form {

    public function __construct() { 
        parent::__construct("FormLogin");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-signin',
        ));


        // elemento do tipo text
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'login',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'login',
                'placeholder' => 'Usuário',
            ),
        ));
        
        $this->add(array(
        'name' => 'senha',
        'attributes' => array(
            'type'        => 'password',
            'id'          => 'senha',
            'class' => 'form-control',
            'placeholder' => 'Senha'
        ),
        'options' => array(
            'label' => 'Password :'
        )
    ));
    }

}
