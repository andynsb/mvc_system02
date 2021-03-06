<?php //var_dump($_SESSION["current_cargo"]);?>
<?php //var_dump($_SESSION["current_cargo"]["data"]["permisos"]["info"]);?>
<script type="text/javascript">
var data = eval('(' + JSON.stringify(<?php print json_encode($_SESSION["current_cargo"]);?>) + ')');

function set_table(current){
  var $data=eval('('+JSON.stringify(data.data)+')')[current];
  var table='<tr class="table_title">';
  if(current==="permisos"){
    table+='<th>CARGO</th>';
    table+='<th>AREA</th>';
    table+='<th>TIPO DE INSTANCIA</th>';
    table+='<th>NIVEL</th>';
    table+='<th>PERMISO</th>';
    table+='<th>PROPIEDAD</th>';
    table+='<th>FUNCIONES</th></tr>';
    for(var j=0;j<$data.data.length;j++){
      table+='<tr class="table_row '+current+$data.data[j].id+'">';
      if($data.data[j].cargo_id===0)
        table+='<td></td>';
      else{
        var flag=0;
        for(var i=0;i<$data.info.cargos.length||flag===0;i++){
          if($data.info.cargos[i].id===$data.data[j].cargo_id){
            flag=1;
            table+='<td>'+$data.info.cargos[i].nombre+'</td>';
          }
        }
      }
      if($data.data[j].area_id==="0")
        table+='<td></td>';
      else{
        flag=0;
        for(var i=0;i<$data.info.areas.length||flag===0;i++){
          if($data.info.areas[i].id===$data.data[j].area_id){
            flag=1;
            table+='<td>'+$data.info.areas[i].nombre+'</td>';
          }
        }
      }
      if($data.data[j].tipo_instancia_id==="0")
        table+='<td></td>';
      else{
        flag=0;
        for(var i=0;i<$data.info.tipos.length||flag===0;i++){
          if($data.info.tipos[i].id===$data.data[j].tipo_instancia_id){
            flag=1;
            table+='<td>'+$data.info.tipos[i].nombre+'</td>';
          }
        }
      }
      table+='<td>'+$data.data[j].nivel+'</td>';
      if($data.data[j].permisos){
        table+='<td><select id="tipo_instancia">';
        for (var i = 0; i < $data.data[j].permisos.length; i++) {
          flag=0;
          for (var k = 0; k < $data.info.tipos.length||flag===0; k++) {
            if($data.info.tipos[k].id===$data.data[j].permisos[i].tipo){
              flag=1;
              table+='<option selected="selected" value=".tipo'+$data.data[j].id+'_'+i+'">'+$data.info.tipos[k].nombre+'</option>';      
            }            
          }          
        }
        //table+='<option selected="selected" value="0">--Ver Instancias--</option>';
        table+='</select></td>';
        table+='<td>';
        for (var i = 0; i < $data.data[j].permisos.length; i++) {          
          table+='<span class="perm'+$data.data[j].id+'_'+i+' permiso_span">'+$(this).text()+$data.data[j].permisos[i].permiso+'</span>';
        }
        table+='</td>';
      }else{
        table+='<td></td>';
        table+='<td></td>';
      }
      //var id=boton.parent().parent().attr("class").substring(boton.parent().parent().attr("class").indexOf(" ")+1+current.length);
      table+='<td><input type="button" value="EDITAR" class="btneditar"></td>';
      table+='</tr>';
    }
  }else if(current==="tipos_instancia"){
    table+='<th>LOGO</th>';
    table+='<th>CLASIFICACIÓN</th>';
    table+='<th>NOMBRE</th>';
    table+='<th>DESCRIPCIÓN</th>';
    table+='<th>FUNCIONES</th></tr>';
    if($data[0]){
      for(var j=0;j<$data.length;j++){
        table+='<tr id="'+current+$data[j].id+'" class="table_row '+current+$data[j].id+'">'
        table+='<td>'+$data[j].logo+'</td>';
        table+='<td>'+$data[j].clasificacion+'</td>';
        table+='<td>'+$data[j].nombre+'</td>';
        table+='<td>'+$data[j].descripcion+'</td>';
        table+='<td><input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar" onclick="eliminar(\''+current+'\','+$data[j].id+')"></td>';
        table+='</tr>';
      }
    }else{
      table+='<tr class="none"><td>NO HAY ELEMENTOS</td></tr>';
    }
  
  }else if(current==="nucleo"){
    
  }else{//cargo o area
    table+='<th>NOMBRE</th>';
    table+='<th>DESCRIPCIÓN</th>';
    table+='<th>FUNCIONES</th></tr>';
    if($data[0]){
      for(var j=0;j<$data.length;j++){
        table+='<tr id="'+current+$data[j].id+'" class="table_row '+current+$data[j].id+'">'
        table+='<td>'+$data[j].nombre+'</td>';
        table+='<td>'+$data[j].descripcion+'</td>';
        table+='<td><input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar" onclick="eliminar(\''+current+'\','+$data[j].id+')"></td>';
        table+='</tr>';
      }
    }else{
      table+='<tr class="none"><td>NO HAY ELEMENTOS</td></tr>';
    }
  }
  $('#main_table tbody').append(table);
  $('.btneditar').click(function(){
    editar($(this));
  });

  $('.permiso_span').hide();
  $('#tipo_instancia').change(function(){
      $('.permiso_span').hide();
       $("select option:selected").each(function() {
        var idtipo=$(this).attr('value').substring($(this).attr('value').indexOf(' ')+6);
        $('.perm'+idtipo).css("display", "inline");
       });
  });
}

