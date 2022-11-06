var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corUsuario.php";
window.onload = () => {
	fpListar();
	fpCancelar();
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Usuario';
	loF.txtOperacion.value = 'incluir';
	$("#divFormulario").modal("show");
	setTimeout(() => { loF.txtNombre.focus(); }, 300);
}

/****************************************************************************************************
	Funcion que permite limpiar el formulario y ocultar el modal
****************************************************************************************************/
function fpCancelar()
{
	loF.txtOperacion.value			= "";
	loF.txtCodigo.value				= "";
	loF.txtNombre.value				= "";
	loF.txtNombre_Persona.value		= "";
	loF.txtClave.value				= "";
	loF.txtRepita_Clave.value		= "";
	loF.cmbPregunta1.value			= "-";
	loF.txtRespuesta1.value			= "";
	loF.cmbPregunta2.value			= "-";
	loF.txtRespuesta2.value			= "";
	loF.cmbRol.value				= "-";
	loF.cmbEstado.value				= "A";
	loF.txtUsuario_Registrado.value = "";
	document.querySelector("#divMensaje_Usuario").classList.add("d-none");

	$('#cmbRol').trigger('change.select2');
	$('#cmbPregunta1, #cmbPregunta2').trigger('change.select2');
	for(liI = loF.txtFila.value ; liI > 0 ; liI--)
	{
		fpQuitar_Punto_Venta(liI);
	}
	fpLimpiar_Busqueda_Punto_Venta();
	
	$("#divFormulario").modal("hide");
	loF.txtCodigo.focus();
}

/****************************************************************************************************
	Funcion que permite limpiar el área de busqueda de punto de venta
****************************************************************************************************/
function fpLimpiar_Busqueda_Punto_Venta()
{
	loF.cmbPunto_Venta.value= '-';
	loF.radDefecto1.checked	= false;
	loF.radDefecto2.checked	= true;
	$('#cmbPunto_Venta').trigger('change.select2');
	loF.cmbPunto_Venta.focus();
}

/****************************************************************************************************
	Funcion que busca un registro a partir de su codigo y si lo encuentra muestra el formulario para 
	su actualización
****************************************************************************************************/
function fpBuscar(piCodigo)
{
	if(piCodigo >= 0)
	{
		loF.txtCodigo.value 	= piCodigo;
		loF.txtOperacion.value	= 'buscar';
		//Se declara una constante con el objeto obtenido desde el formulario
		const loFormulario = new FormData(document.querySelector('#frmF'));
		
		fpEnviar(loFormulario, laResultado => {
			if(laResultado.lbEstado == true)
			{
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Usuario';
				
				//asignacion de valores
				loF.txtOperacion.value		= 'modificar';
				loF.txtNombre.value			= laResultado.laDatos.txtNombre;
				loF.txtNombre_Persona.value	= laResultado.laDatos.txtNombre_Persona;
				loF.cmbRol.value			= laResultado.laDatos.cmbRol;
				loF.txtRol_Anterior.value	= laResultado.laDatos.cmbRol;
				loF.cmbEstado.value			= laResultado.laDatos.cmbEstatus;
				
				$('#cmbRol').trigger('change.select2');
				let laPunto_Venta = laResultado.laDatos.laPunto_Venta;
				if(laResultado.laDatos.txtCantidad_Punto_Venta >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Punto_Venta ; liI++){
						fpAgregar_Punto_Venta(laPunto_Venta[liI])
					}
				}

				setTimeout(() => { loF.txtNombre.focus(); }, 300);
				$("#divFormulario").modal("show");
			}
			else
			{
				fpMostrar_Mensaje(laResultado.lsMensaje, '-');
				fpCancelar();
			}
		});
	}
	else
	{
		fpMostrar_Mensaje('Disculpe, el codigo que intenta buscar es incorrecto', '-');
		fpCancelar();
	}
}

/****************************************************************************************************
	Funcion que permite verificar si el formulario supera las validaciones y de ser cierto, envia los
	datos al controlador
****************************************************************************************************/
function fpGuardar()
{
	if( fbValidar() )
	{
		//Se declara una constante con el objeto obtenido desde el formulario
		const loFormulario = new FormData(document.querySelector('#frmF'));
		fpEnviar(loFormulario, laResultado => {
			if(laResultado.lbEstado == true)
			{
				fpCancelar();
				fpMostrar_Mensaje(laResultado.lsMensaje, '+');
				fpListar();
			}
			else
			{
				fpMostrar_Mensaje(laResultado.lsMensaje, '-');
			}
		});
	}
}

