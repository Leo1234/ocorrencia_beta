<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Homicidio implements InputFilterAwareInterface {

    public $qtde;
    public $tipo_homi;
    public $presidio;
    public $id_ocorrencia;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->qtde = (!empty($data['qtde'])) ? $data['qtde'] : null;
        $this->tipo_homi = (!empty($data['tipo_homi'])) ? $data['tipo_homi'] : null;
        $this->presidio = (!empty($data['presidio'])) ? $data['presidio'] : null;
        $this->id_ocorrencia = (!empty($data['id_ocorrencia'])) ? $data['id_ocorrencia'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'qtde',
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
                'name' => 'tipo_homi',
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
    public function getQtde() {
        return $this->qtde;
    }

    public function getTipo_homi() {
        return $this->tipo_homi;
    }

    public function getPresidio() {
        return $this->presidio;
    }

    public function getId_ocorrencia() {
        return $this->id_ocorrencia;
    }

    public function setQtde($qtde) {
        $this->qtde = $qtde;
    }

    public function setTipo_homi($tipo_homi) {
        $this->tipo_homi = $tipo_homi;
    }

    public function setPresidio($presidio) {
        $this->presidio = $presidio;
    }

    public function setId_ocorrencia($id_ocorrencia) {
        $this->id_ocorrencia = $id_ocorrencia;
    }



}
