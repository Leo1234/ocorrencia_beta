
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
                    options += '<option value="' + value.id_area + '">' +value.descricao+ '</option>';
                });
                $("#id_area").html(options);
              
            },
            error: function(erro) {
                alert("Aconteceu algum erro na Requisição.");
            }
        });
    });

});


/*
 $(function() {
 ("input.typeahead").typeahead({
 ajax: {
 url: "/ocorrencia_beta/public/bairro/search",
 timeout: 500,
 displayField: "id_area",
 triggerLength: 1,
 method: "get",
 loadingClass: "loading-circle",
 preDispatch: function(query) {
 {
 query: $("#id_muni").val();
 }
 },
 preProcess: function(data) {
 var options = "";
 $.each(data, function(key, value) {
 options += '<option value="' + key + '">' + value + '</option>';
 });
 $("#id_area").html(options);
 return options;
 
 }
 }
 });
 });
 
 */