/****************************************************************************************************
	Funcion que permite validar el formulario
****************************************************************************************************/
function fbValidar()
{
	let lbBueno = false; 
	if(loF.txtCodigo.value == "" && loF.txtOperacion.value != "incluir")
	{
		fpMostrar_Mensaje("Disculpe, el código no puede estar en blanco",'a');
	}
	else if(loF.txtNombre.value == "")
	{
		fpMostrar_Mensaje("Disculpe, el nombre del usuario no puede estar en blanco",'a');
	}
	else if(loF.txtClave.value == "" && loF.txtOperacion.value == "incluir")
	{
		fpMostrar_Mensaje("Disculpe, debe ingresar la contraseña nueva",'a');
	}
	else if(loF.txtClave.value.length < 8 && loF.txtOperacion.value == "incluir")
	{
		fpMostrar_Mensaje("Disculpe, la nueva contraseña debe constar de al menos 8 caracteres",'a');
		loF.txtClave.value = "";
		loF.txtRepita_Clave.value = "";
	}
	else if(loF.txtRepita_Clave.value != loF.txtClave.value && loF.txtClave.value != '')
	{
		fpMostrar_Mensaje("Disculpe, debe coincidir la nueva contraseña ",'a');
		loF.txtRepita_Clave.value = "";
	}
	else if(loF.cmbPregunta1.value == "-" && loF.txtOperacion.value == "incluir")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la pregunta de seguridad No. 1 ",'a');
	}
	else if(loF.txtRespuesta1.value == "" && loF.txtOperacion.value == "incluir")
	{
		fpMostrar_Mensaje("Disculpe, debe colocar la respuesta a la pregunta de seguridad No. 1",'a');
	}
	else if(loF.cmbPregunta2.value == "-" && loF.txtOperacion.value == "incluir")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la pregunta de seguridad No. 2 ",'a');
	}
	else if(loF.txtRespuesta2.value == "" && loF.txtOperacion.value == "incluir")
	{
		fpMostrar_Mensaje("Disculpe, debe colocar la respuesta a la pregunta de seguridad No. 2",'a');
	}
	else if(loF.txtUsuario_Registrado.value == "S")
	{
		fpMostrar_Mensaje("Disculpe, el nombre del usuario no esta disponible, debe elegir otro",'a');
	}
	else if(loF.cmbRol.value == "-")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar el rol",'a');
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
		if(liContador == 0 && loF.txtOperacion.value == "incluir")
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
			if(liContador == 0 && loF.txtOperacion.value == "incluir")
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

/****************************************************************************************************
	Funcion que imprime el listado de registro que coinciden con el formulario del filtro
****************************************************************************************************/
function fpListar(piPagina)
{
	loFiltro.txtFiltro_Pagina.value = piPagina || 1;
	let laDatos = {
		txtOperacion		: 'listar',
		lsTitulos			: 'Código,Usuario,Nombre,Rol,Estatus,Opciones',
		lsTitles			: 'Código,Usuario,Nombre,Rol,Estatus,Opciones',
		lsPorcentajes		: '10%,25%,25%,20%,10%,10%',
		lsAlineacion		: 'right,left,left,left',
		lsCapa_Listado		: 'divListado',
		lsCapa_Paginado		: 'divPaginado',
		lsTabla_Interna		: 'tabListado',
		lsFondo_Encabezado	: 'bg-info',
		txtArchivo			: loF.txtArchivo.value,
		lbPie_Tabla			: true
	}
	let laFormulario = faObtener_Datos_Formulario('frmFiltro');
	
	let laBotones = {
		0: {
			nombre			: 'btnBuscar',
			onClick			: 'fpBuscar',
			title			: 'btnBuscar',
			icono			: 'fas fa-pencil-alt',
			color			: 'btn-primary',
			tipo			: 'boton',
			posicion_valor	: 0
		},
		1: {
			nombre				: 'btnEliminar',
			onClick				: 'fpPreguntar_Desactivar',
			title				: 'btnEliminar',
			icono				: 'far fa-trash-alt',
			color				: 'btn-danger',
			tipo				: 'boton',
			posicion_valor		: 0,
			posicion_condicion	: 4,
			valor_condicion		: 'Activo'
			
		},
		2: {
			nombre				: 'btnActivar',
			onClick				: 'fpPreguntar_Activar',
			title				: 'btnActivar',
			icono				: 'fas fa-redo',
			color				: 'btn-info',
			tipo				: 'boton',
			posicion_valor		: 0,
			posicion_condicion	: 4,
			valor_condicion		: 'Inactivo'
		}
	}
	fpImprimir_Listado(laDatos, laFormulario, laBotones, gsUrl);
}

