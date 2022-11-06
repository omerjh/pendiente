var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corPunto_Venta.php";
window.onload = () => {
	fpListar();
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Punto de Venta';
	loF.txtOperacion.value = 'incluir';
	$("#divFormulario").modal("show");
	setTimeout(() => { loF.txtNombre.focus(); }, 300);
}

/****************************************************************************************************
	Funcion que permite limpiar el formulario y ocultar el modal
****************************************************************************************************/
function fpCancelar()
{
	loF.txtOperacion.value	= "";
	loF.txtCodigo.value		= "";
	loF.txtNombre.value		= "";
	loF.cmbAlmacen.value	= "-";
	loF.cmbEstado.value		= "A";

	$('#cmbAlmacen').trigger('change.select2');
	for(liI = loF.txtFila.value ; liI > 0 ; liI--)
	{
		fpQuitar_Talonario(liI);
	}
	
	for(liI = loF.txtFila_Caja.value ; liI > 0 ; liI--)
	{
		fpQuitar_Caja(liI);
	}
	fpLimpiar_Busqueda_Talonario();
	fpLimpiar_Busqueda_Caja();
	$("#divFormulario").modal("hide");
}

/****************************************************************************************************
	Funcion que permite limpiar el área de busqueda de talonarios
****************************************************************************************************/
function fpLimpiar_Busqueda_Talonario()
{
	loF.cmbSistema.value	= '-';
	loF.cmbDocumento.value	= '-';
	loF.cmbTalonario.value	= '-';
	fpLimpiar_Combo('cmbDocumento');
	fpLimpiar_Combo('cmbTalonario');
	$('#cmbSistema, #cmbDocumento, #cmbTalonario').trigger('change.select2');
	loF.cmbSistema.focus();
}

/****************************************************************************************************
	Funcion que permite limpiar el área de busqueda de cajas
****************************************************************************************************/
function fpLimpiar_Busqueda_Caja()
{
	loF.cmbCaja.value	= '-';
	loF.txtOrden.value	= 1;
	$('#cmbCaja').trigger('change.select2');
	loF.cmbCaja.focus();
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
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Punto de Venta';

				//asignacion de valores
				loF.txtOperacion.value	= 'modificar';
				loF.txtNombre.value		= laResultado.laDatos.txtNombre;
				loF.cmbAlmacen.value	= laResultado.laDatos.cmbAlmacen;

				$('#cmbAlmacen').trigger('change.select2');
				let laTalonario = laResultado.laDatos.laTalonario;
				if(laResultado.laDatos.txtCantidad_Talonario >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Talonario ; liI++){
						fpAgregar_Talonario(laTalonario[liI])
					}
				}

				let laCaja = laResultado.laDatos.laCaja;
				if(laResultado.laDatos.txtCantidad_Caja >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Caja ; liI++){
						fpAgregar_Caja(laCaja[liI])
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
		fpMostrar_Mensaje("Disculpe, el nombre del punto de venta no puede estar en blanco",'a');
	}
	else if(loF.cmbAlmacen.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar el almacén",'a');
	}
	else if(Number(loF.txtFila.value) <= 0)
	{
		fpMostrar_Mensaje("Disculpe, debe agregar al menos un talonario",'a');
	}
	else if(Number(loF.txtFila_Caja.value) <= 0)
	{
		fpMostrar_Mensaje("Disculpe, debe agregar al menos una caja",'a');
	}
	else
	{
		lbBueno = true;
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
		lsTitulos			: 'Código,Nombre,Almacén,Estado,Opciones',
		lsPorcentajes		: '10%,40%,30%,10%,10%',
		lsAlineacion		: 'right,left,left,left,left',
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
			posicion_condicion	: 3,
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
			posicion_condicion	: 3,
			valor_condicion		: 'Inactivo'
		}
	}
	fpImprimir_Listado(laDatos, laFormulario, laBotones, gsUrl);
}

