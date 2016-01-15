<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\RelatoriosForm;

class RelatoriosController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }
    
        public function mapacrimeAction() {

        $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
        $form = new RelatoriosForm($dbAdapter);
        $html = $this->iniciarMapa();

        return new ViewModel(array('map_html' => $html, 'formRelarorio' => $form));

    }

    public function iniciarMapa() {

        $markers = array(
            'Rua Setenta e quatro' => '-3.891161, -38.616232'
        );  //markers location with latitude and longitude

        $config = array(
            'sensor' => 'true', //true or false 
            'div_id' => 'map', //div id of the google map
            'div_class' => 'grid_6', //div class of the google map
            'zoom' => 16, //zoom level
            'width' => "600px", //width of the div
            'height' => "400px", //height of the div
            'lat' => -3.891161, //lattitude
            'lon' => -38.616232, //longitude 
            'draggable' => true,
            'animation' => xx, //animation of the marker
            'markers' => $markers       //loading the array of markers
        );

        $map = $this->getServiceLocator()->get('GMaps\Service\GoogleMap'); //getting the google map object using service manager
        $map->initialize($config);                                         //loading the config   
        $html = $map->generate();                                          //genrating the html map content  
        return $html;
    }
    
       function itinerarioAction() {
       
        $request = $this->getRequest();
        $postData = $request->getPost()->toArray();
        
            $dbAdapter = $this->getServiceLocator()->get('AdapterDb');
            $form = new RelatoriosForm($dbAdapter);
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());
       
        return new ViewModel(array('dados' => $form));  
       /*
       echo 'rrrrrrrrrrrrrr';
       $result = ['datai']; //= json_decode($_POST['datai']);
       // var_dump($result);

        
          $id_muni = $_POST['id_muniO'];
          $crime = $_POST['id_crimeM'];
          $datai = $_POST['datai'];
          $dataf = $_POST['dataf'];



          if (isset($id_muni) && isset($crime) && isset($datai) && isset($dataf)) {
          $result = $this->getOcorrenciaTable()->searchItinerario($id_muni, $crime, $datai, $dataf);
          } else {
          $result = [];
          }
         
        return new \Zend\View\Model\JsonModel($result);*/
    }

}
