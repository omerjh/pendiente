/********************************************	INICIO DE AREA DE VALIDACIONES	********************************************/
/********************************************************************************************************
	Función que valida si la tecla presionada corresponde letras permitidas por esta funcion
********************************************************************************************************/
function fbSolo_Texto(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = " ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a numeros o letras que son permitidos por esta
	funcion
********************************************************************************************************/
function fbSolo_Texto_Numeros(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = " ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).select();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a numeros, letras o ciertos caracteres 
	especiales que son permitidos por esta funcion
********************************************************************************************************/
function fbSolo_Clave(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = ".,;:-_*¡!#%&/()¿?+ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida que el documento de de identidicación de una persona sea V, E o P
	(Venezolano - Extranjero - Pasaporte)
********************************************************************************************************/
function fbSolo_Documento(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "VEPvep";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los caracteres permitidos para formar 
	enlaces a los archivos que se llamaran desde el menú
********************************************************************************************************/
function fbSolo_Enlace(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "&?=.-_/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a numeros, letras o ciertos caracteres 
	especiales que son permitidos por esta funcion
********************************************************************************************************/
function fbSolo_Texto_Numeros_Simbolos(psTecla, psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = " .,;:-_*¡!#%&/()=+?¿ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
		
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a numeros, letras o ciertos caracteres 
	especiales que son permitidos por esta funcion, asi como tambien permitir letras mayúsculas y 
	minúsculas en dicho campo
********************************************************************************************************/
function fbSolo_Numeros_Guion(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "-1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a letras o ciertos caracteres especiales que 
	son permitidos por esta funcion, asi mismo se usará principalmente para nombres y apellidos de las
	personas u otros campos que se considere
********************************************************************************************************/
function fbSolo_Nombres(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = " 'ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los números
********************************************************************************************************/
function fbSolo_Numeros(psTecla,psCampo,psCampo_Focus) 
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = ".1234567890";
	laEspeciales = [8,9,13,35,36,37,38,39,40,46,116,118];
	
	if(liKey == 46)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = '.';
		for(liI = 0 ; liI <= liA ; liI++){
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1){
				liContador++;
			}
		}
		if(liContador == 1)
		{
			return false;
		}
	}
	
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los números  del RIF
********************************************************************************************************/
function fbSolo_Rif(psTecla,psCampo_Focus) 
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "JVGEPC-1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los números para las horas y separador :
********************************************************************************************************/
function fbSolo_Horas(psTecla,psCampo,psCampo_Focus) 
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = ":1234567890";
	laEspeciales = [8,9,13,35,36,37,38,39,40,46,116,118];
	
	if(liKey == 58)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = ':';
		for(liI = 0 ; liI <= liA ; liI++){
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1){
				liContador++;
			}
		}
		if(liContador == 1)
		{
			return false;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
		
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los números, en este caso se permite el 
	símbolo de la coma, ya que se utiliza para separar los numeros decimales.
********************************************************************************************************/
function fbSolo_Numeros_Flotantes_Comas(psTecla,psCampo,psCampo_Focus) 
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = ",1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	
	if(liKey == 44)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = ",";
		for(liI = 0 ; liI <= liA ; liI++){
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1){
				liContador++;
			}
		}
		if(liContador == 1)
		{
			return false;
		}
	}
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los números, en este caso se permite el 
	símbolo de la coma, ya que se utiliza para separar los numeros decimales.
********************************************************************************************************/
function fbSolo_Placa(psTecla,psCampo,psCampo_Focus) 
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = " ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	
	if(liKey == 32)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = " ";
		for(liI = 0 ; liI <= liA ; liI++){
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1){
				liContador++;
			}
		}
		if(liContador == 1)
		{
			return false;
		}
	}
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los caracteres permitidos para las fechas
********************************************************************************************************/
function fbSolo_Fecha(psTecla,psCampo,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "-/1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];

	if(liKey == 45 || liKey == 47)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = '-/'
		for(liI = 0 ; liI <= liA ; liI++){
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1){
				liContador++;
			}
		}
		if(liContador == 2)
		{
			return false;
		}
	}
	
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los caracteres permitidos para los telefonos
********************************************************************************************************/
function fbSolo_Telefono(psTecla,psCampo,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];

	if(liKey == 45)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = '-'
		for(liI = 0 ; liI <= liA ; liI++)
		{
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1)
			{
				liContador++;
			}
		}
		if(liContador == 1){
			return false;
		}
	}
	
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los caracteres permitidos para los 
	Correos electrónicos
