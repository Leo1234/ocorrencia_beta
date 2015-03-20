<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

use Application\Model\Municipio;

class Area implements InputFilterAwareInterface{
    public $id_area;
    public $descricao;
    public $municipio;
    protected $inputFilter;
    
    function __construct($id_area=0, $descricao="", Municipio $m = null) {
        $this->id_area = $id_area;
        $this->descricao = $descricao;
        $this->municipio = $m;
    }
    
    public function exchangeArray($data) {
        $this->id_area      = (!empty($data['id_area'])) ? $data['id_area'] : null;
        $this->descricao    = (!empty($data['descricao'])) ? $data['descricao'] : null;
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
                'name' => 'id_area',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'descricao',
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
    
    public function getId_area() {
        return $this->id_area;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setId_area($id_area) {
        $this->id_area = $id_area;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    } 
}
