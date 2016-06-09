<?php

namespace Application\Form;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\ElementText;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;
use Application\Model\Viatura;
use Application\Model\ViaturaTable as ModelViatura;
use Application\Model\Municipio;
use Application\Model\MunicipioTable as ModelMunicipio;
use Application\Model\Bairro;
use Application\Model\BairroTable as ModelBairro;
use Application\Model\Policial;
use Application\Model\PolicialTable as ModelPolicial;
use Application\Model\Crime;
use Application\Model\CrimeTable as ModelCrime;
use Application\Model\Procedimento;
use Application\Model\ProcedimentoTable as ModelProcedimento;

class RelatoriosForm extends Form {

    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter) {
        $this->adapter = $dbAdapter;
        //$name = null;

        parent::__construct("FormRelatorios");

        // config form atributes
        $this->setAttributes(array(
            'method' => 'POST',
            'class' => 'form-horizontal',
        ));

 
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_muniO',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'municipioR',
                ' style' => 'width:350px',
            ),
            'options' => array(
                'label' => 'Município',
                'empty_option' => 'Escolha o município',
                'value_options' => $this->getOptionsForSelectM()),
        ));

        
                $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id_crimeR',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'crimeR',
                ' style' => 'width:350px',
                //'multiple' => 'multiple'
            ),
            'options' => array(
                 'disable_inarray_validator' => true,
                'data-placeholder' => 'Escolha o crime',
                //'empty_option' => 'Escolha a viatura',
                'value_options' => $this->getOptionsForSelectC()),
           
          
        ));
        

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'inicio',
            'attributes' => array(
                'class' => 'form-control bairro',
                'id' => 'inicio',
                ' style' => 'width:350px',
            // 'multiple' => 'true'
            ),
            'options' => array(
                'label' => 'Escolha a rua',
                'empty_option' => 'Escolha a rua',
                ),
        ));

  $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'pontos',
            'attributes' => array(
                'class' => 'form-control bairro',
                'id' => 'pontos',
                ' style' => 'width:350px',
                'multiple' => 'true'
            ),
            'options' => array(
                'label' => 'Escolha a rua',
                'empty_option' => 'Escolha a rua',
                ),
        ));

  
          $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'fim',
            'attributes' => array(
                'class' => 'form-control bairro',
                'id' => 'fim',
                ' style' => 'width:350px',
            // 'multiple' => 'true'
            ),
            'options' => array(
                'label' => 'Escolha a rua',
                'empty_option' => 'Escolha a rua',
                ),
        ));
          
        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'datai',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputDataiR',
                'placeholder' => 'Data/hora Início',
            ),
        ));

        $this->add(array(
            'type' => 'Text', # ou 'type' => 'ZendFormElementText'
            'name' => 'dataf',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'inputDatafR',
                'placeholder' => 'Data/hora Fim',
            ),
        ));
    }

    
    public function getOptionsForSelectB() {
        $selectData = $this->getBairroTable()->fetchAll();
        return $selectData;
    }

    private function getBairroTable() {
        $dbAdapter = $this->adapter;
        return new ModelBairro($dbAdapter);
    }

    public function getOptionsForSelectM() {
        $selectData = $this->getMunicipioTable()->fetchAll();
        return $selectData;
    }

    private function getMunicipioTable() {
        $dbAdapter = $this->adapter;
        return new ModelMunicipio($dbAdapter);
    }
   public function getOptionsForSelectC() {
        $selectData = $this->getCrimeTable()->fetchAll();
        return $selectData;
    }

    private function getCrimeTable() {
        $dbAdapter = $this->adapter;
        return new ModelCrime($dbAdapter);
    }
}
