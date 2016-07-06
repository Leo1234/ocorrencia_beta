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
            success: function(json){
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
    $("#municipioR").change(function(){
        
      if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR()){
            
            
            var muniR  = $("#municipioR option:selected").val();     
            var crimeM = $("#crimeR option:selected").val();
            var dataI  = $("#inputDataiR").val();
            var dataF  = $("#inputDatafR").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: { 
                        id_muniO    :muniR, 
                        id_crimeM   :crimeM, 
                        datai       :dataI, 
                        dataf       :dataF
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
    $("#inputDataiR").change(function(){
        
      if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR()){
            
            
            var muniR  = $("#municipioR option:selected").val();     
            var crimeM = $("#crimeR  option:selected").val();
            var dataI  = $("#inputDataiR").val();
            var dataF  = $("#inputDatafR").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: { 
                        id_muniO    :muniR, 
                        id_crimeM   :crimeM, 
                        datai       :dataI, 
                        dataf       :dataF
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
    $("#inputDatafR").change(function(){
        
      if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR()){
          
            var muniR  = $("#municipioR option:selected").val();     
            var crimeM = $("#crimeR  option:selected").val();
            var dataI  = $("#inputDataiR").val();
            var dataF  = $("#inputDatafR").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: { 
                        id_muniO    :muniR, 
                        id_crimeM   :crimeM, 
                        datai       :dataI, 
                        dataf       :dataF
                }, 
               // data: JSON.stringify(selecionados),
                 //data: data_to_send,
                dataType: "json",
                success: function(json) {
                    var options = "";
                    $.each(json, function(key, value) {
                        options += '<option value="' + value.lat+","+value.lng + '">' + value.rua +", "+value.bairro+ '</option>';
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
    $("#crimeR").change(function(){
        
      if (isEmptyMunicipio() && isEmptyCrime() && isEmptyDataiR() && isEmptyDatafR()){
            
            
            var muniR  = $("#municipioR option:selected").val();     
            var crimeM = $("#crimeR  option:selected").val();
            var dataI  = $("#inputDataiR").val();
            var dataF  = $("#inputDatafR").val();
            
            //var jsonString = JSON.stringify(selecionados);
            
            $.ajax({
                type: "POST",
                url: "/ocorrencia_beta/public/ocorrencia/itinerario",
                data: { 
                        id_muniO    :muniR, 
                        id_crimeM   :crimeM, 
                        datai       :dataI, 
                        dataf       :dataF
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

function isEmptyMunicipio(){
    if ($("#municipioR option:selected").val() != '')
        return true;
    else
        return false;
}

function isEmptyCrime(){
    if ($("#crimeR option:selected").val() != '')
        return true;
    else
        return false;
}

function isEmptyDataiR(){
    if ($("#inputDataiR").val() != '')
        return true;
    else
        return false;
}

function isEmptyDatafR(){
    if ($("#inputDatafR").val() != '')
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

  