********************************************************************************************************/
function fbSolo_Correo(psTecla,psCampo,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	lsTecla = String.fromCharCode(liKey).toUpperCase();
	laLetras = "@.,-_ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	laEspeciales = [8,9,13,35,36,37,39,46,116,118];
	lbTecla_Especial = false
	for(let liI in laEspeciales)
	{
		if(liKey == laEspeciales[liI])
		{
			lbTecla_Especial = true;
			break;
		}
	}
	
	if(liKey == 64)
	{
		let liA = psCampo.value.length;
		let liI = 0;
		let liContador = 0;
		let lsCaracteres = '@'
		for(liI = 0 ; liI <= liA ; liI++)
		{
			if(lsCaracteres.indexOf(psCampo.value[liI]) != -1)
			{
				liContador++;
			}
		}
		if(liContador == 1)
		{
			return false;
		}
	}
	
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
	
	if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
	{
		return false;
	}
}
	
/********************************************************************************************************
	Función que permite borrar espacios en blanco al principio y al final de una caja de texto
********************************************************************************************************/
function fsBorrar_Espacios(poCaja,psTipo)
{
	let lsCadena = poCaja.value;
	let lsListo;
	let liTamano = lsCadena.length;
	String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };
	lsListo=lsCadena.trim();
	if(psTipo != 'enlace' && psTipo != 'texto_numero_simbolo_2' )
	{
		lsListo=lsListo.toUpperCase();
	}
	
	//Llamado a la funcion que borra los espacios en blanco dentro de una cadena de texto
	lsListo = fpValidar_Espaciado_Texto(lsListo);
	
	if(lsListo != '')
	{
		lsListo=fsLimpia(lsListo,psTipo);
	}
	document.querySelector('#'+poCaja.id).value=lsListo;
}

/********************************************************************************************************
	Función que permite borrar espacios en blanco adicionales (en medio) de una caja de texto, dejando 
	solo un espacio entre cada palabra
********************************************************************************************************/
function fpValidar_Espaciado_Texto(psCadena)
{
	let liI=0;
	let lsCadena = psCadena;
	let lsCadena_Nueva = "";
	for(liI=0 ; liI < lsCadena.length ; liI++)
	{
		if(lsCadena[liI] == " " )
		{
			
			if(lsCadena[liI] == lsCadena[liI+1])
			{
				continue;
			}
			else
			{
				lsCadena_Nueva = lsCadena_Nueva+lsCadena[liI];
			}
		}
		else
		{
			lsCadena_Nueva = lsCadena_Nueva+lsCadena[liI];
		}
	}
	return lsCadena_Nueva;
}

