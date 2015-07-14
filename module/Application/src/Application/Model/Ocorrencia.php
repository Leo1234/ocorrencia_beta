<?php

namespace Application\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\I18n\Validator\Int;
use Application\Model\Endereco;
use Application\Model\Bairro;
use Application\Model\Municipio;

class Ocorrencia implements InputFilterAwareInterface {

    public $id_ocorrencia;
    public $end;
    public $vtr;
    public $ciops;
    public $datai;
    public $dataf;
    public $narracao;
    public $usuario = 1;
    protected $inputFilter;

    public function exchangeArray($data) {

        $this->id_ocorrencia = (!empty($data['id_ocorrencia'])) ? $data['id_ocorrencia'] : null;
        $this->end = (!empty($data['id_end'])) ? new Endereco($data['id_end'], $data['rua'], $data['numero'], new Bairro($data['id_bai'], $data['bairro'], new Municipio($data['id_muni'], $data['municipio']))) : null;
        $this->vtr = (!empty($data['id_vtr'])) ? new Viatura($data['id_vtr'], $data['prefixo']) : null;
        $this->ciops = (!empty($data['ciops'])) ? $data['ciops'] : null;
        $this->datai = (!empty($data['datai'])) ? $data['datai'] : null;
        $this->dataf = (!empty($data['dataf'])) ? $data['dataf'] : null;
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
                'name' => 'end',
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
                'name' => 'ciops',
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
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
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

    public function setId_oco($id_ocorrencia) {
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


}
