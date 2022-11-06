var loF		= document.querySelector('#frmF');
var gsUrl	= "../../controladores/corUsuario.php";

window.onload = () => {
	fpMostrar_Mensaje("Su usuario ha sido restablecido, deberá actualizar su información de usuario", 'a');
};

/****************************************************************************************************
	Funcion que permite verificar si el formulario supera las validaciones y de ser cierto, envia los
	datos al controlador
****************************************************************************************************/
function fpGuardar()
{
	if( fbValidar() )
	{
		loF.submit();
	}
}

/****************************************************************************************************
	Funcion que permite validar el formulario
****************************************************************************************************/
function fbValidar()
{
	let lbBueno = false; 
	if(loF.txtClave_Anterior.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe ingresar la contraseña anterior",'a');
	}
	else if(loF.txtClave.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe ingresar la contraseña nueva",'a');
	}
	else if(loF.txtClave.value.length < 8)
	{
		fpMostrar_Mensaje("Disculpe, la nueva contraseña debe constar de al menos 8 caracteres",'a');
		loF.txtClave.value = "";
		loF.txtRepita_Clave.value = "";
	}
	else if(loF.txtRepita_Clave.value != loF.txtClave.value)
	{
		fpMostrar_Mensaje("Disculpe, debe coincidir la nueva contraseña ",'a');
		loF.txtRepita_Clave.value = "";
	}
	else if(loF.cmbPregunta1.value == "-")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la pregunta de seguridad No. 1 ",'a');
	}
	else if(loF.txtRespuesta1.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe colocar la respuesta a la pregunta de seguridad No. 1",'a');
	}
	else if(loF.cmbPregunta2.value == "-")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la pregunta de seguridad No. 2 ",'a');
	}
	else if(loF.txtRespuesta2.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe colocar la respuesta a la pregunta de seguridad No. 2",'a');
	}
	else
	{
		let laCadena	= loF.txtClave.value;
		let liTamano	= loF.txtClave.value.length;
		let laLetras	= "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
		let laNumeros	= "0123456789";
		let liI 		= 0;
		let liContador	= 0;
		while( liI < liTamano && liContador == 0)
		{
			if(laLetras.indexOf(laCadena[liI]) != -1)
			{
				liContador++;
			}
			liI++;
		}
		if(liContador == 0)
		{
			fpMostrar_Mensaje("Disculpe, la contraseña debe ser de al menos 8 caracteres, de los cuales debe contener por lo menos una letra o por lo menos un número", 'a');
		}
		else
		{
			liI 		= 0;
			liContador	= 0;
			while( liI < liTamano && liContador == 0)
			{
				if(laNumeros.indexOf(laCadena[liI]) != -1)
				{
					liContador++;
				}
				liI++;
			}
			if(liContador == 0)
			{
				fpMostrar_Mensaje("Disculpe, la contraseña debe ser de al menos 8 caracteres, de los cuales debe contener por lo menos una letra o por lo menos un número", 'a');
			}
			else
			{
				lbBueno = true;
			}
		}
	}
	return lbBueno;
}
