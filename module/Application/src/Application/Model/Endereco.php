<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;
use Application\Model\Bairro;


class Endereco implements InputFilterAwareInterface {

    public $id_end;
    public $rua;
    public $numero;
    public $id_bai;
    public $municipio;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id_end = (!empty($data['id_end'])) ? $data['id_end'] : null;
        $this->rua = (!empty($data['rua'])) ? $data['rua'] : null;
        $this->numero = (!empty($data['numero'])) ? $data['numero'] : null;
        $this->id_bai = (!empty($data['id_bai'])) ? new Bairro($data['id_bai'], $data['bairro']) : null;
        $this->municipio    = (!empty($data['id_muni'])) ? new Municipio($data['id_muni'], $data['municipio']) : null;
    }

    //método da interface InputFilterAwareInterface, n será usado e lança apenas uma exceção
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_end',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'rua',
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
                'name' => 'numero',
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
                'name' => 'bairro',
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

    public function getId_end() {
        return $this->id_end;
    }

    public function getRua() {
        return $this->rua;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getId_bai() {
        return $this->id_bai;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setId_end($id_end) {
        $this->id_end = $id_end;
    }

    public function setRua($rua) {
        $this->rua = $rua;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setId_bai($id_bai) {
        $this->id_bai = $id_bai;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }



}
