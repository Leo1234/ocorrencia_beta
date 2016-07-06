var arrayMes = new Array(12);
arrayMes[1] = "Janeiro";
arrayMes[2] = "Fevereiro";
arrayMes[3] = "Março";
arrayMes[4] = "Abril";
arrayMes[5] = "Maio";
arrayMes[6] = "Junho";
arrayMes[7] = "Julho";
arrayMes[8] = "Agosto";
arrayMes[9] = "Setembro";
arrayMes[10] = "Outubro";
arrayMes[11] = "Novembro";
arrayMes[12] = "Dezembro";

  
var randomnb = function() {
    
    return Math.round(Math.random() * 300);
};


var options = {
    responsive: true
};





function getMesExtenso(mes){
    return this.arrayMes[mes];
}

function isEmptyCrimeG() {
    if ($("#crimeG option:selected").val() !== '')
        return true;
    else
        return false;
}

function isEmptyDataiG() {
    if ($("#inputDataiG").val() !== '')
        return true;
    else
        return false;
}

function isEmptyDatafG() {
    if ($("#inputDatafG").val() !== '')
        return true;
    else
        return false;
}


window.onload = function(){
$("#btnGrafico").click(function(){
    if (isEmptyCrimeG() && isEmptyDataiG() && isEmptyDatafG()) {
            var crimeM = $("#crimeG  option:selected").val();
            var dataI = $("#inputDataiG").val();
            var dataF = $("#inputDatafG").val();
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/grafico",
                data: {
                    id_crimeM: crimeM,
                    datai: dataI,
                    dataf: dataF
                },
                dataType: "json",
                success: function(json) {
                    var x = [];
                    var y = [];
                    $.each(json, function(key, value) {
                        x [key] = getMesExtenso(value.mes);
                        y [key] = value.qtd;
                    });
                    var data = {
                        labels: x,
                        datasets: [
                            {
                                label: "Dados primários",
                                fillColor: "rgba(220,220,220,0.5)",
                                strokeColor: "rgba(220,220,220,0.8)",
                                highlightFill: "rgba(220,220,220,0.75)",
                                highlightStroke: "rgba(220,220,220,1)",
                                data: y
                            }

                        ]
                    };
                    var ctx = document.getElementById("GraficoBarra").getContext("2d");
                    var BarChart = new Chart(ctx).Bar(data, options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
        
    });
};    
