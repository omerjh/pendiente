var Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	// timer: 10000
});
var loF=document.querySelector('#frmF');
fpInicio();

function fpInicio()
{
	switch(loF.txtHacer.value)
	{
		case "Listo":
			fpMostrar_Mensaje(loF.txtMensaje.value, '+');
			break;
			
		case "Malo":
			fpMostrar_Mensaje(loF.txtMensaje.value, '-');
			break;
	}
	loF.txtNombre.focus();
}

/****************************************************************************************************
	Comienzo de las validaciones del formulario
****************************************************************************************************/
function fbValidar()
{
	let lbBueno = false; 
	if(loF.txtNombre.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe escribir su usuario",'a');
	}
	else if(loF.txtClave.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe escribir su contraseña",'a');
	}else
	{
			lbBueno = true;
	}
	loF.txtOperacion.value='iniciar_sesion';
	return lbBueno;
}

/****************************************************************************************************
	Funcion que permite mostrar una vntana modal donde se muestra los mensajes
****************************************************************************************************/
function fpMostrar_Mensaje(psMensaje, psImagen)
{
	let lsIcono = "";
	if(psImagen=='+')//MENSAJE E IMAGEN DE OPERACION EXITOSA
	{
		lsIcono = "success";
	}
	else if(psImagen=='-')//MENSAJE E IMAGEN DE OPERACION ERRONEA
	{
		lsIcono = "error";
	}
	else if(psImagen=='a')//MENSAJE E IMAGEN PARA ADVERTENCIAS
	{
		lsIcono = "warning";
	}
	Swal.fire({
		icon: lsIcono,
		title: psMensaje
	});
}
/****************************************************************************************************
	Funcion que permite ocultar la ventana modal de olvido de contraseña y limpia la caja de texto 
	donde se solicita el usuario a recuperar.
****************************************************************************************************/
function fpCancelar_Recuperar()
{
	loF.txtNombre_Recuperar.value="";
	$("#mensaje").modal("hide");
}

/****************************************************************************************************
	Funcion que permite validar el campo donde se ingresa el usuario a recuperar, si pasa la 
	validación se procede a enviar el formulario.
****************************************************************************************************/
function fpRecuperar_Enviar()
{
	if(loF.txtNombre_Recuperar.value =="")
	{
		fpMostrar_Mensaje("Disculpe, debe indicar su nombre de usuario para recuperar la contraseña", 'a')
	}
	else
	{
		loF.txtOperacion.value='recuperar_clave';
		loF.submit();
	}
}

/****************************************************************************************************
	Funcion que permite ocultar la ventana modal de olvido de contraseña y limpia la caja de texto 
	donde se solicita el usuario a recuperar.
****************************************************************************************************/
function fpCancelar_Recuperar()
{
	loF.txtNombre_Recuperar.value="";
	$("#mensaje").modal("hide");
}
