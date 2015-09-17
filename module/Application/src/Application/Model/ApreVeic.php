<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class ApreVeic implements InputFilterAwareInterface {

    public $qtdeV;
    public $descricaoV;
    public $id;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->qtdeV = (!empty($data['qtdeV'])) ? $data['qtdeV'] : null;
        $this->descricaoV = (!empty($data['descricaoV'])) ? $data['descricaoV'] : null;
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'qtdeV',
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
                'name' => 'descricaoV',
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
    public function getQtdeV() {
        return $this->qtdeV;
    }

    public function getDescricaoV() {
        return $this->descricaoV;
    }

    public function getId() {
        return $this->id;
    }

    public function setQtdeV($qtdeV) {
        $this->qtdeV = $qtdeV;
    }

    public function setDescricaoV($descricaoV) {
        $this->descricaoV = $descricaoV;
    }

    public function setId($id) {
        $this->id = $id;
    }


}
