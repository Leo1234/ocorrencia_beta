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

}
