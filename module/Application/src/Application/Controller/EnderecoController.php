<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Model\Area;
use Application\Form\AreaForm;
use Application\Model\AreaTable as ModelArea;

use Application\Model\Bairro;
use Application\Form\BairroForm;
use Application\Model\BairroTable as BairroArea;

use Application\Model\Viatura;
use Application\Form\ViaturaForm;
use Application\Model\ViaturaTable as ModelViatura;

use Application\Model\Municipio;
use Application\Form\MunicipioForm;
use Application\Model\MunicipioTable as ModelMunicipio;

use Application\Model\Endereco;
use Application\Model\EnderecoTable as ModelEndereco;

use Application\Model\BairroTable as ModelBairro;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

;

class EnderecoController extends AbstractActionController {
    
    
   private function getEnderecoTable() {
        $dbAdapter =  $this->getServiceLocator()->get('AdapterDb');
        return new ModelEndereco($dbAdapter);
    }
      
    public function indexAction() {
      
        $paramsUrl = [
            'pagina_atual' => $this->params()->fromQuery('pagina', 1),
            'itens_pagina' => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_end' => $this->params()->fromQuery('coluna_end', 'id_end'),
            'coluna_sort' => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search' => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getEnderecoTable()->fetchPaginator(
                /* $pagina */ $paramsUrl['pagina_atual'],
                /* $itensPagina */ $paramsUrl['itens_pagina'],
                /* $ordem */ "{$paramsUrl['coluna_end']} {$paramsUrl['coluna_sort']}",
                /* $search */ $paramsUrl['search'],
                /* $itensPaginacao */ 5
        );
                 
        // retonar paginação mais os params de url para view
        return new ViewModel(['endereco' => $paginacao] + $paramsUrl  );
    }
}
