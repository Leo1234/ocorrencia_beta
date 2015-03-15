<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

use Application\Model\Area;

class Viatura implements InputFilterAwareInterface {

    public $id_vtr;
    public $prefixo;
    public $area;
    protected $inputFilter;

    function __construct($id_vtr = 0, $prefixo = null, $area = null) {
        $this->id_vtr = $id_vtr;
        $this->prefixo = $prefixo;
        $this->area = $area;
    }

    public function exchangeArray($data) {
        $this->id_vtr = (!empty($data['id_vtr'])) ? $data['id_vtr'] : null;
        $this->prefixo = (!empty($data['prefixo'])) ? $data['prefixo'] : null;
      // $this->id_area = (!empty($data['id_area'])) ? $data['id_area'] : null;
        $this->area = (!empty($data['id_area'])) ? new Area($data['id_area'], $data['descricao']) : null;
        //$this->area      = (!empty($data['descricao'])) ? new Area($data['id_area'], $data['descricao'], new Municipio($data['id_vtr'],$data['municipio'])) : null;
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
                'name' => 'id_vtr',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'prefixo',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # remove xml e html da string
                    array('name' => 'StringTrim'), # remove espacos do início e do final da string
                    array('name' => 'StringToUpper'), # transofrma string para maiusculo
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
                            'min' => 4,
                            'max' => 6,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

            // input filter para campo de telefone principal
           // $inputFilter->add(array(
              //  'name' => 'area',
             //   'required' => true,
            //));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getId_vtr() {
        return $this->id_vtr;
    }

    public function getPrefixo() {
        return $this->prefixo;
    }

    public function getArea() {
        return $this->area;
    }

    public function setId_vtr($id_vtr) {
        $this->id_vtr = $id_vtr;
    }

    public function setPrefixo($prefixo) {
        $this->prefixo = $prefixo;
    }

    public function setArea($area) {
        $this->area = $area;
    }
}
