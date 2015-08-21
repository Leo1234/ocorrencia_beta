<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Lesao implements InputFilterAwareInterface {

    public $qtdeL;
    public $tipo_les;
    public $id;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->qtdeL = (!empty($data['qtdeL'])) ? $data['qtdeL'] : null;
        $this->tipo_les = (!empty($data['tipo_les'])) ? $data['tipo_les'] : null;
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'qtdeL',
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
                            'min' => 1,
                            'max' => 3,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de dígitos aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de dígitos aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'tipo_les',
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
                            'min' => 3,
                            'max' => 50,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

           


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    public function getQtdeL() {
        return $this->qtdeL;
    }

    public function getTipo_les() {
        return $this->tipo_les;
    }

    public function getId() {
        return $this->id;
    }

    public function setQtdeL($qtdeL) {
        $this->qtdeL = $qtdeL;
    }

    public function setTipo_les($tipo_les) {
        $this->tipo_les = $tipo_les;
    }

    public function setId($id) {
        $this->id = $id;
    }



}
