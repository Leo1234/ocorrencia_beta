jq162 = jQuery.noConflict(true);



$(function() {
    $("#procedimento").chosen();

});

$(function() {
    $("#crime").chosen();
});

$(function() {
    $("#composicao").chosen();
});



$(function() {
    $("#id_muni").change(function() {
        $.ajax({
            type: "POST",
            url: "/ocorrencia_beta/public/bairro/search",
            data: {id_muni: $("#id_muni").val()},
            dataType: "json",
            success: function(json) {
                var options = "";
                $.each(json, function(key, value) {
                    options += '<option value="' + value.id_area + '">' + value.descricao + '</option>';
                });
                $("#id_area").html(options);

            },
            error: function(erro) {
                alert("Aconteceu algum erro na Requisição.");
            }
        });
    });
});




$(function() {
    $("#municipio").change(function() {
        $.ajax({
            type: "POST",
            url: "/ocorrencia_beta/public/bairro/searchBairro",
            data: {id_muni: $("#municipio").val()},
            dataType: "json",
            success: function(json) {
                var options = "";
                $.each(json, function(key, value) {
                    options += '<option value="' + value.id_bai + '">' + value.bairro + '</option>';
                });
                $("#bairro").html(options);

            },
            error: function(erro) {
                alert("Aconteceu algum erro na Requisição.");
            }
        });
    });
});


