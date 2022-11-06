var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corVenta.php";
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
	fpCargar_Cajas();
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	// validar si tiene punto de venta asignado, si no lo tiene no lo deja registrar, solo visualizar
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Venta';
	loF.txtOperacion.value = 'incluir';
	$("#divFormulario").modal("show");
	fpCargar_Documento();
	fpBuscar_Cliente(1);
	setTimeout(() => { loF.txtProducto_Nombre.focus(); }, 300);
}

/****************************************************************************************************
	Funcion que permite limpiar el formulario y ocultar el modal
****************************************************************************************************/
function fpCancelar()
{
	// loF.txtOperacion.value	= "";
	// loF.txtCodigo.value		= "";
	// loF.txtNombre.value		= "";
	// loF.cmbPunto_Venta.value	= loF.txtPunto_Venta.value;
	// loF.cmbMoneda.value	= 1;
	// loF.txtTipo_Cambio.value	= 1;
	// loF.cmbEstado.value		= "A";
	// loF.txtTipo_Cambio.disabled = true;

	// $('#cmbAlmacen').trigger('change.select2');
	// for(liI = loF.txtFila.value ; liI > 0 ; liI--)
	// {
		// fpQuitar_Talonario(liI);
	// }
	// fpLimpiar_Busqueda_Producto();
	// $("#divFormulario").modal("hide");
	fpBuscar_Cliente(1);
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
	loF.txtPrecio_Venta.value		= '';
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
		loF.txtCodigo.value 	= piCodigo;
		loF.txtOperacion.value	= 'buscar';
		//Se declara una constante con el objeto obtenido desde el formulario
		const loFormulario = new FormData(document.querySelector('#frmF'));

		fpEnviar(loFormulario, laResultado => {
			if(laResultado.lbEstado == true)
			{
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Visualizar Venta';

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
		loF.txtTipo_Cambio.disabled = false;
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
		// else if(Number(loF.txtCantidad.value) > Number(loF.txtStock.value))
		// {
			// fpMostrar_Mensaje("Disculpe, la cantidad debe ser menor al stock actual que es: "+loF.txtStock.value,'a','txtCantidad');
		// }
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
		let lfPrecio_Venta			= '';
		let lfTotal					= '';
		
		if(laDatos != '')
		{
			liPresentacion_Codigo	= laDatos.liPresentacion_Codigo;
			liProducto_Codigo		= laDatos.liProducto_Codigo;
			lsProducto_Nombre		= laDatos.lsProducto_Nombre;
			liUnidad_Codigo			= laDatos.liUnidad_Codigo;
			lsUnidad_Nombre			= laDatos.lsUnidad_Nombre;
			lfCantidad				= Number(laDatos.lfCantidad);
			lfPrecio_Venta			= Number(laDatos.lfPrecio_Venta);
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
			lfPrecio_Venta			= Number(loF.txtPrecio_Venta.value);
			lfPrecio_Venta			= lfPrecio_Venta.toFixed(2);
			lfTotal					= lfCantidad * lfPrecio_Venta;
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
		loTexto_Celda4 = document.createTextNode(lfPrecio_Venta);
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
		
		document.querySelector("#Span_Producto_"+liI).innerHTML	= document.querySelector("#Span_Producto_"+liS).innerHTML;
		document.querySelector("#tdCelda_b"+liI).innerHTML		= document.querySelector("#tdCelda_b"+liS).innerHTML
		document.querySelector("#tdCelda_c"+liI).innerHTML		= document.querySelector("#tdCelda_c"+liS).innerHTML
		document.querySelector("#tdCelda_d"+liI).innerHTML		= document.querySelector("#tdCelda_d"+liS).innerHTML
		document.querySelector("#tdCelda_e"+liI).innerHTML		= document.querySelector("#tdCelda_e"+liS).innerHTML
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
	loF.txtOperacion.value			= 'autocompletado_presentacion';
	//Se declara una constante con el objeto obtenido desde el formulario
	const loFormulario = new FormData(document.querySelector('#frmF'));

	fpEnviar(loFormulario, laResultado => {
		if(laResultado.lbEstado == true)
		{
			loF.txtProducto_Codigo.value	= document.querySelector("#txtProducto_Codigo_"+piLinea).value;
			loF.txtProducto_Nombre.value	= document.querySelector("#txtProducto_Nombre_"+piLinea).value;
			loF.txtCantidad.value			= document.querySelector("#txtCantidad_"+piLinea).value;
			loF.txtPrecio_Venta.value		= document.querySelector("#tdCelda_d"+piLinea).innerHTML;
			fpCargar_Combo_Valores('cmbUnidad', laResultado[0].presentacion_opcion, laResultado[0].cantidad, loF.txtPresentacion_Codigo.value);
			fpQuitar_Producto(piLinea);
			loF.txtCantidad.select();
		}
		else
		{
			fpMostrar_Mensaje(laResultado.lsMensaje, '-');
			fpCancelar();
		}
	},'../../controladores/corProducto.php');
}

/****************************************************************************************************
	Funcion que actualiza el monto total de la venta y al final llama la funcion que actualiza los 
	montos a pagar
****************************************************************************************************/
function fpActualizar_Costo_Total()
{
	let liFila				= Number(loF.txtFila.value);
	let liI					= 1;
	let lfSub_Total			= 0;
	let lfIva				= 0;
	let lfTotal				= 0;
	
	for(liI = 1 ; liI <= liFila ; liI++)
	{
		lfSub_Total = lfSub_Total + Number(document.querySelector("#tdCelda_e"+liI).innerHTML);
	}
	
	lfIva		= lfSub_Total.toFixed(2) * 0.18;
	lfTotal		= lfSub_Total;
	lfSub_Total	= lfSub_Total - lfIva;
	
	loF.txtSubtotal.value	= lfSub_Total.toFixed(2);
	loF.txtIva.value		= lfIva.toFixed(2);
	loF.txtTotal.value		= lfTotal.toFixed(2);
	
	document.querySelector('#txtDiferencia').value = lfTotal.toFixed(2);
	
	fpActualizar_Forma_Pago();
}

/****************************************************************************************************
	Funcion que cambia el código de la presentación al momento de seleccionar una unidad desde el 
	combo
****************************************************************************************************/
function fpCambiar_Presentacion()
{
	loF.txtPresentacion_Codigo.value= loF.cmbUnidad.value;
	if(loF.cmbUnidad.options[loF.cmbUnidad.selectedIndex].dataset.precio_venta != undefined)
	{
		loF.txtPrecio_Venta.value	= loF.cmbUnidad.options[loF.cmbUnidad.selectedIndex].dataset.precio_venta;
		loF.txtCantidad.select();
	}
}

/****************************************************************************************************
	Funcion que 
****************************************************************************************************/
function fpCargar_Documento(psSeleccion = '', psSerie = '', psCorrelativo = '')
{
	fpCombo_Dependiente('cmbPunto_Venta,txtSistema', 'cmbDocumento', 'documento_punto_venta', psSeleccion, 'N', ()=>{
		let loCombo		= document.querySelector("#cmbDocumento"); 
		let lbEncontrado= false;
		let lsValor		= '';
		let liI			= 0;
		while(liI < loCombo.length && lbEncontrado == false && psSeleccion == '')
		{
			loCombo[liI].removeAttribute('selected');
			if(psSeleccion != '' && psSeleccion == loCombo[liI].value)
			{
				loCombo[liI].setAttribute('selected', 'selected');
			}
			else if(loCombo[liI].dataset.defecto == 'S' && psSeleccion == '')
			{
				loCombo[liI].setAttribute('selected', 'selected');
			}
			liI++;
		}
		$('#cmbDocumento').trigger('change.select2');
		
		fpCargar_Talonario(psSerie, psCorrelativo);
	});
}

/****************************************************************************************************
	Funcion que 
****************************************************************************************************/
function fpCargar_Talonario(psSeleccion = '', psCorrelativo = '')
{
	fpCombo_Dependiente('cmbPunto_Venta,cmbDocumento', 'cmbSerie', 'talonario_documento', psSeleccion, 'N', ()=>{
		if(psCorrelativo != '')
		{
			loF.txtCorrelativo.value = psCorrelativo;
		}
		else
		{
			fpCargar_Correlativo()
		}
	});
}

/****************************************************************************************************
	Funcion que permite buscar el correlativo de un talonario seleccionado
****************************************************************************************************/
function fpCargar_Correlativo()
{
	let liCodigo = loF.txtCodigo.value;
	loF.txtCodigo.value	= loF.cmbSerie.value;
	loF.txtOperacion.value	= 'buscar_correlativo';
	const loFormulario = new FormData(document.querySelector('#frmF'));
	fpEnviar(loFormulario, laResultado => {
		loF.txtCodigo.value	= liCodigo;
		loF.txtOperacion.value	= loF.txtCodigo.value != '' ? 'modificar' : 'incluir';
		if(laResultado.lbEstado == true)
		{
			loF.txtCorrelativo.value= laResultado.laDatos.txtCorrelativo;
		}
		else
		{
			fpMostrar_Mensaje(laResultado.lsMensaje, '-');
		}
	}, '../../controladores/corTalonario.php');
}

/****************************************************************************************************
	Funcion que permite buscar las cajas asignadas al punto de venta seleccionado
****************************************************************************************************/
function fpCargar_Cajas()
{
	let liCodigo = loF.txtCodigo.value;
	loF.txtCodigo.value	= loF.cmbPunto_Venta.value;
	loF.txtOperacion.value	= 'buscar_cajas_asignadas';
	const loFormulario = new FormData(document.querySelector('#frmF'));
	fpEnviar(loFormulario, laResultado => {
		loF.txtCodigo.value	= liCodigo;
		loF.txtOperacion.value	= loF.txtCodigo.value != '' ? 'modificar' : 'incluir';
		if(laResultado.lbEstado == true)
		{
			// console.log(laResultado.laDatos);
			
			let laCaja = laResultado.laDatos.laCaja;
			if(laResultado.laDatos.txtCantidad_Caja >= 1){
				let liI = 1;
				for( liI = 1 ; liI <= laResultado.laDatos.txtCantidad_Caja ; liI++){
					fpAgregar_Cajas(laCaja[liI])
				}
				fpAgregar_Diferencia();
			}
		}
		else
		{
			fpMostrar_Mensaje(laResultado.lsMensaje, '-');
		}
	}, '../../controladores/corPunto_Venta.php');
}

/****************************************************************************************************
	Funcion que agrega una linea a la tabla de productos que son los insumos para un producto 
	compuesto
****************************************************************************************************/
function fpAgregar_Cajas(paDatos)
{
	// let laDatos	= paDatos || '';
	let liFila	= Number(loF.txtFila_Caja.value)+1;
	let loTabla	= document.querySelector("#tabCaja");
	let loFila	= loTabla.insertRow(liFila);
	let loCelda1= loFila.insertCell(0);
	let loCelda2= loFila.insertCell(1);
	let loCelda3= loFila.insertCell(2);
	let loCelda4= loFila.insertCell(3);
	let loCelda5= loFila.insertCell(4);
		
	loCelda1.id = 'tdCelda_Caja_a'+liFila;
	loCelda2.id = 'tdCelda_Caja_b'+liFila;
	loCelda3.id = 'tdCelda_Caja_c'+liFila;
	loCelda4.id = 'tdCelda_Caja_d'+liFila;
	loCelda5.id = 'tdCelda_Caja_e'+liFila;
	
	loCelda1.className = 'font-weight-bold';
	
	// console.log(paDatos);
	loTexto_Celda1	= document.createTextNode(paDatos.lsNombre);
	let loCaja		= foCrear_Objeto('input', 'hidden', 'txtCaja_'+liFila, paDatos.liCodigo);
	let loMonto		= foCrear_Objeto('input', 'text', 'txtMonto_'+liFila, '', 'form-control form-control-sm text-right monto-caja', paDatos.lsNombre);
	loMonto.setAttribute("data-posicion", liFila);
	loMonto.setAttribute("data-moneda", paDatos.liMoneda);
	loMonto.setAttribute("data-tipo_cambio", paDatos.lfTipo_Cambio);
	loMonto.setAttribute("data-salto", paDatos.lsSalto);
	loMonto.setAttribute("data-numero_operacion", paDatos.lsNumero_Operacion);
	loMonto.setAttribute("onBlur", "fpCalcular_Diferencia(this)");
	loMonto.setAttribute("onKeyPress", "return fbSolo_Montos(event,this)");
	
	let loNumero_Operacion	= foCrear_Objeto('input', 'text', 'txtNumero_Operacion_'+liFila, '', 'form-control form-control-sm', 'NÚMERO DE OPERACIÓN');
	loNumero_Operacion.setAttribute("data-salto", paDatos.lsSalto);
	loNumero_Operacion.setAttribute("onKeyPress", "return fbSolo_Texto_Numeros(event,'txtMonto_"+paDatos.lsSalto+"')");
	
	let loEquivalente		= foCrear_Objeto('input', 'text', 'txtEquivalente_'+liFila, '', 'form-control form-control-sm text-right', 'EQUIVALENTE');
	loEquivalente.setAttribute("readonly", "readonly");
	
	let loTipo_Cambio		= foCrear_Objeto('input', 'text', 'txtTipo_Cambio_'+liFila, paDatos.lfTipo_Cambio, 'form-control form-control-sm text-right', 'TIPO DE CAMBIO');
	loTipo_Cambio.setAttribute("readonly", "readonly");
	
	// lsNumero_Operacion: false
	// lfTipo_Cambio: 1
	// liCodigo: "1"
	// liMoneda: "1"
	// lsNombre: "EFECTIVO BS"
	// lsSalto: 2
	
	loCelda1.append(loTexto_Celda1);
	loCelda2.append(loCaja, loMonto);
	if(paDatos.lsNumero_Operacion == 'S'){
		loCelda3.append(loNumero_Operacion);
	}
	loCelda4.append(loEquivalente);
	loCelda5.append(loTipo_Cambio);
	
	loF.txtFila_Caja.value = liFila;
}

function fpAgregar_Diferencia()
{
	let liFila	= Number(loF.txtFila_Caja.value)+1;
	let loTabla	= document.querySelector("#tabCaja");
	let loFila	= loTabla.insertRow(liFila);
	let loCelda1= loFila.insertCell(0);
	let loCelda2= loFila.insertCell(1);
	let loCelda3= loFila.insertCell(2);
	let loCelda4= loFila.insertCell(3);
	let loCelda5= loFila.insertCell(4);
		
	loCelda1.id = 'tdCelda_Diferencia_a'+liFila;
	loCelda2.id = 'tdCelda_Diferencia_b'+liFila;
	loCelda3.id = 'tdCelda_Diferencia_c'+liFila;
	loCelda4.id = 'tdCelda_Diferencia_d'+liFila;
	loCelda5.id = 'tdCelda_Diferencia_e'+liFila;
	
	loCelda3.className = 'font-weight-bold text-right';
	
	loTexto_Celda3	= document.createTextNode('DIFERENCIA');
	let loDiferencia= foCrear_Objeto('input', 'text', 'txtDiferencia', '', 'form-control form-control-sm text-right', 'DIFERENCIA');
	loDiferencia.setAttribute("readonly", "readonly");
	
	loCelda3.append(loTexto_Celda3);
	loCelda4.append(loDiferencia);
	loF.txtFila_Caja.value = liFila;
}

function fpCalcular_Diferencia(poCampo='')
{
	let lfDiferencia= loF.txtTotal.value;
	let liPosicion	= 0;
	let laMonto		= document.querySelectorAll('.monto-caja');
	let lfTotal		= 0;
	laMonto.forEach((poCaja) => {
		liPosicion		= poCaja.dataset.posicion;
		loCampo			= document.querySelector('#txtMonto_'+liPosicion);
		lfTipo_Cambio	= Number(loCampo.dataset.tipo_cambio) > 0 ? loCampo.dataset.tipo_cambio : 1;
		lfMonto			= Number(loCampo.value * lfTipo_Cambio);
		lfTotal			+= lfMonto;

		document.querySelector('#txtEquivalente_'+liPosicion).value = lfMonto > 0 ? lfMonto.toFixed(2) : '';
		loCampo.value = loCampo.value > 0 ? loCampo.value : '';
	});
	lfTotal = loF.txtTotal.value - lfTotal;
	document.querySelector('#txtDiferencia').value = lfTotal.toFixed(2);
	
	if(poCampo != '')
	{
		liPosicion = poCampo.dataset.posicion;
		if(poCampo.dataset.numero_operacion == "S" && poCampo.value > 0)
		{
			document.querySelector('#txtNumero_Operacion_'+liPosicion).select();
		}
		else
		{
			if(poCampo.dataset.numero_operacion == "S" && poCampo.value == 0)
			{
				document.querySelector('#txtNumero_Operacion_'+liPosicion).value = '';
			}
			if()
			{
				
			}
			document.querySelector('#txtMonto_'+poCampo.dataset.salto).select();
		}
	}
}

/****************************************************************************************************
	Funcion que limpia todas las cajas de la forma de pago, dejando el total a pagar en la primera 
	caja de y actualiza el monto de la diferencia
****************************************************************************************************/
function fpActualizar_Forma_Pago()
{
	let laMonto = document.querySelectorAll('.monto-caja');
	laMonto.forEach((poCaja) => {
		poCaja.value='';
	});
	
	if(loF.txtTotal.value > 0)
	{
		let loCampo			= document.querySelector('#txtMonto_1');
		let lfMonto			= Number(loF.txtTotal.value / loCampo.dataset.tipo_cambio);
		let lfEquivalente	= Number(loF.txtTotal.value);
		loCampo.value		= lfMonto > 0 ? lfMonto.toFixed(2) : '';
		document.querySelector('#txtEquivalente_1').value = lfEquivalente > 0 ? lfEquivalente.toFixed(2) : '';
		fpCalcular_Diferencia();
	}
}

/********************************************************************************************************
	Función que valida si la tecla presionada corresponde a los números
********************************************************************************************************/
function fbSolo_Montos(psTecla,poCampo) 
{
	if(loF.txtTotal.value > 0)
	{
		liKey = psTecla.keyCode || psTecla.which;
		lsTecla = String.fromCharCode(liKey).toUpperCase();
		laLetras = ".1234567890";
		laEspeciales = [8,9,13,35,36,37,38,39,40,46,116,118];
		
		if(liKey == 46)
		{
			let liA = poCampo.value.length;
			let liI = 0;
			let liContador = 0;
			let lsCaracteres = '.';
			for(liI = 0 ; liI <= liA ; liI++){
				if(lsCaracteres.indexOf(poCampo.value[liI]) != -1){
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
		
		if(liKey == 13)
		{
			fpCalcular_Diferencia(poCampo);
			return false;
		}
		
		if(laLetras.indexOf(lsTecla)==-1 && !lbTecla_Especial)
		{
			return false;
		}
	}
	else
	{
		fpMostrar_Mensaje('Debe Agregar al menos un producto para ingresar algun monto', 'a');
		return false;
	}
}

/****************************************************************************************************
	Funcion que permite buscar un cliente 
****************************************************************************************************/
function fpBuscar_Cliente(piCodigo = 1)
{
	let liCodigo = loF.txtCodigo.value;
	loF.txtCodigo.value	= piCodigo;
	loF.txtOperacion.value	= 'buscar';
	const loFormulario = new FormData(document.querySelector('#frmF'));
	fpEnviar(loFormulario, laResultado => {
		loF.txtCodigo.value	= liCodigo;
		loF.txtOperacion.value	= loF.txtCodigo.value != '' ? 'modificar' : 'incluir';
		if(laResultado.lbEstado == true)
		{
			loF.txtCliente_Codigo.value = laResultado.laDatos.txtCodigo;
			loF.txtCliente_Numero.value = laResultado.laDatos.txtNumero;
			loF.txtCliente_Nombre.value = laResultado.laDatos.txtNombre;
		}
		else
		{
			fpMostrar_Mensaje(laResultado.lsMensaje, '-');
		}
	}, '../../controladores/corCliente.php');
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
function fpVerificar_Venta()
{
	if(loFiltro.txtFiltro_Nombre.value == ''){
		loFiltro.txtFiltro_Codigo.value='';
	}
}

/****************************************************************************************************
	Funcion que imprime un texto en una etiqueta small
****************************************************************************************************/
function fpCambiar_Icono_Subtitulo(psIcono){
	if(document.querySelector('#'+psIcono).classList.contains('fa-angle-left') == true)
	{
		document.querySelector('#'+psIcono).classList.replace('fa-angle-left','fa-angle-down');
	}
	else
	{
		document.querySelector('#'+psIcono).classList.replace('fa-angle-down','fa-angle-left');
	}
}

/****************************************************************************************************
	Funcion que imprime una lista con los registros que coinciden con lo escrito por el usuario
****************************************************************************************************/
$( "#txtProducto_Nombre" ).autocomplete({
	minLength: 1,
	source: function(request, response) {
		$.ajax({
			type: "POST",
			url: '../../controladores/corProducto.php',
			dataType: "JSON",
			data: {
				txtBuscar:		request.term,
				txtOperacion:	'autocompletado_presentacion',
				// cmbMoneda:		loF.cmbMoneda.value,
				txtAlmacen:		loF.txtAlmacen.value,
				txtArchivo:		loF.txtArchivo.value
			},
			success: function(data) {
				response(data);
			}
		})
	},
	select: function( event, ui ) {
		loF.txtPresentacion_Codigo.value= ui.item.presentacion_codigo;
		loF.txtProducto_Codigo.value= ui.item.producto_codigo;
		loF.txtPrecio_Venta.value	= ui.item.precio_venta;
		loF.txtStock.value			= ui.item.stock;
		loF.txtCantidad.value		= 1;
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
	Funcion que imprime una lista con los clientes registrados que coinciden con lo escrito por el 
	usuario
****************************************************************************************************/
$( "#txtCliente_Numero, #txtCliente_Nombre" ).autocomplete({
	minLength: 1,
	source: function(request, response) {
		$.ajax({
			type: "POST",
			url: '../../controladores/corCliente.php',
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
		loF.txtCliente_Codigo.value	= ui.item.codigo;
		setTimeout(()=>{
			loF.txtCliente_Numero.value	= ui.item.numero;
			loF.txtCliente_Nombre.value	= ui.item.nombre;
		},300)
	}
});

