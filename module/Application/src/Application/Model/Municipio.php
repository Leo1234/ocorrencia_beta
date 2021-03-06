<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Municipio implements InputFilterAwareInterface {

   public $id_muni;
   public $municipio;
    protected $inputFilter;

    function __construct($id_muni=0, $muni="") {
        $this->id_muni = $id_muni;
        $this->municipio = $muni;
    }
    
    public function exchangeArray($data) {
        $this->id_muni = (!empty($data['id_muni'])) ? $data['id_muni'] : null;
        $this->municipio = (!empty($data['municipio'])) ? $data['municipio'] : null;
    }
     public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_muni',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'municipio',
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
    
    
    public function getId_muni() {
        return $this->id_muni;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setId_muni($id_muni) {
        $this->id_muni = $id_muni;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }


}