$(function() {
    $("#municipioP").change(function(){
        
      if (isEmptyMunicipioP() && isEmptyCrimeP() && isEmptyDataiP() && isEmptyDatafP()){
            
            
            var muniR  = $("#municipioP option:selected").val();     
            var crimeM = $("#crimeP option:selected").val();
            var dataI  = $("#dataiP").val();
            var dataF  = $("#datafP").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/pontos",
                data: { 
                    municipioP: muniR,
                    crimeP: crimeM,
                    dataiP: dataI,
                    datafP: dataF
                }, 
               // data: JSON.stringify(selecionados),
                 //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat+", "+value.lng + '">' + value.rua +", "+value.bairro+ '</option>';
                    });
                    $(".bairro").each(function(e){
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
alert(JSON.stringify(selecionados));
    });
    
});

$(function() {
    $("#crimeP").change(function(){
        
      if (isEmptyMunicipioP() && isEmptyCrimeP() && isEmptyDataiP() && isEmptyDatafP()){
            
            
            var muniR  = $("#municipioP option:selected").val();     
            var crimeM = $("#crimeP option:selected").val();
            var dataI  = $("#dataiP").val();
            var dataF  = $("#datafP").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/pontos",
                data: { 
                    municipioP: muniR,
                    crimeP: crimeM,
                    dataiP: dataI,
                    datafP: dataF
                }, 
               // data: JSON.stringify(selecionados),
                 //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat+", "+value.lng + '">' + value.rua +", "+value.bairro+ '</option>';
                    });
                    $(".bairro").each(function(e){
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
alert(JSON.stringify(selecionados));
    });
    
});

$(function() {
    $("#dataiP").change(function(){
        
      if (isEmptyMunicipioP() && isEmptyCrimeP() && isEmptyDataiP() && isEmptyDatafP()){
            
            
            var muniR  = $("#municipioP option:selected").val();     
            var crimeM = $("#crimeP option:selected").val();
            var dataI  = $("#dataiP").val();
            var dataF  = $("#datafP").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/pontos",
                data: { 
                    municipioP: muniR,
                    crimeP: crimeM,
                    dataiP: dataI,
                    datafP: dataF
                }, 
               // data: JSON.stringify(selecionados),
                 //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat+", "+value.lng + '">' + value.rua +", "+value.bairro+ '</option>';
                    });
                    $(".bairro").each(function(e){
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
alert(JSON.stringify(selecionados));
    });
    
});

$(function() {
    $("#datafP").change(function(){
        
      if (isEmptyMunicipioP() && isEmptyCrimeP() && isEmptyDataiP() && isEmptyDatafP()){
            
            
            var muniR  = $("#municipioP option:selected").val();     
            var crimeM = $("#crimeP option:selected").val();
            var dataI  = $("#dataiP").val();
            var dataF  = $("#datafP").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/pontos",
                data: { 
                    municipioP: muniR,
                    crimeP: crimeM,
                    dataiP: dataI,
                    datafP: dataF
                }, 
               // data: JSON.stringify(selecionados),
                 //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat+", "+value.lng + '">' + value.rua +", "+value.bairro+ '</option>';
                    });
                    $(".bairro").each(function(e){
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
alert(JSON.stringify(selecionados));
    });
    
});

$(function() {
    $("#municipioR").change(function() {

        if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR() && isEmptyDiaSemana()) {

            var diasSemana = [];
            var j = 0;
            var checkboxArray = document.getElementById('diaSemana');
            for (var i = 0; i < checkboxArray.length; i++) {
                if (checkboxArray.options[i].selected) {
                    diasSemana[j] = checkboxArray[i].value;
                    j++;
                }
            }
            // alert(diasSemana);
            //alert(document.getElementById('diaSemana').selectedIndex);

            var muniR = $("#municipioR option:selected").val();
            var crimeM = $("#crimeR option:selected").val();
            var dataI = $("#inputDataiR").val();
            var dataF = $("#inputDatafR").val();

            //var jsonString = JSON.stringify(selecionados);

            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: {
                    id_muniO: muniR,
                    id_crimeM: crimeM,
                    datai: dataI,
                    dataf: dataF,
                    dias: diasSemana
                },
                // data: JSON.stringify(selecionados),
                //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat + ", " + value.lng + '">' + value.rua + ", " + value.bairro + '</option>';
                    });
                    $(".bairro").each(function(e) {
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
        alert(JSON.stringify(selecionados));
    });

});


$(function() {
    $("#diaSemana").change(function() {

        if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR() && isEmptyDiaSemana()) {

            var diasSemana = [];
            var j = 0;
            var checkboxArray = document.getElementById('diaSemana');
            for (var i = 0; i < checkboxArray.length; i++) {
                if (checkboxArray.options[i].selected) {
                    diasSemana[j] = checkboxArray[i].value;
                    j++;
                }
            }

            // alert(diasSemana);

            var muniR = $("#municipioR option:selected").val();
            var crimeM = $("#crimeR option:selected").val();
            var dataI = $("#inputDataiR").val();
            var dataF = $("#inputDatafR").val();

            //var jsonString = JSON.stringify(selecionados);

            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: {
                    id_muniO: muniR,
                    id_crimeM: crimeM,
                    datai: dataI,
                    dataf: dataF,
                    dias: diasSemana
                },
                // data: JSON.stringify(selecionados),
                //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat + ", " + value.lng + '">' + value.rua + ", " + value.bairro + '</option>';
                    });
                    $(".bairro").each(function(e) {
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
        alert(JSON.stringify(selecionados));
    });

});


$(function() {
    $("#inputDataiR").change(function() {
        if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR() && isEmptyDiaSemana()) {

            var diasSemana = [];
            var j = 0;
            var checkboxArray = document.getElementById('diaSemana');
            for (var i = 0; i < checkboxArray.length; i++) {
                if (checkboxArray.options[i].selected) {
                    diasSemana[j] = checkboxArray[i].value;
                    j++;
                }
            }

            var muniR = $("#municipioR option:selected").val();
            var crimeM = $("#crimeR  option:selected").val();
            var dataI = $("#inputDataiR").val();
            var dataF = $("#inputDatafR").val();

            //var jsonString = JSON.stringify(selecionados);

            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: {
                    id_muniO: muniR,
                    id_crimeM: crimeM,
                    datai: dataI,
                    dataf: dataF,
                    dias: diasSemana
                },
                // data: JSON.stringify(selecionados),
                //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat + ", " + value.lng + '">' + value.rua + ", " + value.bairro + '</option>';

                    });
                    $(".bairro").each(function(e) {
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
        alert(JSON.stringify(selecionados));
    });

});

$(function() {
    $("#inputDatafR").change(function() {

        if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR() && isEmptyDiaSemana()) {

            var diasSemana = [];
            var j = 0;
            var checkboxArray = document.getElementById('diaSemana');
            for (var i = 0; i < checkboxArray.length; i++) {
                if (checkboxArray.options[i].selected) {
                    diasSemana[j] = checkboxArray[i].value;
                    j++;
                }
            }

            var muniR = $("#municipioR option:selected").val();
            var crimeM = $("#crimeR  option:selected").val();
            var dataI = $("#inputDataiR").val();
            var dataF = $("#inputDatafR").val();

            //var jsonString = JSON.stringify(selecionados);

            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: {
                    id_muniO: muniR,
                    id_crimeM: crimeM,
                    datai: dataI,
                    dataf: dataF,
                    dias: diasSemana
                },
                // data: JSON.stringify(selecionados),
                //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat + "," + value.lng + '">' + value.rua + ", " + value.bairro + '</option>';
                    });
                    $(".bairro").each(function(e) {
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
        alert(JSON.stringify(selecionados));
    });

});

$(function() {
    $("#crimeR").change(function() {

        if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR() && isEmptyDiaSemana()) {

            var diasSemana = [];
            var j = 0;
            var checkboxArray = document.getElementById('diaSemana');
            for (var i = 0; i < checkboxArray.length; i++) {
                if (checkboxArray.options[i].selected) {
                    diasSemana[j] = checkboxArray[i].value;
                    j++;
                }
            }

            var muniR = $("#municipioR option:selected").val();
            var crimeM = $("#crimeR  option:selected").val();
            var dataI = $("#inputDataiR").val();
            var dataF = $("#inputDatafR").val();

            //var jsonString = JSON.stringify(selecionados);

            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: {
                    id_muniO: muniR,
                    id_crimeM: crimeM,
                    datai: dataI,
                    dataf: dataF,
                    dias: diasSemana
                },
                // data: JSON.stringify(selecionados),
                //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat + ", " + value.lng + '">' + value.rua + ", " + value.bairro + '</option>';
                    });
                    $(".bairro").each(function(e) {
                        $(this).html(options);
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error... " + textStatus + "        " + errorThrown);
                }
            });
        }
        alert(JSON.stringify(selecionados));
    });

});

