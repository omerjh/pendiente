var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corProducto_Compuesto.php";
window.onload = () => {
	fpListar();
};

/****************************************************************************************************
	Funcion que permite limpiar el formulario y ocultar el modal
****************************************************************************************************/
function fpCancelar()
{	
	loF.txtOperacion.value					= "";
	loF.txtCodigo.value						= "";
	loF.txtProducto_Codigo_Compuesto.value	= "";
	loF.txtNombre.value						= "";
	loF.txtCantidad_Compuesto.value			= "";
	loF.txtPrecio_Compuesto.value			= "";
	
	for(liI = loF.txtFila.value ; liI > 0 ; liI--)
	{
		fpQuitar_Producto(liI);
	}
	
	fpLimpiar_Busqueda_Producto()
	$("#divFormulario").modal("hide");
}

/****************************************************************************************************
	Funcion que permite limpiar el área de busqueda de productos
****************************************************************************************************/
function fpLimpiar_Busqueda_Producto()
{    
	loF.txtPresentacion_Codigo.value= '';
	loF.txtProducto_Codigo.value	= '';
	loF.txtProducto_Nombre.value	= '';
	loF.cmbUnidad.value				= '';
	loF.txtCantidad.value			= '';
	loF.txtPrecio_Compra.value		= '';
	fpLimpiar_Combo('cmbUnidad');
	loF.txtProducto_Nombre.focus();
}

