var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corProducto.php";
window.onload = () => {
	fpListar();
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Producto';
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
	loF.txtDescripcion.value		= "";
	loF.cmbTipo_Impuesto.value		= "";
	loF.cmbTipo_Producto.value		= "";
	loF.cmbCategoria.value			= "";
	loF.cmbMarca.value				= "";
	loF.cmbEstado.value				= "A";
	loF.cmbUnidad_Base.value		= "";
	loF.txtFactor_Base.value		= "1.00";
	loF.txtCodigo_Barras_Base.value	= "";
	loF.txtPrecio_Compra_Base.value	= "";
	loF.txtPrecio_Venta_Base.value	= "";
	
	$('#cmbTipo_Impuesto, #cmbTipo_Producto, #cmbCategoria, #cmbMarca, #cmbUnidad_Base').trigger('change.select2');
	for(liI = loF.txtFila.value ; liI > 0 ; liI--)
	{
		fpQuitar_Presentacion_Alternativa(liI);
	}
	$("#divFormulario").modal("hide");
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
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Producto';
				
				//asignacion de valores
				loF.txtOperacion.value			= 'modificar';
				loF.txtNombre.value				= laResultado.laDatos.txtNombre;
				loF.txtDescripcion.value		= laResultado.laDatos.txtDescripcion;
				loF.cmbTipo_Impuesto.value		= laResultado.laDatos.cmbTipo_Impuesto;
				loF.cmbTipo_Producto.value		= laResultado.laDatos.cmbTipo_Producto;
				loF.cmbCategoria.value			= laResultado.laDatos.cmbCategoria;
				loF.cmbMarca.value				= laResultado.laDatos.cmbMarca;
				loF.cmbEstado.value				= laResultado.laDatos.cmbEstatus;
				loF.txtCodigo_Presentacion.value= laResultado.laDatos.txtCodigo_Presentacion;
				loF.cmbUnidad_Base.value		= laResultado.laDatos.cmbUnidad_Base;
				loF.txtFactor_Base.value		= "1.00";
				loF.txtCodigo_Barras_Base.value	= laResultado.laDatos.txtCodigo_Barras_Base;
				loF.txtPrecio_Compra_Base.value	= laResultado.laDatos.txtPrecio_Compra_Base;
				loF.txtPrecio_Venta_Base.value	= laResultado.laDatos.txtPrecio_Venta_Base;
				
				$('#cmbTipo_Impuesto, #cmbTipo_Producto, #cmbCategoria, #cmbMarca, #cmbUnidad_Base').trigger('change.select2');
				let laPresentacion = laResultado.laDatos.laPresentacion;
				if(laResultado.laDatos.txtCantidad_Presentacion >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Presentacion ; liI++){
						fpAgregar_Presentacion_Alternativa(laPresentacion[liI])
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
		fpMostrar_Mensaje("Disculpe, el nombre del producto no puede estar en blanco",'a');
	}
	else if(loF.cmbTipo_Impuesto.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar el tipo de impuesto del producto",'a');
	}
	else if(loF.cmbTipo_Producto.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar el tipo de producto",'a');
	}
	else if(loF.cmbCategoria.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la categoría",'a');
	}
	else if(loF.cmbMarca.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la marca",'a');
	}
	else if(loF.cmbUnidad_Base.value == "")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar la marca",'a');
	}
	else if(loF.txtPrecio_Compra_Base.value == "")
	{
		fpMostrar_Mensaje("Disculpe, el precio de compra de la unidad principal no puede estar en blanco",'a');
	}
	else if(loF.txtPrecio_Venta_Base.value == "")
	{
		fpMostrar_Mensaje("Disculpe, el precio de venta de la unidad principal no puede estar en blanco",'a');
	}
	else
	{
		let laUnidad			= [];
		let loUnidad			= '';
		let liError_Duplicado	= 0;
		let liError_Datos		= 0;
		let liI					= 1;
		for(liI = 1 ; liI <= Number(loF.txtFila.value) ; liI++)
		{
			if(document.querySelector("#cmbUnidad_Alternativa_"+liI).value != ''){
				if(laUnidad.includes(document.querySelector("#cmbUnidad_Alternativa_"+liI).value))
				{
					loUnidad = document.querySelector("#cmbUnidad_Alternativa_"+liI);
					liError_Duplicado++
				}
				else
				{
					laUnidad.push(document.querySelector("#cmbUnidad_Alternativa_"+liI).value);
				}
				if(	document.querySelector("#txtFactor_Alternativa_"+liI).value == '' ||
					document.querySelector("#txtPrecio_Compra_Alternativa_"+liI).value == '' ||
					document.querySelector("#txtPrecio_Venta_Alternativa_"+liI).value == ''
				){
					loUnidad = document.querySelector("#cmbUnidad_Alternativa_"+liI);
					liError_Datos++;
					break;
				}
			}			
		}
		
		if(liError_Duplicado > 0)
		{
			fpMostrar_Mensaje("Disculpe, la unidad: "+ loUnidad.options[loUnidad.selectedIndex].text +' se encuentra duplicada','a');
		}
		else if(liError_Datos > 0)
		{
			fpMostrar_Mensaje("Disculpe, debe llenar todos los datos de la unidad: "+ loUnidad.options[loUnidad.selectedIndex].text,'a');
		}
		else
		{
			lbBueno = true;
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
		lsTitulos			: 'Código,Nombre,Tipo,Unidad,Estado,Opciones',
		lsPorcentajes		: '10%,50%,20%,10%,10%',
		lsAlineacion		: 'right,left,left,left,right,left,left',
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
	Funcion que agrega una linea a la tabla de presentación alternativa
****************************************************************************************************/
function fpAgregar_Presentacion_Alternativa(paDatos)
{
	document.getElementById("tabUnidad_Presentacion").classList.remove("d-none");
	
	let laDatos	= paDatos || '';
	let liFila	= Number(loF.txtFila.value)+1;
	let loTabla	= document.querySelector("#tabUnidad_Presentacion");
	let loFila	= loTabla.insertRow(liFila);
	let loCelda1= loFila.insertCell(0);
	let loCelda2= loFila.insertCell(1);
	let loCelda3= loFila.insertCell(2);
	let loCelda4= loFila.insertCell(3);
	let loCelda5= loFila.insertCell(4);
	let loCelda6= loFila.insertCell(5);
	
	let liUnidad		= '';
	let lfFactor		= '';
	let lsCodigo_Barras	= '';
	let lfPrecio_Compra	= '';
	let lfPrecio_Venta	= '';
	
	if(laDatos != ''){
		liUnidad		= laDatos.cmbUnidad;
		lfFactor		= laDatos.txtFactor;
		lsCodigo_Barras	= laDatos.txtCodigo_Barras;
		lfPrecio_Compra	= laDatos.txtPrecio_Compra;
		lfPrecio_Venta	= laDatos.txtPrecio_Venta;
	}
	
	//Creación de objetos
	let loCombo			= foCrear_Objeto('select', '', 'cmbUnidad_Alternativa_'+liFila, '', 'form-control combos');
	let loFactor		= foCrear_Objeto('input', 'number', 'txtFactor_Alternativa_'+liFila, lfFactor, 'form-control text-right', 'Factor');
	let loCodigo_Barras	= foCrear_Objeto('input', 'text', 'txtCodigo_Barras_Alternativa_'+liFila, lsCodigo_Barras, 'form-control', 'Código de Barras');
	let loPrecio_Compra	= foCrear_Objeto('input', 'text', 'txtPrecio_Compra_Alternativa_'+liFila, lfPrecio_Compra, 'form-control text-right', 'P. Compra');
	let loPrecio_Venta	= foCrear_Objeto('input', 'text', 'txtPrecio_Venta_Alternativa_'+liFila, lfPrecio_Venta, 'form-control text-right', 'P. Venta');
	let loQuitar		= foCrear_Objeto('button', 'button', 'btnQuitar'+liFila, '', 'btn btn-danger', '');
	let loLi			= document.createElement("li");
	loLi.className		= "fa fa-minus";
	
	//Asignación de valores o atributos adicionales
	let loOpcion				= document.createElement('option');
	loOpcion.value				= "";
	loOpcion.innerHTML			= "Seleccione un valor...";
	loCombo.append(loOpcion);
	loCombo.setAttribute("onchange","fpObtener_Factor("+liFila+");");
	
	loFactor.step = '0.50';
	loQuitar.setAttribute("onclick","fpQuitar_Presentacion_Alternativa("+liFila+");");
	loQuitar.append(loLi);
	
	loCelda1.append(loCombo);
	loCelda2.append(loFactor);
	loCelda3.append(loCodigo_Barras);
	loCelda4.append(loPrecio_Compra);
	loCelda5.append(loPrecio_Venta);
	loCelda6.append(loQuitar);
	
	loF.txtFila.value = liFila;
	
	$('#cmbUnidad_Alternativa_'+liFila).select2({ language: "es" });
	fpRecargar_Combo('cmbUnidad_Alternativa_'+liFila, 'unidad_secundaria', liUnidad)
	document.querySelector('#cmbUnidad_Alternativa_'+liFila).focus();
}

function fpQuitar_Presentacion_Alternativa(piLinea)
{
	let liFila	= Number(loF.txtFila.value);
	let liLinea	= liFila + 1;
	let liI		= 0;
	let liS		= 0;
	
	for(liI = piLinea ; liI < liFila ; liI++)
	{
		liS = liI + 1;
		document.querySelector("#cmbUnidad_Alternativa_"+liI).value			=document.querySelector("#cmbUnidad_Alternativa_"+liS).value;
		document.querySelector("#txtFactor_Alternativa_"+liI).value			=document.querySelector("#txtFactor_Alternativa_"+liS).value;
		document.querySelector("#txtCodigo_Barras_Alternativa_"+liI).value	=document.querySelector("#txtCodigo_Barras_Alternativa_"+liS).value;
		document.querySelector("#txtPrecio_Compra_Alternativa_"+liI).value	=document.querySelector("#txtPrecio_Compra_Alternativa_"+liS).value;
		document.querySelector("#txtPrecio_Venta_Alternativa_"+liI).value	=document.querySelector("#txtPrecio_Venta_Alternativa_"+liS).value;
		
		//Esto es para que actualice la información con el estilo del select2
		$('#cmbUnidad_Alternativa_'+liI).trigger('change.select2');
	}
	
	loF.txtFila.value = liFila - 1;
	if(loF.txtFila.value <= 0){
		document.querySelector("#tabUnidad_Presentacion").classList.add("d-none");
	}
	
	document.querySelector("#tabUnidad_Presentacion").deleteRow(liLinea - 1)
}

function fpObtener_Factor(piLinea)
{
	let loUnidad = document.querySelector("#cmbUnidad_Alternativa_"+piLinea);
	let lfFactor = loUnidad.options[loUnidad.selectedIndex].dataset.factor != undefined ? loUnidad.options[loUnidad.selectedIndex].dataset.factor : '';
	document.querySelector("#txtFactor_Alternativa_"+piLinea).value = lfFactor;
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
	código de la producto y que al momento de buscar lo haga por coincidencia del nombre y no por código
****************************************************************************************************/
function fpVerificar_Producto()
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
