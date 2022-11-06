var loF		= document.querySelector('#frmF');
var gsUrl	= "../../controladores/corUsuario.php";

window.onload = () => {
	loF.txtRespuesta1.focus();
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
	if(loF.txtRespuesta1.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe colocar la respuesta a la pregunta de seguridad No. 1",'a');
	}
	else if(loF.txtRespuesta2.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe colocar la respuesta a la pregunta de seguridad No. 2",'a');
	}
	else
	{
		lbBueno = true;
	}
	return lbBueno;
}
