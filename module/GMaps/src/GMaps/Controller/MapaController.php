<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class MapaController extends AbstractActionController {
    
   public function mapaAction() {
     $markers = array(
        'Mozzat Web Team' => '17.516684,79.961589',
        'Home Town' => '16.916684,80.683594'
    );  //markers location with latitude and longitude

    $config = array(
        //'api_key' => 'AIzaSyDjwOd5a09zESk19OrFer6PjN72iiSgUyg',
        'sensor' => 'true',         //true or false
        'div_id' => 'map',          //div id of the google map
        'div_class' => 'grid_6',    //div class of the google map
        'zoom' => 5,                //zoom level
        'width' => "600px",         //width of the div
        'height' => "300px",        //height of the div
        'lat' => 16.916684,         //lattitude
        'lon' => 80.683594,         //longitude 
        'animation' => 'none',      //animation of the marker
        'markers' => $markers       //loading the array of markers
    );

    $map = $this->getServiceLocator()->get('GMaps\Service\GoogleMap'); //getting the google map object using service manager
    $map->initialize($config);                                         //loading the config   
    $html = $map->generate();                                          //genrating the html map content  
    return new ViewModel(array('map_html' => $html));
   }
    
}
