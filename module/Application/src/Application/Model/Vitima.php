<?php

namespace Application\Model;

use Application\View\Helper\Util;
use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\I18n\Validator\Int,
    Zend\InputFilter\InputFilterInterface;

class Vitima implements InputFilterAwareInterface {

    public $id_vitima;
    public $nome;
    public $telefone;
    public $data_nasc;
    public $sexo;
    public $rua;
    public $numero;
    public $bairro;
    public $municipio;
    protected $inputFilter;

    public function exchangeArray($data) {

        $this->id_vitima = (!empty($data['id_vitima'])) ? $data['id_vitima'] : null;
        $this->nome = (!empty($data['nome'])) ? $data['nome'] : null;
        $this->telefone = (!empty($data['telefone'])) ? $data['telefone'] : null;
        $this->data_nasc = (!empty($data['data_nasc'])) ? $data['data_nasc'] : null;
        $this->sexo = (!empty($data['sexo'])) ? $data['sexo'] : null;
        $this->rua = (!empty($data['rua'])) ? $data['rua'] : null;
        $this->numero = (!empty($data['numero'])) ? $data['numero'] : null;
        $this->bairro = (!empty($data['id_bai'])) ? new Bairro($data['id_bai'], $data['bairro']) : null;
        $this->municipio = (!empty($data['id_muni'])) ? new Municipio($data['id_muni'], $data['municipio']) : null;
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
                            'min' => 8,
                            'max' => 12,
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

    public function getRua() {
        return $this->rua;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getMunicipio() {
        return $this->municipio;
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