/********************************************************************************************************
	Función que permite verificar si el contenido de una caja de texto corresponde con el tipo de datos 
	que realmente debe capturar. Esto se realiza cuando pierde el foco (onBlur).
********************************************************************************************************/
function fsLimpia(psCadena,psTipo)
{
	liTamano = psCadena.length;
	if(psTipo == 'texto')
	{
		laLetras = " ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo solo acepta letras ", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'numero')
	{
		numeros = "0123456789";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(numeros.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo no acepta letras ni caracetres especiales",'a');
				break;
			}
		}
	}
	else if(psTipo == 'texto_numero')
	{
		laLetras = " ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo solo acepta letras y números", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'clave')
	{/*REVISAR ESTA FUNCION PARA VER SI SE CAMBIA O SE DEJA POR LA DE TEXTO_NUMERO_SIMBOLOS*/
		laLetras = " .,;:-_*¡!#%&/()=+ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo solo acepta letras, números y los siguientes caracteres especiales: . , ; : - _ * ¡ ! # % & / ( ) = +", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'nombres')
	{
		laLetras = " 'ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo no acepta números, el único caracter especial permitido es el apostrofe '",'a');
				break;
			}
		}
	}
	else if(psTipo == 'numero_decimales')
	{
		numeros = ".0123456789";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(numeros.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo no acepta letras ni caracetres especiales, solo acepta el punto (.) como separador de decimales",'a');
				break;
			}
		}
	}
	else if(psTipo == 'numero_flotantes_coma')
	{
		numeros = ",0123456789";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(numeros.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo no acepta letras ni caracetres especiales, solo acepta la (,) como separador de decimales",'a');
				break;
			}
		}
	}
	else if(psTipo == 'numero_guion')
	{
		numeros = "-0123456789";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(numeros.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo no acepta letras ni caracetres especiales, solo acepta el punto (.) como separador de decimales",'a');
				break;
			}
		}
	}
	else if(psTipo == 'texto_numero_simbolo')
	{
		laLetras = " .,;:-_*¡!#%&/()=+ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo solo acepta letras, números y los siguientes caracteres especiales: . , ; : - _ * ¡ ! # % & / ( ) = +", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'texto_numero_simbolo_2')
	{//Validacion cuando el campo queremos que acepte mayúsculas y minúsculas por igual
		laLetras = '\n \r "<>.,;:-_*¡!#%&/()=+ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890abcdefghijklmnñopqrstuvwxyzáéíóúöü';
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo solo acepta letras y los siguientes caracteres especiales: . , ; : - _ * ¡ ! # % & / ( ) = +", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'enlace')
	{
		laLetras = " &?=.-_/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, este campo es solo para los enlaces ", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'documento')
	{
		laLetras = "VEPvep";
		for(liI = 0 ; liI < liTamano ; liI++)
		{
			if(laLetras.indexOf(psCadena[liI]) == -1)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, El documento de Identificación de una persona puede ser V, E o P <br>(V: Venezolano, E: Extranjero, P: Pasaporte)", 'a');
				break;
			}
		}
	}
	else if(psTipo == 'fecha')
	{
		lbError = false;
		if(liTamano == 10)
		{
			numeros = "-/1234567890";
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if(numeros.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de las fechas deben llelet el siguiente formato: DD-MM-AAAA y el separador puede ser: - o /",'a');
					lbError = true;
					break;
				}
			}
			
			if(lbError == false)
			{
				if(psCadena.substring(2,3) != "/" && psCadena.substring(2,3) != "-" )
				{
					fpMostrar_Mensaje("Disculpe, los campos de las fechas deben llelet el siguiente formato: DD-MM-AAAA y el separador puede ser: - o /",'a');
					psCadena='';
				}
				else if(psCadena.substring(5,6) != "/" && psCadena.substring(5,6) != "-")
				{
					fpMostrar_Mensaje("Disculpe, los campos de las fechas deben llelet el siguiente formato: DD-MM-AAAA y el separador puede ser: - o /",'a');
					psCadena='';
				}
			}
		}
		else
		{
			fpMostrar_Mensaje("Disculpe, los campos de las fechas deben llelet el siguiente formato: DD-MM-AAAA y el separador puede ser: - o /",'a');
			psCadena='';
		}
	}
	else if(psTipo == 'telefono')
	{
		/*if(liTamano == 12 && psCadena.indexOf('-') == 4)
		{*/
			numeros = "0123456789";
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if(numeros.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de teléfono solo permite números",'a');
					break;
				}
			}
		/*}
		else
		{
			fpMostrar_Mensaje("Disculpe, los campos de números telefonicos deben tener el siguiente formato: 0123-1234567",'a');
			psCadena='';
		}*/
	}
	else if(psTipo == 'rif2')
	{
		/*if(liTamano == 12 && psCadena.indexOf('-') == 4)
		{*/
			numeros = "JVGEPC-0123456789";
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if(numeros.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de este tipo solo permite JVGEPC-0123456789",'a');
					break;
				}
			}
		/*}
		else
		{
			fpMostrar_Mensaje("Disculpe, los campos de números telefonicos deben tener el siguiente formato: 0123-1234567",'a');
			psCadena='';
		}*/
	}
	else if(psTipo == 'rif')
	{
		lbError = false;
		if(liTamano == 12)
		{
			numeros = "JVGEPC-1234567890";
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if(numeros.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de rif deben llelet el siguiente formato: J-12345678-0",'a');
					lbError = true;
					break;
				}
			}
			
			if(lbError == false)
			{
				if(psCadena.substring(1,2) != "-")
				{
					fpMostrar_Mensaje("Disculpe, los campos de rif deben llelet el siguiente formato: J-12345678-0",'a');
					psCadena='';
				}
				else if(psCadena.substring(10,11) != "-")
				{
					fpMostrar_Mensaje("Disculpe, los campos de rif deben llelet el siguiente formato: J-12345678-0",'a');
					psCadena='';
				}
			}
		}
		else
		{
			fpMostrar_Mensaje("Disculpe, los campos de rif deben llelet el siguiente formato: J-12345678-0",'a');
			psCadena='';
		}
	}
	else if(psTipo == 'email')
	{
		let laLetras			= "@.,-_ÁÉÍÓÚÜABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		let laLetras2			= "@";
		let laLetras3			= ".";
		let liArroba			= 0;
		let liPunto				= 0;
		let liPosicion_Punto	= 0;
		let liPosicion_Arroba	= 0;
		if(psCadena.length > 9)
		{
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if (laLetras.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de correo electrónico deben llelet el siguiente formato: xxx@xxx.xxx",'a');
					break;
				}

				if (laLetras2.indexOf(psCadena[liI]) != -1)
				{
					liPosicion_Arroba = laLetras2.indexOf(psCadena[liI]);
					liArroba++;
				}

				if (laLetras3.indexOf(psCadena[liI]) != -1)
				{
					liPosicion_Punto = laLetras3.indexOf(psCadena[liI]);
					liPunto++;
				}
			}
			if(psCadena.lastIndexOf('.') < psCadena.lastIndexOf('@'))
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, los campos de correo electrónico deben llelet el siguiente formato: xxx@xxx.xxx",'a');
			}
		}
		else
		{
			psCadena='';
			fpMostrar_Mensaje("Disculpe, los campos de correo electrónico deben tener al menos 10 caracteres y llelet el siguiente formato: xxx@xxx.xxx",'a');
		}
	}
	else if(psTipo == 'placa')
	{
		if(liTamano == 7 && psCadena.indexOf(' ') == 3)
		{
			numeros = " ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if(numeros.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de placa solo permite numeros, letras y de separado un espacio",'a');
					break;
				}
			}
		}
		else
		{
			fpMostrar_Mensaje("Disculpe, los campos de placas de vehiculos deben tener el siguiente formato: XXX XXX",'a');
			psCadena='';
		}
	}
	else if(psTipo == 'horas')
	{
		if(liTamano == 5 && psCadena.indexOf(':') == 2)
		{
			numeros = ":1234567890";
			for(liI = 0 ; liI < liTamano ; liI++)
			{
				if(numeros.indexOf(psCadena[liI]) == -1)
				{
					psCadena='';
					fpMostrar_Mensaje("Disculpe, los campos de horas solo permite numeros y de separado dos puntos (:)",'a');
					break;
				}
			}
			let laHora = psCadena.split(":");
			if(Number(laHora[0]) < 0 || Number(laHora[0]) > 23)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, las horas deben estar comprendidas entre el: 0 y el 23",'a');
			}
			else if(Number(laHora[1]) < 0 || Number(laHora[1]) > 59)
			{
				psCadena='';
				fpMostrar_Mensaje("Disculpe, los minutos deben estar comprendidas entre el: 0 y el 59",'a');
			}
		}
		else
		{
			fpMostrar_Mensaje("Disculpe, los campos de Horas deben tener el siguiente formato: HH:MM",'a');
			psCadena='';
		}
	}
	return psCadena;
}

