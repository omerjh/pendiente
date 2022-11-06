var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corProduccion.php";
window.onload = () => {
	$('#txtFecha').daterangepicker({
		"singleDatePicker": true,
        "locale": {
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Setiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        "startDate":  function() {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        },
        "opens": "center"
    });
	
	$('#txtFiltro_Fecha').daterangepicker({
        "locale": {
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Setiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        "startDate": function() {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        },
        "endDate": function() {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        },
        "opens": "center"
    });
	
	fpListar();
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Orden de Producción';
	loF.txtOperacion.value = 'incluir';
	$("#divFormulario").modal("show");
	setTimeout(() => { loF.cmbAlmacen_Origen.focus(); }, 300);
}

/****************************************************************************************************
	Funcion que permite limpiar el formulario y ocultar el modal
****************************************************************************************************/
function fpCancelar()
{
	loF.txtOperacion.value			= "";
	loF.txtCodigo.value				= "";
	loF.txtObservacion.value		= "";
	loF.cmbAlmacen_Origen.value		= "-";
	loF.cmbAlmacen_Destino.value	= "-";
	loF.txtAlmacen_Origen.value		= "";
	loF.txtAlmacen_Destino.value	= "";
	loF.cmbEstado.value				= "A";

	$('#txtFecha').data('daterangepicker').setStartDate(()=> { return new Date(date.getFullYear(), date.getMonth(), date.getDate()); });
	for(liI = loF.txtFila.value ; liI > 0 ; liI--)
	{
		fpQuitar_Producto(liI, 'N');
	}
	
	fpLimpiar_Busqueda_Producto();
	fpLimpiar_Insumos();
	$('#cmbAlmacen_Origen, #cmbAlmacen_Destino').trigger('change.select2');
	
	document.querySelector('#fieBuscar_Producto').classList.remove('d-none');
	document.querySelector('#divInsumos').classList.remove('show');
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
	loF.txtCantidad.value			= '';
	loF.txtProducto_Nombre.focus();
}

/****************************************************************************************************
	Funcion que permite limpiar el área de busqueda de productos
****************************************************************************************************/
function fpLimpiar_Insumos()
{
	for(liI = loF.txtFila_Insumo.value ; liI > 0 ; liI--)
	{
		document.querySelector("#tabInsumos").deleteRow(liI)
	}
	loF.txtFila_Insumo.value = 0;
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
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Visualizar Orden de Producción';
				
				//asignacion de valores
				loF.txtOperacion.value		= 'modificar';
				loF.txtFecha.value			= laResultado.laDatos.txtFecha;
				loF.txtObservacion.value	= laResultado.laDatos.txtObservacion;
				loF.cmbAlmacen_Origen.value	= laResultado.laDatos.cmbAlmacen_Origen;
				loF.cmbAlmacen_Destino.value= laResultado.laDatos.cmbAlmacen_Destino;
				loF.cmbEstado.value			= laResultado.laDatos.cmbEstado;
				
				$('#cmbAlmacen_Origen, #cmbAlmacen_Destino').trigger('change.select2');
				document.querySelector('#fieBuscar_Producto').classList.add('d-none');
				document.querySelector('#divInsumos').classList.add('show');
				
				let laPresentacion = laResultado.laDatos.laPresentacion;
				if(laResultado.laDatos.txtCantidad_Presentacion >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Presentacion ; liI++){
						fpAgregar_Producto(laPresentacion[liI])
					}
				}
				
				let laInsumo = laResultado.laDatos.laInsumo;
				if(laResultado.laDatos.txtCantidad_Insumo >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Insumo ; liI++){
						fpAgregar_Insumo(laInsumo[liI])
					}
				}
				
				setTimeout(() => { loF.txtObservacion.focus(); }, 300);
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
		loF.txtAlmacen_Origen.value	= loF.cmbAlmacen_Origen.options[loF.cmbAlmacen_Origen.selectedIndex].text;
		loF.txtAlmacen_Destino.value= loF.cmbAlmacen_Destino.options[loF.cmbAlmacen_Destino.selectedIndex].text;
		
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
	else if(loF.cmbAlmacen_Origen.value == "-")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar el almacen de origen",'a');
	}
	else if(loF.cmbAlmacen_Destino.value == "-")
	{
		fpMostrar_Mensaje("Disculpe, debe seleccionar el almacen de destino",'a');
	}
	else if(Number(loF.txtFila.value) <= 0)
	{
		fpMostrar_Mensaje("Disculpe, debe agregar al menos un producto a la lista",'a');
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
		lsTitulos			: 'Código,Fecha,Almace Origen,Almace Destino,Estado,Opciones',
		lsPorcentajes		: '10%,20%,25%,25%,10%,10%',
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
			icono			: 'fas fa-eye',
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
			valor_condicion		: 'Registrada'
			
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
		if(loF.cmbAlmacen_Origen.value == "-")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar el almacen de origen para poder verificar el stock",'a','txtProducto');
		}
		else if(loF.txtPresentacion_Codigo.value == "" || loF.txtProducto_Codigo.value == "" || loF.txtProducto_Nombre.value == "")
		{
			fpMostrar_Mensaje("Disculpe, debe seleccionar un producto de la lista",'a','txtProducto');
			loF.txtProducto_Nombre.value = '';
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
function fpAgregar_Producto(paDatos='')
{
	let laDatos	= paDatos;
	if(fbValidar_Producto(laDatos) == true)
	{
		let liFila	= Number(loF.txtFila.value)+1;
		let loTabla	= document.querySelector("#tabProducto");
		let loFila	= loTabla.insertRow(liFila);
		let loCelda1= loFila.insertCell(0);
		let loCelda2= loFila.insertCell(1);
		let loCelda3= loFila.insertCell(2);
		
		loCelda1.id = 'tdCelda_a'+liFila;
		loCelda2.id = 'tdCelda_b'+liFila;
		loCelda3.id = 'tdCelda_c'+liFila;
		
		loCelda2.className = 'text-right';
		
		let liPresentacion_Codigo	= '';
		let liProducto_Codigo		= '';
		let lsProducto_Nombre		= '';
		let lfCantidad				= '';
		
		if(laDatos != '')
		{
			liPresentacion_Codigo	= laDatos.liPresentacion_Codigo;
			liProducto_Codigo		= laDatos.liProducto_Codigo;
			lsProducto_Nombre		= laDatos.lsProducto_Nombre;
			lfCantidad				= Number(laDatos.lfCantidad);
		}
		else
		{
			liPresentacion_Codigo	= loF.txtPresentacion_Codigo.value;
			liProducto_Codigo		= loF.txtProducto_Codigo.value;
			lsProducto_Nombre		= loF.txtProducto_Nombre.value;
			lfCantidad				= Number(loF.txtCantidad.value);
		}
		
		let lsClase_Adicional = loF.txtOperacion.value == 'modificar' ? ' d-none' : '';
		
		//Creación de objetos
		let loPresentacion_Codigo	= foCrear_Objeto('input', 'hidden', 'txtPresentacion_Codigo_'+liFila, liPresentacion_Codigo);
		let loProducto_Codigo		= foCrear_Objeto('input', 'hidden', 'txtProducto_Codigo_'+liFila, liProducto_Codigo);
		let loProducto_Nombre		= foCrear_Objeto('input', 'hidden', 'txtProducto_Nombre_'+liFila, lsProducto_Nombre);
		let loCantidad				= foCrear_Objeto('input', 'hidden', 'txtCantidad_'+liFila, lfCantidad);
		let loEditar				= foCrear_Objeto('button', 'button', 'btnEditar'+liFila, '', 'btn btn-info'+lsClase_Adicional);
		let loQuitar				= foCrear_Objeto('button', 'button', 'btnQuitar'+liFila, '', 'btn btn-danger'+lsClase_Adicional);
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
		
		loTexto_Celda2 = document.createTextNode(lfCantidad);
		
		loCelda1.append(loSpan_Producto, loPresentacion_Codigo, loProducto_Nombre, loProducto_Codigo, loCantidad);
		loCelda2.append(loTexto_Celda2);
		loCelda3.append(loEditar,loQuitar);
		
		loF.txtFila.value = liFila;
		fpLimpiar_Busqueda_Producto();
		
		if( laDatos == '')
		{
			fpBuscar_Insumos();
		}
	}
}

/****************************************************************************************************
	Funcion que elimina el contenido de una fila de la tabla de insumos de productos
****************************************************************************************************/
function fpQuitar_Producto(piLinea, pbBuscar_Insumo='S')
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
		document.querySelector("#txtCantidad_"+liI).value			=document.querySelector("#txtCantidad_"+liS).value;
		
		document.getElementById("Span_Producto_"+liI).innerHTML = document.getElementById("Span_Producto_"+liS).innerHTML;
		document.getElementById("tdCelda_b"+liI).innerHTML		= document.getElementById("tdCelda_b"+liS).innerHTML
	}
	
	loF.txtFila.value = liFila - 1;
	document.querySelector("#tabProducto").deleteRow(liLinea - 1);
	if(pbBuscar_Insumo == 'S'){
		fpBuscar_Insumos();
	}
}