/****************************************************************************************************
	Funcion que valida si al agregar el punto de venta se hace desde un arreglo, el cual pasa sin 
	validar ningún dato debido a que ya están guardados en la base de datos, si no se pasan los datos
	por parámetros entonces se validan los campos que buscan los puntos de ventas
****************************************************************************************************/
function fbValidar_Punto_Venta(paDatos)
{
	let lbBueno = false;
	if( paDatos != '')
	{
		lbBueno = true;
	}
	else
	{
		if(loF.cmbPunto_Venta.value == "-")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un punto de venta",'a','cmbPunto_Venta');
		}
		else if(loF.radDefecto1.checked == false && loF.radDefecto2.checked == false)
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar si el punto de venta es el que estará por defecto",'a','cmbPunto_Venta');
		}
		else
		{
			let liFila			= Number(loF.txtFila.value);
			let liI				= 1;
			let liEncontrado	= 0;
			let liEncontrado2	= 0;
			for(liI = 1 ; liI <= liFila ; liI++)
			{
				if(Number(document.querySelector("#cmbPunto_Venta").value) == Number(document.querySelector("#txtPunto_Venta_Codigo_"+liI).value)){
					liEncontrado++;
				}
				
				if(document.querySelector("#radDefecto1").checked == true && document.querySelector("#txtDefecto_"+liI).value == 'S'){
					liEncontrado2++;
				}
			}

			if(liEncontrado == 0 && liEncontrado2 == 0){
				lbBueno = true;
			}
			else if(liEncontrado2 > 0)
			{
				fpMostrar_Mensaje("Disculpe, ya fue seleccionado el punto de venta por defecto",'a','cmbPunto_Venta');
				fpLimpiar_Busqueda_Punto_Venta();
			}
			else
			{
				fpMostrar_Mensaje("Disculpe, el punto de venta ya fue agregado",'a','cmbPunto_Venta');
				fpLimpiar_Busqueda_Punto_Venta();
			}
		}
	}
	return lbBueno;
}

/****************************************************************************************************
	Funcion que agrega una linea a la tabla de puntos de ventas 
****************************************************************************************************/
function fpAgregar_Punto_Venta(paDatos)
{
	let laDatos	= paDatos || '';
	if(fbValidar_Punto_Venta(laDatos) == true)
	{
		let liFila	= Number(loF.txtFila.value)+1;
		let loTabla	= document.querySelector("#tabPunto_Venta");
		let loFila	= loTabla.insertRow(liFila);
		let loCelda1= loFila.insertCell(0);
		let loCelda2= loFila.insertCell(1);
		let loCelda3= loFila.insertCell(2);

		loCelda1.id = 'tdCelda_a'+liFila;
		loCelda2.id = 'tdCelda_b'+liFila;
		loCelda3.id = 'tdCelda_c'+liFila;

		let liPunto_Venta	= '';
		let lsPunto_Venta	= '';
		let lsDefecto		= '';

		if(laDatos != '')
		{
			liPunto_Venta	= laDatos.liPunto_Venta;
			lsPunto_Venta	= laDatos.lsPunto_Venta;
			lsDefecto		= laDatos.lsDefecto;
		}
		else
		{
			liPunto_Venta	= loF.cmbPunto_Venta.value;
			lsPunto_Venta	= loF.cmbPunto_Venta.options[loF.cmbPunto_Venta.selectedIndex].text;
			lsDefecto		= loF.radDefecto1.checked == true ? 'S' : 'N';
		}

		//Creación de objetos
		let loPunto_Venta_Codigo= foCrear_Objeto('input', 'hidden', 'txtPunto_Venta_Codigo_'+liFila, liPunto_Venta);
		let loPunto_Venta_Nombre= foCrear_Objeto('input', 'hidden', 'txtPunto_Venta_Nombre_'+liFila, liPunto_Venta);
		let loDefecto			= foCrear_Objeto('input', 'hidden', 'txtDefecto_'+liFila, lsDefecto);
		let loQuitar			= foCrear_Objeto('button', 'button', 'btnQuitar'+liFila, '', 'btn btn-danger');
		let loLi_Quitar			= document.createElement("li");
		loLi_Quitar.className	= "fa fa-minus";

		// //Asignación de valores o atributos adicionales
		loQuitar.setAttribute("onclick","fpQuitar_Punto_Venta("+liFila+");");
		loQuitar.append(loLi_Quitar);

		//averiguar para que sirve sino borrar
		let loSpan_Punto_Venta			= foCrear_Objeto('span', '', 'Span_Punto_Venta_'+liFila);
		loSpan_Punto_Venta.innerHTML	= lsPunto_Venta;
		
		lsDefecto = lsDefecto == 'S' ? 'Si' : 'No';
		loTexto_Celda2 = document.createTextNode(lsDefecto);

		loCelda1.append(loSpan_Punto_Venta, loPunto_Venta_Codigo, loPunto_Venta_Nombre, loDefecto);
		loCelda2.append(loTexto_Celda2);
		loCelda3.append(loQuitar);

		loF.txtFila.value = liFila;

		fpLimpiar_Busqueda_Punto_Venta();
	}
}

