<?php

namespace Application\Model;

use Application\View\Helper\Util;
use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\I18n\Validator\Int,
    Zend\InputFilter\InputFilterInterface;
use Application\Model\Endereco;
use Application\Model\Bairro;
use Application\Model\Municipio;

class Vitima implements InputFilterAwareInterface {

    public $id_vitima;
    public $nome;
    public $telefone;
    public $data_nasc;
    public $sexo;
    public $end;
    protected $inputFilter;
    
    public function vitima($data) {

        $this->id_vitima = (!empty($data['id_vitima'])) ? $data['id_vitima'] : null;
        $this->nome = (!empty($data['nome'])) ? $data['nome'] : null;
        $this->telefone = (!empty($data['telefone'])) ? $data['telefone'] : null;
        $this->data_nasc = (!empty($data['data_nasc'])) ? $data['data_nasc'] : null;
        $this->sexo = (!empty($data['sexo'])) ? $data['sexo'] : null;
        $this->end = (!empty($data['id_end'])) ? $data['id_end'] : null;
    }
    public function exchangeArray($data) {

        $this->id_vitima = (!empty($data['id_vitima'])) ? $data['id_vitima'] : null;
        $this->nome = (!empty($data['nome'])) ? $data['nome'] : null;
        $this->telefone = (!empty($data['telefone'])) ? $data['telefone'] : null;
        $this->data_nasc = (!empty($data['data_nasc'])) ? $data['data_nasc'] : null;
        $this->sexo = (!empty($data['sexo'])) ? $data['sexo'] : null;
        $this->end = (!empty($data['id_end'])) ? new Endereco($data['id_end'], $data['rua'], $data['numero'], new Bairro($data['id_bai'], $data['bairro'], new Municipio($data['id_muni'], $data['municipio']))) : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_vitima',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));

            $inputFilter->add(array(
                'name' => 'rua',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'), # remove xml e html da string
                    array('name' => 'StringTrim'), # remove espacos do início e do final da string           
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

            $inputFilter->add(array(
                'name' => 'nome',
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
                'name' => 'telefone',
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
                            'min' => 9,
                            'max' => 12,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));


            $inputFilter->add(array(
                'name' => 'data_nasc',
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
                            'min' => 8,
                            'max' => 19,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de caracteres aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de caracteres aceitáveis %max%.',
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'sexo',
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

    public function getId_vitima() {
        return $this->id_vitima;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getData_nasc() {
        return $this->data_nasc;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getEnd() {
        return $this->end;
    }

    public function setId_vitima($id_vitima) {
        $this->id_vitima = $id_vitima;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function setData_nasc($data_nasc) {
        $this->data_nasc = $data_nasc;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

}
