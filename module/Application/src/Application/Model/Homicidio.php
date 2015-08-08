<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Homicidio implements InputFilterAwareInterface {

    public $qtd;
    public $tipo;
    public $presidio;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->qtd = (!empty($data['qtd'])) ? $data['qtd'] : null;
        $this->tipo = (!empty($data['tipo'])) ? $data['tipo'] : null;
        $this->presidio = (!empty($data['presidio'])) ? $data['presidio'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'qtd',
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
                'name' => 'tipo',
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

            $inputFilter->add(array(
                'name' => 'presidio',
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
                ),
            ));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getQtd() {
        return $this->qtd;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getPresidio() {
        return $this->presidio;
    }

    public function setQtd($qtd) {
        $this->qtd = $qtd;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setPresidio($presidio) {
        $this->presidio = $presidio;
    }

}