/****************************************************************************************************
	Funcion que elimina el contenido de una fila de la tabla de punto de venta
****************************************************************************************************/
function fpQuitar_Punto_Venta(piLinea)
{
	let liFila	= Number(loF.txtFila.value);
	let liLinea	= liFila + 1;
	let liI		= 0;
	let liS		= 0;

	for(liI = piLinea ; liI < liFila ; liI++)
	{
		liS = liI + 1;
		document.querySelector("#txtPunto_Venta_Codigo_"+liI).value	=document.querySelector("#txtPunto_Venta_Codigo_"+liS).value;
		document.querySelector("#txtPunto_Venta_Nombre_"+liI).value	=document.querySelector("#txtPunto_Venta_Nombre_"+liS).value;
		document.querySelector("#txtDefecto_"+liI).value			=document.querySelector("#txtDefecto_"+liS).value;

		document.getElementById("Span_Punto_Venta_"+liI).innerHTML	= document.getElementById("Span_Punto_Venta_"+liS).innerHTML;
		document.getElementById("tdCelda_b"+liI).innerHTML 			= document.getElementById("tdCelda_b"+liS).innerHTML
	}

	loF.txtFila.value = liFila - 1;
	document.querySelector("#tabPunto_Venta").deleteRow(liLinea - 1)
}

/****************************************************************************************************
	Funcion que verifica si el nombre de usuario esta disponible y muestra un mensaje en caso de que 
	no lo este
****************************************************************************************************/
function fpVerificar_Nombre_Usuario(poCampo)
{
	document.querySelector("#divMensaje_Usuario").classList.add("d-none");
	fsBorrar_Espacios(poCampo,'texto_numero_simbolo');
	if(poCampo.value != '')
	{
		loF.txtOperacion.value	= 'verificar_usuario';
		//Se declara una constante con el objeto obtenido desde el formulario
		const loFormulario = new FormData(document.querySelector('#frmF'));
		
		fpEnviar(loFormulario, laResultado => {
			loF.txtOperacion.value	= loF.txtCodigo.value != '' ? 'modificar' : 'incluir';
			if(laResultado.lbEstado == true)
			{
				loF.txtUsuario_Registrado.value = 'N';
			}
			else
			{
				loF.txtUsuario_Registrado.value = 'S';
				document.querySelector("#divMensaje_Usuario").classList.remove("d-none");
			}
		});
	}
}

/****************************************************************************************************
	Funcion que envia el formulario del filtro al archivo que generará el reporte en formato excel
****************************************************************************************************/
function fpGenerar_Excel()
{
	loFiltro.submit();
}

/****************************************************************************************************
	Funcion que verifica si el campo que se usa en el autocomplete esta en blanco para limpiar el 
	código del módulo y que al momento de buscar lo haga por coincidencia del nombre y no por código
****************************************************************************************************/
function fpVerificar_Usuario()
{
	if(loFiltro.txtFiltro_Nombre.value == ''){
		loFiltro.txtFiltro_Codigo.value='';
	}
}

/****************************************************************************************************
	Funcion que imprime una lista con los registros que coinciden con lo escrito por el usuario
****************************************************************************************************/
$( "#txtFiltro_Nombre" ).autocomplete({
	minLength: 1,
	source: function(request, response) {
		$.ajax({
			type: "POST",
			url: gsUrl,
			dataType: "JSON",
			data: {
				txtBuscar:		request.term,
				txtOperacion:	'autocompletado',
				txtArchivo:		loF.txtArchivo.value
			},
			success: function(data) {
				response(data);
			}
		})
	},
	select: function( event, ui ) {
		loFiltro.txtFiltro_Codigo.value = ui.item.codigo;
		loFiltro.txtFiltro_Nombre.value = ui.item.label;
		fpListar();
	}
});
