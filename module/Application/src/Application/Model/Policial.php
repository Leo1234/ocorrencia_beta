<?php

namespace Application\Model;

use Application\Model\Graduacao;
use Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilter,
    Zend\I18n\Validator\Int,
    Zend\InputFilter\InputFilterInterface;

class Policial implements InputFilterAwareInterface {

    public $id_policial;
    public $graduacao;
    public $numeral;
    public $nome;
    public $nome_guerra;
    public $matricula;
    public $data_nasc;
    public $data_inclu;
    public $sexo;
    protected $inputFilter;

    public function exchangeArray($data) {

        $this->id_policial = (!empty($data['id_policial'])) ? $data['id_policial'] : null;
        $this->graduacao = (!empty($data['id_grad'])) ? new Graduacao($data['id_grad'], $data['graduacao']) : null;
        $this->numeral = (!empty($data['numeral'])) ? $data['numeral'] : null;
        $this->nome = (!empty($data['nome'])) ? $data['nome'] : null;
        $this->nome_guerra = (!empty($data['nome_guerra'])) ? $data['nome_guerra'] : null;
        $this->matricula = (!empty($data['matricula'])) ? $data['matricula'] : null;
        $this->data_nasc = (!empty($data['data_nasc'])) ? $data['data_nasc'] : null;
        $this->data_inclu = (!empty($data['data_inclu'])) ? $data['data_inclu'] : null;
        $this->sexo = (!empty($data['sexo'])) ? $data['sexo'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new Exception('Não utilizado.');
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            // input filter para campo de id
            $inputFilter->add(array(
                'name' => 'id_policial',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'), # transforma string para inteiro
                ),
            ));
      $inputFilter->add(array(
                'name' => 'numeral',
                'required' => true,
              
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
                            'max' => 5,
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_SHORT => 'Mínimo de dígitos aceitáveis %min%.',
                                \Zend\Validator\StringLength::TOO_LONG => 'Máximo de dígitos aceitáveis %max%.',
                            ),
                        ),
                    ),
                     array(
                        'name' => 'GreaterThan',
                        'options' => array(
                            'min' => 0,
                            'messages' => array(
                                \Zend\Validator\GreaterThan::NOT_GREATER => 'A entrada não é maior do que  %min%.',
                            ),
                        ),
                    ),
                ),
            ));
            // input filter para campo de nome  
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
                'name' => 'nome_guerra',
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
                'name' => 'matricula',
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
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 16,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'data_inclu',
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
                            'max' => 16,
                        ),
                    ),
                ),
            ));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getId_policial() {
        return $this->id_policial;
    }

    public function getGraduacao() {
        return $this->graduacao;
    }

    public function getNumeral() {
        return $this->numeral;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getNome_guerra() {
        return $this->nome_guerra;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getData_nasc() {
        return $this->data_nasc;
    }

    public function getData_inclu() {
        return $this->data_inclu;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setId_policial($id_policial) {
        $this->id_policial = $id_policial;
    }

    public function setGraduacao($graduacao) {
        $this->graduacao = $graduacao;
    }

    public function setNumeral($numeral) {
        $this->numeral = $numeral;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setNome_guerra($nome_guerra) {
        $this->nome_guerra = $nome_guerra;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function setData_nasc($data_nasc) {
        $this->data_nasc = $data_nasc;
    }

    public function setData_inclu($data_inclu) {
        $this->data_inclu = $data_inclu;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

}
