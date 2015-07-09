<?php

namespace Application\Model;

use Application\View\Helper\Util;
use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterInterface;
use Application\Model\Endereco;
use Application\Model\Bairro;
use Application\Model\Municipio;
use Application\Model\Viatura;
use Application\Model\Area;
use Application\Model\Policial;
use Application\Model\Vitima;
use Application\Model\Crime;


 
use Zend\Validator;

class Ocorrencia implements InputFilterAwareInterface {

    public $id_oco;
    public $end;
    public $vtr;
    public $composicao = array();
    public $crimes = array();
    public $procedimentos = array();
    public $ciops;
    public $datai;
    public $dataf;
    public $narracao;
    //private $id_usuario;
    public $inputFilter;

    public function Ocorrencia($data) {

        $this->id_oco = (!empty($data['id_oco'])) ? $data['id_oco'] : null;
        $this->end = (!empty($data['id_end'])) ? $data['id_end'] : null;
        $this->vtr = (!empty($data['id_vtr'])) ? new Viatura($data['id_vtr'], $data['prefixo']) : null;
        $this->composicao = (!empty($data['id_composicao'])) ? $data['id_composicao'] : null;
        $this->crimes = (!empty($data['id_crime'])) ? $data['id_crime'] : null;
        $this->procedimentos = (!empty($data['procedimento'])) ? $data['procedimento'] : null;
        $this->ciops = (!empty($data['ciops'])) ? $data['ciops'] : null;
        $this->datai = (!empty($data['datai'])) ? $data['datai'] : null;
        $this->dataf = (!empty($data['dataf'])) ? $data['dataf'] : null;
        $this->narracao = (!empty($data['narracao'])) ? $data['narracao'] : null;
        //$this->id_usuario = (!empty($data['id_usuario'])) ? $data['id_usuario'] : null;
    }

    public function exchangeArray($data) {

        $this->id_oco = (!empty($data['id_oco'])) ? $data['id_oco'] : null;
        $this->end = (!empty($data['id_end'])) ? new Endereco($data['id_end'], $data['rua'], $data['numero'], new Bairro($data['id_bai'], $data['bairro'], new Municipio($data['id_muni'], $data['municipio']))) : null;
        $this->vtr = (!empty($data['id_vtr'])) ? new Viatura($data['id_vtr'], $data['prefixo']) : null;
        $this->composicao = (!empty($data['id_composicao'])) ? $data['id_composicao'] : null;
        $this->crimes = (!empty($data['id_crime'])) ? $data['id_crime'] : null;
        $this->procedimentos = (!empty($data['procedimento'])) ? $data['procedimento'] : null;
        $this->ciops = (!empty($data['ciops'])) ? $data['ciops'] : null;
        $this->datai = (!empty($data['datai'])) ? $data['datai'] : null;
        $this->dataf = (!empty($data['dataf'])) ? $data['dataf'] : null;
        $this->narracao = (!empty($data['narracao'])) ? $data['narracao'] : null;
        //$this->id_usuario = (!empty($data['id_usuario'])) ? $data['id_usuario'] : null;
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
                'name' => 'id_oco',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));



             $inputFilter->add(array(
                'name' => 'composicao',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'InArray',
                        'breakChainOnFailure' => true,
                        'options' => array(
                            'haystack' => array('1', '2'),
                            'messages' => array(
                                Validator\InArray::NOT_IN_ARRAY => 'Invalid option supplied for offering.'
                            )
                        )
                    )
                )
            ));
             
             $inputFilter->add(array(
                'name' => 'crimes',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'InArray',
                        'breakChainOnFailure' => true,
                        'options' => array(
                            'haystack' => array('1', '2'),
                            'messages' => array(
                                Validator\InArray::NOT_IN_ARRAY => 'Invalid option supplied for offering.'
                            )
                        )
                    )
                )
            ));
             $inputFilter->add(array(
                'name' => 'procedimentos',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'InArray',
                        'breakChainOnFailure' => true,
                        'options' => array(
                           'haystack' => array('1', '2'),
                            'messages' => array(
                                Validator\InArray::NOT_IN_ARRAY => 'Invalid option supplied for offering.'
                            )
                        )
                    )
                )
            ));
            $inputFilter->add(array(
                'name' => 'vtr',
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
            /*
              $inputFilter->add(array(
              'name' => 'area',
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
             */


            $inputFilter->add(array(
                'name' => 'datai',
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
                'name' => 'dataf',
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
            /*
              $inputFilter->add(array(
              'name' => 'horario',
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
             */


            $inputFilter->add(array(
                'name' => 'narracao',
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
                            'min' => 1,
                            'max' => 2000,
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
        return $this->id_oco;
    }

    public function getEnd() {
        return $this->end;
    }

    public function getVtr() {
        return $this->vtr;
    }

    public function getComposicao() {
        return $this->composicao;
    }

    public function getCrimes() {
        return $this->crimes;
    }

    public function getProcedimentos() {
        return $this->procedimentos;
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

    public function setId_oco($id_oco) {
        $this->id_oco = $id_oco;
    }

    public function setEnd($end) {
        $this->end = $end;
    }

    public function setVtr($vtr) {
        $this->vtr = $vtr;
    }

    public function setComposicao($composicao) {
        $this->composicao = $composicao;
    }

    public function setCrimes($crimes) {
        $this->crimes = $crimes;
    }

    public function setProcedimentos($procedimentos) {
        $this->procedimentos = $procedimentos;
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

}
