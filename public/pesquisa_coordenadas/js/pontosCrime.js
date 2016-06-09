var map;
var centerPos = new google.maps.LatLng(-37.8046274, 144.972156);
var zoomLevel = 12;
function initialize() {
    var mapOptions = {
        center: {lat: -3.895794, lng: -38.605375},
        zoom: zoomLevel
    };
    map = new google.maps.Map(document.getElementById("ponto-mapa"), mapOptions);
    document.getElementById('pontos').addEventListener('click', function() {        
        plotapontos();
    });

}

google.maps.event.addDomListener(window, 'load', initialize);



function plotapontos(){

    var waypts = [];
    var waypts_aux = [];
    var checkboxArray = document.getElementById('pontos');
    for (var i = 0; i < checkboxArray.length; i++) {
        if (checkboxArray.options[i].selected) {
            waypts.push(
                checkboxArray[i].value
            );
        }
    }

    for (i = 0; i < waypts.length; i++){
        waypts_aux[i] = waypts[i].split(",");
    }
    
    for (i = 0; i < waypts_aux.length; i++) {
        
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(waypts_aux[i][0],waypts_aux[i][1]),
            map: map
        });
    }
     //alert(waypts_aux[0][1]);
}










