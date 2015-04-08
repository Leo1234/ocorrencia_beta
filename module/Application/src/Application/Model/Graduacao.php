<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Graduacao implements InputFilterAwareInterface{
    public $id_grad;
    public $sigla;
    public $graduacao;
    protected $inputFilter;

    function __construct($id_grad=0, $graduacao=null,$sigla=null) {
        $this->id_grad = $id_grad;
        $this->sigla = $sigla;
        $this->graduacao = $graduacao;
    }
    
    public function exchangeArray($data) {
        $this->id_grad = (!empty($data['id_grad'])) ? $data['id_grad'] : null;
        $this->sigla = (!empty($data['sigla'])) ? $data['sigla'] : null;
        $this->graduacao = (!empty($data['graduacao'])) ? $data['graduacao'] : null;
       
    }
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_grad',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'sigla',
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
                            'min' => 2,
                            'max' => 10,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));
             $inputFilter->add(array(
                'name' => 'graduacao',
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
                            'max' => 20,
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
   
    public function getId_grad() {
        return $this->id_grad;
    }

    public function getSigla() {
        return $this->sigla;
    }

    public function getGraduacao() {
        return $this->graduacao;
    }

    public function setId_grad($id_grad) {
        $this->id_grad = $id_grad;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    public function setGraduacao($graduacao) {
        $this->graduacao = $graduacao;
    }


}
