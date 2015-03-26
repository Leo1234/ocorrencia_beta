<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

use Application\Model\Municipio;
use Application\Model\Area;

class Bairro implements InputFilterAwareInterface{
    public $id_bai;
    public $bairro;
    public $municipio;
     public $area;
    protected $inputFilter;
    
    function __construct($id_bai=0, $bairro="", Area  $a = null ,Municipio $m = null) {
        $this->id_bai = $id_bai;
        $this->bairro = $bairro;
        $this->area = $m;
        $this->municipio = $m;
    }
    
    public function exchangeArray($data) {
        $this->id_bai      = (!empty($data['id_area'])) ? $data['id_bai'] : null;
        $this->bairro    = (!empty($data['bairro'])) ? $data['bairro'] : null;
        $this->area    = (!empty($data['id_area'])) ? new Area($data['id_area'], $data['descricao']) : null;
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
                'name' => 'id_bai',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
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
    public function getId_bai() {
        return $this->id_bai;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function getArea() {
        return $this->area;
    }

    public function setId_bai($id_bai) {
        $this->id_bai = $id_bai;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    public function setArea($area) {
        $this->area = $area;
    }
}