/****************************************************************************************************
	Funcion que toma los datos de una linea y los coloca en el buscador y luego elimina el contenido 
	de la fila de la tabla de insumos de productos
****************************************************************************************************/
function fpEditar_Producto(piLinea)
{
	loF.txtPresentacion_Codigo.value= document.querySelector("#txtPresentacion_Codigo_"+piLinea).value;
	loF.txtProducto_Codigo.value	= document.querySelector("#txtProducto_Codigo_"+piLinea).value;
	loF.txtProducto_Nombre.value	= document.querySelector("#Span_Producto_"+piLinea).innerHTML;
	loF.txtCantidad.value			= document.querySelector("#txtCantidad_"+piLinea).value;
	fpQuitar_Producto(piLinea);
	loF.txtCantidad.select();
}

/****************************************************************************************************
	Funcion que toma 
****************************************************************************************************/
function fpBuscar_Insumos()
{
	fpLimpiar_Insumos();
	let liFila				= Number(loF.txtFila.value);
	let liI					= 1;
	let lsCodigo_Producto	= '';
	let lsProducto_Cantidad	= '';
	for(liI = 1 ; liI <= liFila ; liI++)
	{
		lsCodigo_Producto	+= lsCodigo_Producto != '' ? ',' : '';
		lsProducto_Cantidad	+= lsProducto_Cantidad != '' ? ',' : '';
		lsCodigo_Producto	+= document.querySelector("#txtProducto_Codigo_"+liI).value;
		lsProducto_Cantidad	+= document.querySelector("#txtCantidad_"+liI).value;
	}
	
	if(liFila > 0  && lsCodigo_Producto != ''){
		let lsOperacion = loF.txtOperacion.value;
		
		loF.txtProducto_Compuesto_Codigo.value	= lsCodigo_Producto;
		loF.txtProducto_Compuesto_Cantidad.value= lsProducto_Cantidad;
		loF.txtOperacion.value					= 'buscar_insumos';

		//Se declara una constante con el objeto obtenido desde el formulario
		const loFormulario = new FormData(document.querySelector('#frmF'));
		
		fpEnviar(loFormulario, laResultado => {
			loF.txtOperacion.value = lsOperacion;
			if(laResultado.lbEstado == true)
			{
				let laInsumo = laResultado.laDatos.laInsumo;
				if(laResultado.laDatos.txtCantidad_Insumo >= 1){
					let liI = 1;
					for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Insumo ; liI++){
						fpAgregar_Insumo(laInsumo[liI])
					}
				}
			}
			else
			{
				//Si el controlador devuelve false, entonces se muestra el mensaje y elimina
				//el ultimo producto agregado
				fpMostrar_Mensaje(laResultado.lsMensaje, '-');
				fpQuitar_Producto(loF.txtFila.value);
			}
		});
	}
}

