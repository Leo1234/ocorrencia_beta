jq162 = jQuery.noConflict( true );

        
 //alert( "1st loaded jQuery version ($): " + $.fn.jquery + "<br>" );
 //alert( "2st loaded jQuery version ($): " + jq162.fn.jquery + "<br>" );
 
 
 $(function() {
$("#chosen-select").chosen();
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
 


