<?php

namespace Application\Model;

use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;

class Crime implements InputFilterAwareInterface {

   public $id_cri;
   public $crime;
   protected $inputFilter;

    function __construct($id_cri=0, $crime="") {
        $this->id_cri = $id_cri;
        $this->crime = $crime;
    }
    
    public function exchangeArray($data) {
        $this->id_cri = (!empty($data['id_cri'])) ? $data['id_cri'] : null;
        $this->crime = (!empty($data['crime'])) ? $data['crime'] : null;
    }
     public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_cri',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            // input filter para campo de nome  
            $inputFilter->add(array(
                'name' => 'crime',
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
    
    
    public function getId_cri() {
        return $this->id_cri;
    }

    public function getCrime() {
        return $this->crime;
    }

    public function setId_cri($id_cri) {
        $this->id_cri = $id_cri;
    }

    public function setCrime($crime) {
        $this->crime = $crime;
    }


}
