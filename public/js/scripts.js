jq162 = jQuery.noConflict(true);


//alert( "1st loaded jQuery version ($): " + $.fn.jquery + "<br>" );
//alert( "2st loaded jQuery version ($): " + jq162.fn.jquery + "<br>" );

v = 0; // total de vítimas
//selected = [];
  
  
  
  
  $(function(){
     
    //Cria uma função para Criar os campos Nome e Telefone
    function createDivFields(){
        /*
         Criamos a variavel, e atribuimos os campos que serão criados;
         Utilizamos o colchetes nos nomes do campos para informar que os dados 
         em forma de array;
         Adiciona uma div, para que nela seja criado novos campos extras;
         E um link para para chamar o evento de adicionar;
        */
        var html  = '<div class="form-group">';
            html += '<label for="inputCirmes" class="col-lg-3 col-md-3 control-label">XXXX*:</label>';
            html += '<div class="col-lg-9  col-md-9">';
            html += '</div>';
            html += '</div>';
            return html;
    }
    
     $("#add").click(function(){
        //Adicionado no final do elemento ( #boxFields) os campos
        $("#boxFields").append(createDivFields());
        return false;
    });
});
  
  

$(function() {
   
    var status = true;
    
    $("#crime").change(function() {
        var selected = [];
        $('#crime :selected').each(function(){
            //selected[$(this).val()] = $(this).text();
            //if ($(this).val() == 1 ) {
                //$("#boxFields").append(createDivFields());
                 selected[$(this).val()] = $(this).text();
            //}
        });
      
    });
    
});

function in_array(array, e) {
    for (var i = 0; i < array.length; i++) {
        if(array[i] == e)
            return i;
        else
            return -1
        //alert(array[i]); 
    }
}
    




$(function() {

    $("#bt").click(function() {
        var nome = $('#nome').val();
        var telefone = $('#telefone').val();
        var sexo = $("input[name='sexo']:checked").val();
        var data_nasc = $('#data_nasc').val();
        var rua = $('#rua').val();
        var numero = $('#numero').val();
        var municipio = $("#municipio option:selected").text();
        var bairro = $("#bairro option:selected").text();

       // var municipioNumero = $('#municipio').val();
        //var bairroNumero = $('#bairro').val();



var status = false;
      
       $('#tbVitima tbody tr').each(function(i, linha) {  
                if(!($(linha).find('td:eq(v)').text()))
                    status = true;
        });
      
      if(status){
       v++; //total de vítimas
        var cols = '<tr>';

        cols += '<td>' + v + '</td>';
        cols += '<td>' + nome + '</td>';
        cols += '<td>' + telefone + '</td>';
        cols += '<td>' + data_nasc + '</td>';
        cols += '<td>' + sexo + '</td>';
        cols += '<td>' + rua + '</td>';
        cols += '<td>' + numero + '</td>';
        cols += '<td>' + bairro + '</td>';
        cols += '<td>' + municipio + '</td>';
        cols += '<td><button onclick="RemoveTableRow(this)" type="button" class="btn btn-xs btn-danger" title="Deletar"><span class="glyphicon glyphicon-floppy-remove"></span></button>\n\
<button onclick="Editar(this)" type="button" class="btn btn-xs btn-warning" title="Editar"><span class="glyphicon glyphicon-edit"></span></button></td>';
        cols += '</tr>';

        $("#tbVitima tbody").append(cols);

        $("#FormVitima")[0].reset();
        $('#bairro').val("");
        $('#municipio').val("");
      
  }else{
       
      alert("ops já tem registro");
  }
       

    });
});


$(function($) {
    Editar = function(handler) {

        var tr = $(handler).closest('tr');

        var tdId = tr.children("td:nth-child(1)");
        var tdNome = tr.children("td:nth-child(2)");
        var tdTelefone = tr.children("td:nth-child(3)");
        var tdData_nasc = tr.children("td:nth-child(4)");
        var tdSexo = tr.children("td:nth-child(5)");
        var tdRua = tr.children("td:nth-child(6)");
        var tdNumero = tr.children("td:nth-child(7)");
        var tdBairro = tr.children("td:nth-child(8)");
        var tdMunicipio = tr.children("td:nth-child(9)");

        $("#nome").attr("value", tdNome.html());
        $("#telefone").attr("value", tdTelefone.html());
        $("#data_nasc").attr("value", tdData_nasc.html());
        $("#rua").attr("value", tdRua.html());
        $("input[name=sexo][value=tdSexo.html()]").attr("checked", true);
        
        $("#numero").attr("value", tdNumero.html());
        $("#bairro option:contains(" + tdBairro.html() + ")").attr('selected', 'selected');
        $("#municipio option:contains(" + tdMunicipio.html() + ")").attr('selected', 'selected');
        return false;
    };
});


$(function($) {
    RemoveTableRow = function(handler) {
        var tr = $(handler).closest('tr');
        tr.fadeOut(400, function() {
            v--;
            tr.remove();
        });
        return false;
    };
});

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

$(function() {

    $("#datetimepickerNascV").datetimepicker({
        locale: "pt-br",
        showClear: true

    });
});