/****************************************************************************************************
	Funcion que valida si al agregar talonario se hace desde un arreglo, el cual pasa sin validar
	ningún dato debido a que ya están guardados en la base de datos, si no se pasan los datos por
	parámetros entonces se validan los campos que buscan los talonarios
****************************************************************************************************/
function fbValidar_Talonario(paDatos)
{
	let lbBueno = false;
	if( paDatos != '')
	{
		lbBueno = true;
	}
	else
	{
		if(loF.cmbSistema.value == "-")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un sistema",'a');
		}
		else if(loF.cmbDocumento.value == "-")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un documento",'a');
		}
		else if(loF.cmbTalonario.value == "-")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un talonario",'a');
		}
		else
		{
			let liFila		= Number(loF.txtFila.value);
			let liI			= 1;
			let liEncontrado= 0;
			for(liI = 1 ; liI <= liFila ; liI++)
			{
				if(Number(document.querySelector("#cmbTalonario").value) == Number(document.querySelector("#txtTalonario_"+liI).value)){
					liEncontrado++;
				}
			}

			if(liEncontrado == 0){
				lbBueno = true;
			}else{
				fpMostrar_Mensaje("Disculpe, el talonario ya fue agregado",'a');
				fpLimpiar_Busqueda_Talonario();
			}
		}
	}
	return lbBueno;
}

/****************************************************************************************************
	Funcion que agrega una linea a la tabla de talonarios
****************************************************************************************************/
function fpAgregar_Talonario(paDatos)
{
	let laDatos	= paDatos || '';
	if(fbValidar_Talonario(laDatos) == true)
	{
		let liFila	= Number(loF.txtFila.value)+1;
		let loTabla	= document.querySelector("#tabTalonario");
		let loFila	= loTabla.insertRow(liFila);
		let loCelda1= loFila.insertCell(0);
		let loCelda2= loFila.insertCell(1);
		let loCelda3= loFila.insertCell(2);
		let loCelda4= loFila.insertCell(3);

		loCelda1.id = 'tdCelda_a'+liFila;
		loCelda2.id = 'tdCelda_b'+liFila;
		loCelda3.id = 'tdCelda_c'+liFila;
		loCelda4.id = 'tdCelda_d'+liFila;

		let liSistema	= '';
		let liDocumento	= '';
		let liTalonario	= '';
		let lsSistema	= '';
		let lsDocumento	= '';
		let lsTalonario	= '';

		if(laDatos != '')
		{
			liSistema	= laDatos.liSistema;
			liDocumento	= laDatos.liDocumento;
			liTalonario	= laDatos.liTalonario;
			lsSistema	= laDatos.lsSistema;
			lsDocumento	= laDatos.lsDocumento;
			lsTalonario	= laDatos.lsTalonario;
		}
		else
		{
			liSistema	= loF.cmbSistema.value;
			liDocumento	= loF.cmbDocumento.value;
			liTalonario	= loF.cmbTalonario.value;
			lsSistema	= loF.cmbSistema.options[loF.cmbSistema.selectedIndex].text;
			lsDocumento	= loF.cmbDocumento.options[loF.cmbDocumento.selectedIndex].text;
			lsTalonario	= loF.cmbTalonario.options[loF.cmbTalonario.selectedIndex].text;
		}

		//Creación de objetos
		let loSistema			= foCrear_Objeto('input', 'hidden', 'txtSistema_'+liFila, liSistema);
		let loDocumento			= foCrear_Objeto('input', 'hidden', 'txtDocumento_'+liFila, liDocumento);
		let loTalonario			= foCrear_Objeto('input', 'hidden', 'txtTalonario_'+liFila, liTalonario);
		let loSerie				= foCrear_Objeto('input', 'hidden', 'txtSerie_'+liFila, lsTalonario);
		let loQuitar			= foCrear_Objeto('button', 'button', 'btnQuitar'+liFila, '', 'btn btn-danger');
		let loLi_Quitar			= document.createElement("li");
		loLi_Quitar.className	= "fa fa-minus";

		// //Asignación de valores o atributos adicionales
		loQuitar.setAttribute("onclick","fpQuitar_Talonario("+liFila+");");
		loQuitar.append(loLi_Quitar);

		//averiguar para que sirve sino borrar
		let loSpan_Sistema			= foCrear_Objeto('span', '', 'Span_Sistema_'+liFila);
		loSpan_Sistema.innerHTML	= lsSistema;

		loTexto_Celda2 = document.createTextNode(lsDocumento);
		loTexto_Celda3 = document.createTextNode(lsTalonario);

		loCelda1.append(loSpan_Sistema, loSistema, loDocumento, loTalonario, loSerie);
		loCelda2.append(loTexto_Celda2);
		loCelda3.append(loTexto_Celda3);
		loCelda4.append(loQuitar);

		loF.txtFila.value = liFila;

		fpLimpiar_Busqueda_Talonario();
	}
}

