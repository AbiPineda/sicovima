var contadorTelefono=0;
var contadorCorreo=0;
$(document).on('ready',function(){
	if($('#modif').val()==1){
		contadorTelefono=parseFloat($('#cot').val());
		contadorCorreo=parseFloat($('#con').val());
	}
	$('#departamentos').on('change',function(e){
		var obtener=$('#departamentos').find('option:selected');
		var municipio=$('#municipios');
		var idDepto =obtener.val();
		if (idDepto!=0) {
			var ruta="/github/sicovima/public/municipio/"+idDepto;
			$.get(ruta,function(res){
				if (res=='false') {
					municipio.empty();
				}else{
					municipio.empty();
					$(res).each(function(key,value){
						cadena = "<option value='"+value.id+"'>"+value.nombre_Muni+"</option>";
						municipio.append(cadena);
					});
				};
			});
		}else{
			municipio.empty();
		};
	});

  $('#AddTelefono').click(function(){

		var telCliente ='';
		var telefono = $("#idTelefonos");
		var cadena="<div class='row' id='r"+contadorTelefono+"'><div class='form-group'><label class='col-lg-3 control-label'></label><div class='col-lg-4' ><input type='text' class='form-control' data-mask='(+999) 9999-9999' placeholder='(+      )         -     ' style='width:140px' name='tel[]' value='"+telCliente+"'/></div><div class='col-lg-1'><button class='btn btn-default  dim' type='button' onclick='deleteTelefono("+contadorTelefono+")' ><i class='fa fa-minus'></i></button></div></div><br></div>";
		contadorTelefono=contadorTelefono+1;
		telefono.append(cadena);

	});

	$('#AddCorreo').click(function(){

		var correoCliente='';
		var correo = $("#idCorreos");
		var cadena1="<div class='row' id='t"+contadorCorreo+"'><div class='form-group'><label class='col-lg-3 control-label'></label><div class='col-lg-7' ><input type='email' class='form-control' placeholder='JuanPerez@ejemplo.com' style = 'width:300px' name='cor[]' value='"+correoCliente+"'/></div><div class='col-lg-1'><button class='btn btn-default  dim' type='button' onclick='deleteCorreo("+contadorCorreo+")' ><i class='fa fa-minus'></i></button></div></div><br></div>";
		contadorCorreo=contadorCorreo+1;
		correo.append(cadena1);

	});

	// $('#tipoMercaderia_Prov').on('change',function(e){
	// 		var obtener=$('#tipoMercaderia_Prov').find('option:selected');
  //
	// 		if (obtener.val() == '0') {
	// 			$('#otroMercaderia').css("display","block");
	// 		}else{
	// 			$('#otroMercaderia').css("display","none");
	// 		}
	// });

});

function deleteTelefono(contador){
	console.log(contador);
	$("#r"+contador).remove();

}

function deleteCorreo(contador){
	console.log(contador);
	$("#t"+contador).remove();
}

function NumCheck(e, field) {
  key = e.keyCode ? e.keyCode : e.which
  // backspace
  if (key == 8){ 
	return true;
	}
  // 0-9
  if (key > 47 && key < 58) {
    if (field.value == "") {
return true;
}
    regexp = /.[0-9]{2}$/
    return !(regexp.test(field.value));
  }
  // .
  if (key == 46) {
    if (field.value == ""){
 return false;
}
    regexp = /^[0-9]+$/
    return regexp.test(field.value);
  }
  // other key
  return false;
}
