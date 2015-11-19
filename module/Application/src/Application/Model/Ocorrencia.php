<?php

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\I18n\Validator\Int;
use Application\Model\Endereco;
use Application\Model\Bairro;
use Application\Model\Municipio;
use Application\Model\Ocorrencia_crime;

class Ocorrencia implements InputFilterAwareInterface {

    public $id_ocorrencia;
    public $end;
    public $vtr;
    public $ciops;
    public $datai;
    public $dataf;
    public $narracao;
    public $usuario = 1;
   // public $policiais = null;
   //public $crimes = null;
   // public $procedimentos = null;
    protected $inputFilter;
    
    public function ocorrencia($data) {
        $this->id_ocorrencia = (!empty($data['id_ocorrencia'])) ? $data['id_ocorrencia'] : null;
        $this->end = (!empty($data['id_end'])) ? $data['id_end'] : null;
        $this->vtr = (!empty($data['id_vtr'])) ? new Viatura($data['id_vtr'], "0") : null;
        $this->ciops = (!empty($data['ciops'])) ? $data['ciops'] : null;
        $this->datai = (!empty($data['datai'])) ? $data['datai'] : null;
        $this->dataf = (!empty($data['dataf'])) ? $data['dataf'] : null;
        //$this->$policiais = (!empty($data['id_composicao'])) ? $data['id_composicao'] : null;
        //$this->$crimes  = (!empty($data['id_ocorrenciaC'])) ? new Ocorrencia_crime($data['id_ocorrenciaC'], $data['id_crime']) : null;
        //$this->$procedimentos  = (!empty($data['procedimento'])) ? $data['procedimento'] : null;
        $this->narracao = (!empty($data['narracao'])) ? $data['narracao'] : null;
    }

    public function exchangeArray($data) {

        $this->id_ocorrencia = (!empty($data['id_ocorrencia'])) ? $data['id_ocorrencia'] : null;
        $this->end = (!empty($data['id_end'])) ? new Endereco($data['id_end'], $data['rua'], $data['numero'], $data['lat'] , $data['lng'], new Bairro($data['id_bai'], $data['bairro'], new Municipio($data['id_muni'], $data['municipio']))) : null;
        $this->vtr = (!empty($data['id_vtr'])) ? new Viatura($data['id_vtr'], $data['prefixo']) : null;
        $this->ciops = (!empty($data['ciops'])) ? $data['ciops'] : null;
        $this->datai = (!empty($data['datai'])) ? $data['datai'] : null;
        $this->dataf = (!empty($data['dataf'])) ? $data['dataf'] : null;
        //$this->$policiais = (!empty($data['id_composicao'])) ? $data['id_composicao'] : null;
        //$this->$crimes  = (!empty($data['id_ocorrenciaC'])) ? new Ocorrencia_crime($data['id_ocorrenciaC'], $data['id_crime']) : null;
        //$this->$procedimentos  = (!empty($data['procedimento'])) ? $data['procedimento'] : null;
        $this->narracao = (!empty($data['narracao'])) ? $data['narracao'] : null;
    }

    //método da interface InputFilterAwareInterface, n será usado e lança apenas uma exceção
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id_ocorrencia',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
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
                'name' => 'ciops',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
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
                'name' => 'datai',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'dataf',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'narracao',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
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
                            'max' => 1000,
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
   
    public function getId_oco() {
        return $this->id_ocorrencia;
    }

    public function getEnd() {
        return $this->end;
    }

    public function getVtr() {
        return $this->vtr;
    }

    public function getCiops() {
        return $this->ciops;
    }

    public function getDatai() {
        return $this->datai;
    }

    public function getDataf() {
        return $this->dataf;
    }

    public function getNarracao() {
        return $this->narracao;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPoliciais() {
        return $this->policiais;
    }

    public function getCrimes() {
        return $this->crimes;
    }

    public function getProcedimentos() {
        return $this->procedimentos;
    }

    public function setId_ocorrencia($id_ocorrencia) {
        $this->id_ocorrencia = $id_ocorrencia;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

    public function setVtr($vtr) {
        $this->vtr = $vtr;
    }

    public function setCiops($ciops) {
        $this->ciops = $ciops;
    }

    public function setDatai($datai) {
        $this->datai = $datai;
    }

    public function setDataf($dataf) {
        $this->dataf = $dataf;
    }

    public function setNarracao($narracao) {
        $this->narracao = $narracao;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setPoliciais($policiais) {
        $this->policiais = $policiais;
    }

    public function setCrimes($crimes) {
        $this->crimes = $crimes;
    }

    public function setProcedimentos($procedimentos) {
        $this->procedimentos = $procedimentos;
    }



}
