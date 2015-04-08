<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Procedimento implements InputFilterAwareInterface{
    public $id_pro;
    public $procedimento;
    public $peso;
    protected $inputFilter;

    function __construct($id_pro = 0, $procedimento = null,$peso = 0) {
        $this->id_pro = $id_pro;
        $this->procedimento = $procedimento;
        $this->peso= $peso;
    }
    
    public function exchangeArray($data) {
        $this->id_pro = (!empty($data['id_pro'])) ? $data['id_pro'] : null;
        $this->procedimento = (!empty($data['procedimento'])) ? $data['procedimento'] : null;
        $this->peso = (!empty($data['peso'])) ? $data['peso'] : null;
       
    }
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_pro',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'procedimento',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # remove xml e html da string
                    array('name' => 'StringTrim'), # remove espacos do início e do final da string
                    //array('name' => 'StringToUpper'), # transofrma string para maiusculo
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Campo obrigatório.'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 2,
                            'max' => 10,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'peso',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    public function getId_pro() {
        return $this->id_pro;
    }

    public function getProcedimento() {
        return $this->procedimento;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function setId_pro($id_pro) {
        $this->id_pro = $id_pro;
    }

    public function setProcedimento($procedimento) {
        $this->procedimento = $procedimento;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }


   

}