/****************************************************************************************************
	Funcion que elimina el contenido de una fila de la tabla talonario
****************************************************************************************************/
function fpQuitar_Talonario(piLinea)
{
	let liFila	= Number(loF.txtFila.value);
	let liLinea	= liFila + 1;
	let liI		= 0;
	let liS		= 0;

	for(liI = piLinea ; liI < liFila ; liI++)
	{
		liS = liI + 1;
		document.querySelector("#txtSistema_"+liI).value		= document.querySelector("#txtSistema_"+liS).value;
		document.querySelector("#txtDocumento_"+liI).value		= document.querySelector("#txtDocumento_"+liS).value;
		document.querySelector("#txtTalonario_"+liI).value		= document.querySelector("#txtTalonario_"+liS).value;

		document.getElementById("Span_Sistema_"+liI).innerHTML	= document.getElementById("Span_Sistema_"+liS).innerHTML;
		document.getElementById("tdCelda_b"+liI).innerHTML 		= document.getElementById("tdCelda_b"+liS).innerHTML
		document.getElementById("tdCelda_c"+liI).innerHTML 		= document.getElementById("tdCelda_c"+liS).innerHTML
	}

	loF.txtFila.value = liFila - 1;
	document.querySelector("#tabTalonario").deleteRow(liLinea - 1)
}

/****************************************************************************************************
	Funcion que valida si al agregar cajas se hace desde un arreglo, el cual pasa sin validar
	ningún dato debido a que ya están guardados en la base de datos, si no se pasan los datos por
	parámetros entonces se validan los campos que buscan los cajas
****************************************************************************************************/
function fbValidar_Caja(paDatos)
{
	let lbBueno = false;
	if( paDatos != '')
	{
		lbBueno = true;
	}
	else
	{
		if(loF.cmbCaja.value == "-")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un caja",'a');
		}
		else if(loF.txtOrden.value == "")
		{
			fpMostrar_Mensaje("Disculpe, el numero en orde no puede estar en blanco",'a');
		}
		else if(Number(loF.txtOrden.value) < 0)
		{
			fpMostrar_Mensaje("Disculpe, el numero en orde no puede ser menor a cero",'a');
		}
		else
		{
			let liFila		= Number(loF.txtFila_Caja.value);
			let liI			= 1;
			let liEncontrado= 0;
			for(liI = 1 ; liI <= liFila ; liI++)
			{
				if(Number(document.querySelector("#cmbCaja").value) == Number(document.querySelector("#txtCaja_"+liI).value)){
					liEncontrado++;
				}
			}

			if(liEncontrado == 0){
				lbBueno = true;
			}else{
				fpMostrar_Mensaje("Disculpe, la caja ya fue agregada",'a');
				fpLimpiar_Busqueda_Caja();
			}
		}
	}
	return lbBueno;
}