function set_table_editar(current){
  
  var $data=eval('('+JSON.stringify(data.data)+')')[current];
  var table='<tr class="table_title">';     
     table+='<th>PROPIEDAD</th>';
     table+='<th>PERMISO</th>';
     table+='</tr>';
      for(var i=0;i<$data.info.tipos.length;i++){
           table+='<tr class="table_row">';
           table+='<td id="tipo'+$data.info.tipos[i].id+'">'+$data.info.tipos[i].clasificacion+" - "+$data.info.tipos[i].nombre+'</td>';
           table+='<td><input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="editar">Editar <input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="ver">Ver <input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="nada">Nada</td>';
            //$('input[name="permiso'+$data.info.tipos[i].id+'"]').filter('[value="'+$data.data.permisos[i].permiso+'"]').prop("checked",true);
           table+='</tr>';
      }
      table+='<td><input type="button" value="GUARDAR" class="save_permiso"><input type="button" value="CANCELAR" class="cancelar"></td>';
      $('#main_table tbody').append(table);      
      $('input.save_permiso').click(function(){
          guardar($(this));
      });
      $('input.cancelar').click(function(){
      $('#main_table tbody').empty();
      set_table(current);  
      });
}
$(document).ready(function(){
  
  /*************************************MENU**************************************/
  $('#menu_bar').each(function(){
    // For each set of tabs, we want to keep track of
    // which tab is active and it's associated content
    var $active, $content, $links = $(this).find('a.father');
    $links.push($('#btn_ver_perfil')[0]);
    // If the location.hash matches one of the links, use that as the active tab.
    // If no match is found, use the first link as the initial active tab.
    $active = $($links.filter('[href="'+location.hash+'"]')[0] ||$links[0]);
    $active.addClass('active');
    $content = $($active.attr('href'));

    $('#content_title').text($active.first().text());
    set_table($active.attr('href').substring(1));

    // Hide the remaining content
    $links.not($active).each(function () {
      $($(this).attr('href')).hide();
    });

    // Bind the click event handler
    $(this).on('click', 'a.father,a#btn_ver_perfil', function(e){
      // Make the old tab inactive.
      $active.removeClass('active');
      $content.hide();
      $('#main_table tbody').empty();
      $(".agregar").removeAttr("disabled");

      // Update the variables with the new link and content
      $active = $(this);
      $content = $($(this).attr('href'));

      // Make the tab active.
      $active.addClass('active');
      $content.show();

      $('#content_title').text($active.first().text());
      set_table($active.attr('href').substring(1));

      if($(this)==$('#btn_ver_perfil')){
        $('ul.roles').hide();
        $('#saludo').css('background-color','#005597');
      }

      // Prevent the anchor's default click action
      e.preventDefault();
    });
  });
});

/*
PONER TITULOS AUTOMATICAMENTE CON LOS KEY DE LOS JSON
nombre_c = eval('(' + JSON.stringify($data[0]) + ')');
      claves= new Array();
      var table="";
      table+='<tr class="table_title">';
      var band=0;
      for(nombre in nombre_c) {
        if(band===1){
          table+='<th>'+nombre.toUpperCase()+'</th>';
          claves.push(nombre);
        }
        band=1;
      }
      table+='<th>FUNCIONES</th></tr>';*/
</script>