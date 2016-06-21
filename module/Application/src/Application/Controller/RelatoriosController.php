<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\RelatoriosForm;
use Application\Model\Ocorrencia;
use Application\Model\OcorrenciaTable as ModelOcorrencia;

class RelatoriosController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }
  
      private function getOcorrenciaTable() {
        $adapter = $this->getServiceLocator()->get('AdapterDb');
        return new ModelOcorrencia($adapter);
    }
    
        public function mapacrimeAction() {

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new RelatoriosForm($dbAdapter);
        $html = $this->iniciarMapa();
        return new ViewModel(array('map_html' => $html, 'formRelarorio' => $form));

    }

    
    function itinerarioAction(){

        $id_muniO = $_POST['id_muniO'];
        $id_crimeM = $_POST['id_crimeM'];
        $datai = $_POST['datai'];
        $dataf = $_POST['dataf'];

        if (isset($_POST['id_muniO'])) {
            $result = (array) $this->getOcorrenciaTable()->searchItinerario($id_muniO, $id_crimeM, $datai, $dataf);
        } else {
            $result = [];
        }

        return new \Zend\View\Model\JsonModel($result);
    }
    

}