function isEmptyMunicipio() {
    if ($("#municipioR option:selected").val() != '')
        return true;
    else
        return false;
}
function isEmptyMunicipioP() {
    if ($("#municipioP option:selected").val() != '')
        return true;
    else
        return false;
}
function isEmptyCrime() {
    if ($("#crimeR option:selected").val() != '')
        return true;
    else
        return false;
}
function isEmptyCrimeP() {
    if ($("#crimeP option:selected").val() != '')
        return true;
    else
        return false;
}
function isEmptyDiaSemana() {
    if (document.getElementById('diaSemana').selectedIndex >= 0)
        return true;
    else
        return false;
}
function isEmptyDataiR() {
    if ($("#inputDataiR").val() != '')
        return true;
    else
        return false;
}

function isEmptyDataiP() {
    if ($("#dataiP").val() != '')
        return true;
    else
        return false;
}
function isEmptyDatafR() {
    if ($("#inputDatafR").val() != '')
        return true;
    else
        return false;
}

function isEmptyDatafP() {
    if ($("#datafP").val() != '')
        return true;
    else
        return false;
}

$(function() {
    $("#bairro").change(function() {
        $.ajax({
            type: "POST",
            url: "/ocorrencia_beta/public/bairro/searchBairroViatura",
            data: {id_bai: $("#bairro").val()},
            dataType: "json",
            success: function(json) {
                var options = "";
                $.each(json, function(key, value) {
                    options += '<option value="' + value.id_vtr + '">' + value.prefixo + '</option>';
                });
                $("#viatura").html(options);

            },
            error: function(erro) {
                alert("Aconteceu algum erro na Requisição.");
            }
        });
    });

});

$(function() {
    $("#datetimepickerI").datetimepicker({
        locale: "pt-br",
        showClear: true
    });
});

$(function() {
    $("#datetimepickerF").datetimepicker({
        locale: "pt-br",
        showClear: true
    });
});

$(function() {

    $("#datetimepickerNasc").datetimepicker({
        locale: "pt-br",
        showClear: true

    });
});

$(function() {
    $("#datetimepickerInlcu").datetimepicker({
        locale: "pt-br",
        showClear: true
    });
});

$(function() {

    $("#datetimepickerNascV").datetimepicker({
        locale: "pt-br",
        showClear: true

    });
});

$(function(){

    $("#datetimepickerNascA").datetimepicker({
        locale: "pt-br",
        showClear: true

    });
});
