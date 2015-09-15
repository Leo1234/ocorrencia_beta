<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class ApreArma implements InputFilterAwareInterface {

    public $qtdeA;
    public $descricaoA;
    public $id;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->qtdeA = (!empty($data['qtdeA'])) ? $data['qtdeA'] : null;
        $this->descricaoA = (!empty($data['descricaoA'])) ? $data['descricaoA'] : null;
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'qtdeA',
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
                    array(
                        'name' => 'GreaterThan',
                        'options' => array(
                            'min' => 0,
                            'messages' => array(
                                \Zend\Validator\GreaterThan::NOT_GREATER => 'A entrada não é maior do que  %min%.',
                            ),
                        ),
                    ),
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'descricaoA',
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
    public function getQtdeA() {
        return $this->qtdeA;
    }

    public function getDescricaoA() {
        return $this->descricaoA;
    }

    public function getId() {
        return $this->id;
    }

    public function setQtdeA($qtdeA) {
        $this->qtdeA = $qtdeA;
    }

    public function setDescricaoA($descricaoA) {
        $this->descricaoA = $descricaoA;
    }

    public function setId($id) {
        $this->id = $id;
    }




}
