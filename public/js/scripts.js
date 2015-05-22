jq162 = jQuery.noConflict(true);


//alert( "1st loaded jQuery version ($): " + $.fn.jquery + "<br>" );
//alert( "2st loaded jQuery version ($): " + jq162.fn.jquery + "<br>" );



$(function() {
    $("#procedimento").chosen();

});
/*
$(function() {
    $("#municipio").chosen();

});
$(function() {
    $("#bairro").chosen();

});

$(function() {
    $("#viatura").chosen();

});
*/
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