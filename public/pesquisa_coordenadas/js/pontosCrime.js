var map;
var centerPos = new google.maps.LatLng(-37.8046274, 144.972156);
var zoomLevel = 12;
var markers_inst = [];
function initialize() {
    var mapOptions = {
        center: {lat: -3.895794, lng: -38.605375},
        zoom: zoomLevel
    };
    map = new google.maps.Map(document.getElementById("ponto-mapa"), mapOptions);
    document.getElementById('pontos').addEventListener('click', function() {        
        plotapontos();
    });
    
      document.getElementById('remove').addEventListener('click', function() {        
        removepontos();
    });


}

google.maps.event.addDomListener(window, 'load', initialize);

function removepontos() {
    for (i = 0; i < markers_inst.length; i++) {
        markers_inst[i].setMap(null);
    }
}

function plotapontos() {
    
    if ($("#crimeR option:selected").val() == '1')
        var image = '/ocorrencia_beta/public/img/morte7.png';
    if ($("#crimeR option:selected").val() == '2')
        var image = '/ocorrencia_beta/public/img/armasangue.png';

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

    for (i = 0; i < waypts.length; i++) {
        waypts_aux[i] = waypts[i].split(",");
    }

    for (i = 0; i < waypts_aux.length; i++) {

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(waypts_aux[i][0], waypts_aux[i][1]),
            map: map,
            icon: image
        });
    }
     markers_inst.push(marker);
}