/****************************************************************************************************
	Funcion que agrega una linea a la tabla de cajas
****************************************************************************************************/
function fpAgregar_Caja(paDatos)
{
	let laDatos	= paDatos || '';
	if(fbValidar_Caja(laDatos) == true)
	{
		let liFila	= Number(loF.txtFila_Caja.value)+1;
		let loTabla	= document.querySelector("#tabCaja");
		let loFila	= loTabla.insertRow(liFila);
		let loCelda1= loFila.insertCell(0);
		let loCelda2= loFila.insertCell(1);
		let loCelda3= loFila.insertCell(2);

		loCelda1.id = 'tdCelda_Caja_a'+liFila;
		loCelda2.id = 'tdCelda_Caja_b'+liFila;
		loCelda3.id = 'tdCelda_Caja_c'+liFila;

		loCelda2.className = 'text-right';
		
		let liCaja	= '';
		let lsCaja	= '';
		let liOrden	= '';

		if(laDatos != '')
		{
			liCaja	= laDatos.liCaja;
			lsCaja	= laDatos.lsCaja;
			liOrden	= laDatos.liOrden;
		}
		else
		{
			liCaja	= loF.cmbCaja.value;
			lsCaja	= loF.cmbCaja.options[loF.cmbCaja.selectedIndex].text;
			liOrden	= loF.txtOrden.value;
		}

		//Creación de objetos
		let loCaja				= foCrear_Objeto('input', 'hidden', 'txtCaja_'+liFila, liCaja);
		let loCaja_Nombre		= foCrear_Objeto('input', 'hidden', 'txtCaja_Nombre_'+liFila, liCaja);
		let loOrden				= foCrear_Objeto('input', 'hidden', 'txtOrden_'+liFila, liOrden);
		let loQuitar			= foCrear_Objeto('button', 'button', 'btnQuitar_Caja'+liFila, '', 'btn btn-danger');
		let loLi_Quitar			= document.createElement("li");
		loLi_Quitar.className	= "fa fa-minus";

		// //Asignación de valores o atributos adicionales
		loQuitar.setAttribute("onclick","fpQuitar_Caja("+liFila+");");
		loQuitar.append(loLi_Quitar);

		//averiguar para que sirve sino borrar
		let loSpan_Caja			= foCrear_Objeto('span', '', 'Span_Caja_'+liFila);
		loSpan_Caja.innerHTML	= lsCaja;
		loTexto_Celda2			= document.createTextNode(liOrden);

		loCelda1.append(loSpan_Caja, loCaja, loCaja_Nombre, loOrden);
		loCelda2.append(loTexto_Celda2);
		loCelda3.append(loQuitar);

		loF.txtFila_Caja.value = liFila;

		fpLimpiar_Busqueda_Caja();
	}
}

/****************************************************************************************************
	Funcion que elimina el contenido de una fila de la tabla caja
****************************************************************************************************/
function fpQuitar_Caja(piLinea)
{
	let liFila	= Number(loF.txtFila_Caja.value);
	let liLinea	= liFila + 1;
	let liI		= 0;
	let liS		= 0;

	for(liI = piLinea ; liI < liFila ; liI++)
	{
		liS = liI + 1;
		document.querySelector("#txtCaja_"+liI).value			= document.querySelector("#txtCaja_"+liS).value;
		document.querySelector("#txtCaja_Nombre_"+liI).value	= document.querySelector("#txtCaja_Nombre_"+liS).value;
		document.querySelector("#txtOrden_"+liI).value			= document.querySelector("#txtOrden_"+liS).value;
		
		document.getElementById("Span_Caja_"+liI).innerHTML		= document.getElementById("Span_Caja_"+liS).innerHTML;
		document.querySelector("#tdCelda_Caja_b"+liI).innerHTML	= document.querySelector("#tdCelda_Caja_b"+liS).innerHTML;
	}

	loF.txtFila_Caja.value = liFila - 1;
	document.querySelector("#tabCaja").deleteRow(liLinea - 1)
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
	código del talonario y que al momento de buscar lo haga por coincidencia del nombre y no por código
****************************************************************************************************/
function fpVerificar_Punto_Venta()
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