/****************************************************************************************************
	Funcion que permite verificar si una fecha es mayor que otra, recibiendo como parametro las cadenas de textos que 
	corresponden con las fechas de inicio y fin. El formato de las fechas debe ser DD-MM-AAAA
****************************************************************************************************/
function fbVerificar_Fechas(psFecha_Inicio, psFecha_Fin)
{
	let lbBueno		= true;
	let lsDia1		= psFecha_Inicio.substring(0,2);
	let lsMes1		= Number(psFecha_Inicio.substring(3,5))-1;
	let lsAno1		= psFecha_Inicio.substring(6,10);
	let loFecha1	= new Date(lsAno1,lsMes1,lsDia1);
	
	let lsDia2		= psFecha_Fin.substring(0,2);
	let lsMes2		= Number(psFecha_Fin.substring(3,5))-1;
	let lsAno2		= psFecha_Fin.substring(6,10);
	let loFecha2	= new Date(lsAno2,lsMes2,lsDia2);
	if(loFecha1 > loFecha2)
	{
		lbBueno = false;
	}
	return lbBueno;
}

/****************************************************************************************************
	Funcion que permite Calcular la edad de una persona pasando por parametros la fecha de nacimiento. El formato de la 
	fecha debe ser DD-MM-AAAA
****************************************************************************************************/
function fbCalcular_Edad(psFecha_Nacimiento)
{
	let liDia_Nacimiento	= psFecha_Nacimiento.substring(0,2);
	let liMes_Nacimiento	= Number(psFecha_Nacimiento.substring(3,5))-1;
	let liAno_Nacimiento	= psFecha_Nacimiento.substring(6,10);
	let loFecha_Nacimiento	= new Date(liAno_Nacimiento,liMes_Nacimiento,liDia_Nacimiento);
	
	let loFecha_Actual		= new Date();
	let liAno_Actual		= loFecha_Actual.getFullYear();
	let liMes_Actual		= loFecha_Actual.getMonth();
	let liDia_Actual		= loFecha_Actual.getDate();
	
	liDia_Nacimiento		= loFecha_Nacimiento.getDate();
	liMes_Nacimiento		= loFecha_Nacimiento.getMonth();
	liAno_Nacimiento		= loFecha_Nacimiento.getFullYear();
	
	let liEdad = liAno_Actual - liAno_Nacimiento;
	
	if ( liMes_Actual < liMes_Nacimiento)
	{
		liEdad--;
	}
	
	if ( ( liMes_Actual == liMes_Nacimiento) && (liDia_Actual < liDia_Nacimiento))
	{
		liEdad--;
	}
	return liEdad;
}

/****************************************************************************************************
	Funcion que permite pasar el foco a otro campo
****************************************************************************************************/
function fbCambiar_Foco(psTecla,psCampo_Focus)
{
	liKey = psTecla.keyCode || psTecla.which;
	if(liKey == 13 && psCampo_Focus != '')
	{
		document.querySelector('#'+psCampo_Focus).focus();
		return false;
	}
}
/********************************************	FIN DE AREA DE VALIDACIONES		********************************************/