/****************************************************************************************************
	Funcion que agrega una linea a la tabla de insumos, solo texto informativo
****************************************************************************************************/
function fpAgregar_Insumo(paDatos)
{
	// console.log(paDatos.lsProducto)
	let laDatos	= paDatos || '';
	let liFila	= Number(loF.txtFila_Insumo.value)+1;
	let loTabla	= document.querySelector("#tabInsumos");
	let loFila	= loTabla.insertRow(liFila);
	let loCelda1= loFila.insertCell(0);
	let loCelda2= loFila.insertCell(1);
	let loCelda3= loFila.insertCell(2);
	
	loCelda3.className = 'text-right';
	
	lsProducto	= laDatos.lsProducto;
	lsUnidad	= laDatos.lsUnidad;
	lfCantidad	= laDatos.lfCantidad;
	
	loTexto_Celda1 = document.createTextNode(lsProducto);
	loTexto_Celda2 = document.createTextNode(lsUnidad);
	loTexto_Celda3 = document.createTextNode(lfCantidad);
		
	loCelda1.append(loTexto_Celda1);
	loCelda2.append(loTexto_Celda2);
	loCelda3.append(loTexto_Celda3);
		
	loF.txtFila_Insumo.value = liFila;
}

/****************************************************************************************************
	Funcion que envia el formulario del filtro al archivo que generará el reporte en formato excel
****************************************************************************************************/
function fpGenerar_Excel()
{
	loFiltro.submit();
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
		loF.txtCantidad.value			= ui.item.cantidad;
		setTimeout(()=>{
			loF.txtProducto_Nombre.value= ui.item.producto_nombre;
		},300);
		loF.txtCantidad.select();
	}
}).data("ui-autocomplete")._renderItem = function (ul, item) {
	 return $("<li></li>")
		 .data("item.autocomplete", item)
		 .append(item.label)
		 .appendTo(ul);
};