/****************************************************************************************************
	Funcion que busca un registro a partir de su codigo y si lo encuentra muestra el formulario para 
	su actualización
****************************************************************************************************/
function fpBuscar(piCodigo)
{
	if(piCodigo >= 0)
	{
		loF.txtProducto_Codigo_Compuesto.value 	= piCodigo;
		loF.txtOperacion.value	= 'buscar';
		//Se declara una constante con el objeto obtenido desde el formulario
		const loFormulario = new FormData(document.querySelector('#frmF'));
		
		fpEnviar(loFormulario, laResultado => {
			if(laResultado.lbEstado == true)
			{
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Producto Compuesto';
				
				//asignacion de valores
				loF.txtOperacion.value					= Number(laResultado.laDatos.txtCodigo) > 0 ? 'modificar' : 'incluir';
				loF.txtCodigo.value						= laResultado.laDatos.txtCodigo;
				loF.txtNombre.value						= laResultado.laDatos.txtNombre;
				loF.txtCantidad_Compuesto.value			= laResultado.laDatos.txtCantidad_Compuesto > 0 ? laResultado.laDatos.txtCantidad_Compuesto : 1;
				loF.txtPrecio_Compuesto.value			= laResultado.laDatos.txtPrecio_Compuesto;
				
				let laPresentacion = laResultado.laDatos.laPresentacion;
				if(laResultado.laDatos.txtCantidad_Presentacion >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Presentacion ; liI++){
						fpAgregar_Producto(laPresentacion[liI])
					}
				}
				
				setTimeout(() => { loF.txtCantidad_Compuesto.focus(); }, 300);
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
	else if(loF.txtCantidad_Compuesto.value == "")
	{
		fpMostrar_Mensaje("Disculpe, la cantidad del producto compuesto no puede estar en blanco",'a');
	}
	else if(Number(loF.txtCantidad_Compuesto.value) <= 0)
	{
		fpMostrar_Mensaje("Disculpe, la cantidad del producto compuesto debe ser mayor a cero",'a');
	}
	else if(Number(loF.txtFila.value) <= 1)
	{
		fpMostrar_Mensaje("Disculpe, debe agregar al menos dos producto en insumo",'a');
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
		lsTitulos			: 'Código,Nombre,Cantidad,Costo,Opciones',
		lsPorcentajes		: '10%,40%,20%,20%,10%',
		lsAlineacion		: 'right,left,right,right,left',
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
		}
	}
	fpImprimir_Listado(laDatos, laFormulario, laBotones, gsUrl);
}

/****************************************************************************************************
	Funcion que valida si al agregar producto se hace desde un arreglo, el cual pasa sin validar 
	ningún dato debido a que ya están guardados en la base de datos, si no se pasan los datos por 
	parámetros entonces se validan los campos que buscan los productos
****************************************************************************************************/
function fbValidar_Producto(paDatos)
{
	let lbBueno = false; 
	if( paDatos != '')
	{
		lbBueno = true; 
	}
	else
	{
		if(loF.txtPresentacion_Codigo.value == "" || loF.txtProducto_Codigo.value == "" || loF.txtProducto_Nombre.value == ""|| loF.cmbUnidad.value == "")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un producto de la lista",'a','txtProducto');
			loF.txtProducto.value = '';
		}
		else if(loF.txtCantidad.value == "")
		{
			fpMostrar_Mensaje("Disculpe, la cantidad no puede estar en blanco",'a','txtCantidad');
		}
		else if(Number(loF.txtCantidad.value) <= 0)
		{
			fpMostrar_Mensaje("Disculpe, la cantidad debe ser mayor a cero",'a','txtCantidad');
		}
		else
		{
			let liFila		= Number(loF.txtFila.value);
			let liI			= 1;
			let liEncontrado= 0;
			for(liI = 1 ; liI <= liFila ; liI++)
			{
				if(Number(document.querySelector("#txtProducto_Codigo").value) == Number(document.querySelector("#txtProducto_Codigo_"+liI).value)){
					liEncontrado++;
				}
			}
			
			if(liEncontrado == 0){
				lbBueno = true;
			}else{
				fpMostrar_Mensaje("Disculpe, el producto ya fue agregado",'a','txtProducto_Nombre');
				fpLimpiar_Busqueda_Producto();
			}
		}
	}
	return lbBueno;
}

/****************************************************************************************************
	Funcion que agrega una linea a la tabla de productos que son los insumos para un producto 
	compuesto
****************************************************************************************************/
function fpAgregar_Producto(paDatos)
{
	let laDatos	= paDatos || '';
	if(fbValidar_Producto(laDatos) == true)
	{
		let liFila	= Number(loF.txtFila.value)+1;
		let loTabla	= document.querySelector("#tabProducto");
		let loFila	= loTabla.insertRow(liFila);
		let loCelda1= loFila.insertCell(0);
		let loCelda2= loFila.insertCell(1);
		let loCelda3= loFila.insertCell(2);
		let loCelda4= loFila.insertCell(3);
		let loCelda5= loFila.insertCell(4);
		let loCelda6= loFila.insertCell(5);
		
		loCelda1.id = 'tdCelda_a'+liFila;
		loCelda2.id = 'tdCelda_b'+liFila;
		loCelda3.id = 'tdCelda_c'+liFila;
		loCelda4.id = 'tdCelda_d'+liFila;
		loCelda5.id = 'tdCelda_e'+liFila;
		loCelda6.id = 'tdCelda_f'+liFila;
		
		loCelda3.className = 'text-right';
		loCelda4.className = 'text-right';
		loCelda5.className = 'text-right';
		
		let liPresentacion_Codigo	= '';
		let liProducto_Codigo		= '';
		let lsProducto_Nombre		= '';
		let liUnidad_Codigo			= '';
		let lsUnidad_Nombre			= '';
		let lfCantidad				= '';
		let lfPrecio_Compra			= '';
		let lfTotal					= '';
		
		if(laDatos != '')
		{
			liPresentacion_Codigo	= laDatos.liPresentacion_Codigo;
			liProducto_Codigo		= laDatos.liProducto_Codigo;
			lsProducto_Nombre		= laDatos.lsProducto_Nombre;
			liUnidad_Codigo			= laDatos.liUnidad_Codigo;
			lsUnidad_Nombre			= laDatos.lsUnidad_Nombre;
			lfCantidad				= Number(laDatos.lfCantidad);
			lfPrecio_Compra			= Number(laDatos.lfPrecio_Compra);
			lfTotal					= Number(laDatos.lfTotal);
		}
		else
		{
			liPresentacion_Codigo	= loF.txtPresentacion_Codigo.value;
			liProducto_Codigo		= loF.txtProducto_Codigo.value;
			lsProducto_Nombre		= loF.txtProducto_Nombre.value;
			liUnidad_Codigo			= loF.cmbUnidad.value;
			lsUnidad_Nombre			= loF.cmbUnidad.options[loF.cmbUnidad.selectedIndex].text;
			lfCantidad				= Number(loF.txtCantidad.value);
			lfPrecio_Compra			= Number(loF.txtPrecio_Compra.value);
			lfPrecio_Compra			= lfPrecio_Compra.toFixed(2);
			lfTotal					= lfCantidad * lfPrecio_Compra;
			lfTotal					= lfTotal.toFixed(2);
		}
		
		//Creación de objetos
		let loPresentacion_Codigo	= foCrear_Objeto('input', 'hidden', 'txtPresentacion_Codigo_'+liFila, liPresentacion_Codigo);
		let loProducto_Codigo		= foCrear_Objeto('input', 'hidden', 'txtProducto_Codigo_'+liFila, liProducto_Codigo);
		let loProducto_Nombre		= foCrear_Objeto('input', 'hidden', 'txtProducto_Nombre_'+liFila, lsProducto_Nombre);
		let loPrecio_Venta			= foCrear_Objeto('input', 'hidden', 'txtUnidad_Codigo_'+liFila, liUnidad_Codigo);
		let loCantidad				= foCrear_Objeto('input', 'hidden', 'txtCantidad_'+liFila, lfCantidad);
		let loTotal					= foCrear_Objeto('input', 'hidden', 'txtTotal_'+liFila, lfTotal);
		let loEditar				= foCrear_Objeto('button', 'button', 'btnEditar'+liFila, '', 'btn btn-info');
		let loQuitar				= foCrear_Objeto('button', 'button', 'btnQuitar'+liFila, '', 'btn btn-danger');
		let loLi_Editar				= document.createElement("li");
		let loLi_Quitar				= document.createElement("li");
		loLi_Editar.className		= "fa fa-pencil-alt";
		loLi_Quitar.className		= "fa fa-minus";
				
		// //Asignación de valores o atributos adicionales
		loEditar.setAttribute("onclick","fpEditar_Producto("+liFila+");");
		loQuitar.setAttribute("onclick","fpQuitar_Producto("+liFila+");");
		loEditar.append(loLi_Editar);
		loQuitar.append(loLi_Quitar);
		
		//averiguar para que sirve sino borrar
		let loSpan_Producto			= foCrear_Objeto('span', '', 'Span_Producto_'+liFila);
		loSpan_Producto.innerHTML	= lsProducto_Nombre;
		
		loTexto_Celda2 = document.createTextNode(lsUnidad_Nombre);
		loTexto_Celda3 = document.createTextNode(lfCantidad);
		loTexto_Celda4 = document.createTextNode(lfPrecio_Compra);
		loTexto_Celda5 = document.createTextNode(lfTotal);
		
		loCelda1.append(loSpan_Producto, loPresentacion_Codigo, loProducto_Codigo, loProducto_Nombre, loPrecio_Venta, loCantidad, loTotal);
		loCelda2.append(loTexto_Celda2);
		loCelda3.append(loTexto_Celda3);
		loCelda4.append(loTexto_Celda4);
		loCelda5.append(loTexto_Celda5);
		loCelda6.append(loEditar,loQuitar);
		
		loF.txtFila.value = liFila;
		
		fpLimpiar_Busqueda_Producto();
		fpActualizar_Costo_Total();
	}
}

/****************************************************************************************************
	Funcion que elimina el contenido de una fila de la tabla de insumos de productos
****************************************************************************************************/
function fpQuitar_Producto(piLinea)
{
	let liFila	= Number(loF.txtFila.value);
	let liLinea	= liFila + 1;
	let liI		= 0;
	let liS		= 0;
	
	for(liI = piLinea ; liI < liFila ; liI++)
	{
		liS = liI + 1;
		document.querySelector("#txtPresentacion_Codigo_"+liI).value=document.querySelector("#txtPresentacion_Codigo_"+liS).value;
		document.querySelector("#txtProducto_Codigo_"+liI).value	=document.querySelector("#txtProducto_Codigo_"+liS).value;
		document.querySelector("#txtProducto_Nombre_"+liI).value	=document.querySelector("#txtProducto_Nombre_"+liS).value;
		document.querySelector("#txtUnidad_Codigo_"+liI).value		=document.querySelector("#txtUnidad_Codigo_"+liS).value;
		document.querySelector("#txtCantidad_"+liI).value			=document.querySelector("#txtCantidad_"+liS).value;
		document.querySelector("#txtTotal_"+liI).value				=document.querySelector("#txtTotal_"+liS).value;
		
		document.getElementById("Span_Producto_"+liI).innerHTML = document.getElementById("Span_Producto_"+liS).innerHTML;
		document.getElementById("tdCelda_b"+liI).innerHTML = document.getElementById("tdCelda_b"+liS).innerHTML
		document.getElementById("tdCelda_c"+liI).innerHTML = document.getElementById("tdCelda_c"+liS).innerHTML
		document.getElementById("tdCelda_d"+liI).innerHTML = document.getElementById("tdCelda_d"+liS).innerHTML
		document.getElementById("tdCelda_e"+liI).innerHTML = document.getElementById("tdCelda_e"+liS).innerHTML
	}
	
	loF.txtFila.value = liFila - 1;
	fpActualizar_Costo_Total();
	document.querySelector("#tabProducto").deleteRow(liLinea - 1)
}

/****************************************************************************************************
	Funcion que toma los datos de una linea y los coloca en el buscador y luego elimina el contenido 
	de la fila de la tabla de insumos de productos
****************************************************************************************************/
function fpEditar_Producto(piLinea)
{
	loF.txtPresentacion_Codigo.value= document.querySelector("#txtPresentacion_Codigo_"+piLinea).value;
	loF.txtProducto_Codigo.value	= document.querySelector("#txtProducto_Codigo_"+piLinea).value;
	loF.txtProducto_Nombre.value	= document.querySelector("#txtProducto_Nombre_"+piLinea).value;
	loF.txtCantidad.value			= document.querySelector("#txtCantidad_"+piLinea).value;
	loF.txtPrecio_Compra.value		= document.querySelector("#tdCelda_d"+piLinea).innerHTML;
	let laOpcion ={
		1: {
			lsValor: document.querySelector("#txtPresentacion_Codigo_"+piLinea).value,
			lsTexto: document.querySelector("#tdCelda_b"+piLinea).innerHTML
		}
	}
	fpCargar_Combo_Valores('cmbUnidad', laOpcion, 1, document.querySelector("#txtPresentacion_Codigo_"+piLinea).value);
	fpQuitar_Producto(piLinea);
	loF.txtCantidad.select();
}

function fpActualizar_Costo_Total()
{
	let liFila				= Number(loF.txtFila.value);
	let liI					= 1;
	let lfSub_Total			= 0;
	let lfCosto				= 0;
	
	for(liI = 1 ; liI <= liFila ; liI++)
	{
		lfSub_Total = lfSub_Total + Number(document.querySelector("#tdCelda_e"+liI).innerHTML);
	}
	
	if(Number(loF.txtCantidad_Compuesto.value) > 0){
		lfCosto = lfSub_Total / loF.txtCantidad_Compuesto.value;
		loF.txtPrecio_Compuesto.value = lfCosto.toFixed(2);
	}
	// loF.txtSubtotal.value	= lfSub_Total.toFixed(2);
	// loF.txtIgv.value		= lfIgv.toFixed(2);
	// loF.txtTotal.value		= lfTotal.toFixed(2);
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
	Funcion que cambia el código de la presentación al momento de seleccionar una unidad desde el 
	combo
****************************************************************************************************/
function fpCambiar_Presentacion()
{
	loF.txtPresentacion_Codigo.value = loF.cmbUnidad.value;
}

/****************************************************************************************************
	Funcion que imprime una lista con los productos que coinciden con lo escrito por el usuario
****************************************************************************************************/
$( "#txtProducto_Nombre" ).autocomplete({
	minLength: 1,
	source: function(request, response) {
		$.ajax({
			type: "POST",
			url: gsUrl,
			dataType: "JSON",
			data: {
				txtBuscar:		request.term,
				txtOperacion:	'buscar_producto',
				txtArchivo:		loF.txtArchivo.value
			},
			success: function(data) {
				response(data);
			}
		})
	},
	select: function( event, ui ) {
		loF.txtPresentacion_Codigo.value= ui.item.presentacion_codigo;
		loF.txtProducto_Codigo.value	= ui.item.producto_codigo;
		loF.txtPrecio_Compra.value		= ui.item.precio_compra;
		loF.txtCantidad.value			= 1;
		setTimeout(()=>{
			loF.txtProducto_Nombre.value= ui.item.producto_nombre;
		},300);
		loF.txtCantidad.select();
		fpCargar_Combo_Valores('cmbUnidad', ui.item.presentacion_opcion, ui.item.cantidad, ui.item.presentacion_codigo);
	}
}).data("ui-autocomplete")._renderItem = function (ul, item) {
	 return $("<li></li>")
		 .data("item.autocomplete", item)
		 .append(item.label)
		 .appendTo(ul);
};

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
