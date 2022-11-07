var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corPendiente.php";
window.onload = () => {
	fpListar();
	fpRecargar_Combo('cmbUnidad', 'unidad', 1);
	
	const laDias = ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"];
	const laMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"];
	
	$('#txtFecha_Estimada, #txtFecha_Ejecucion').daterangepicker({
		"singleDatePicker": true,
        "locale": {
            "format": "DD-MM-YYYY",
            "separator": " - ",
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": laDias,
            "monthNames": laMeses,
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
            "daysOfWeek": laDias,
            "monthNames": laMeses,
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
	
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Pendiente';
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
	loF.txtCantidad.value			= "";
	loF.cmbUnidad.value				= "1";
	loF.txtObservacion.value		= "";
	loF.txtFecha_Estimada.value		= "";
	loF.cmbResponsable.value		= "";
	loF.cmbCondicion.value			= "P";
	loF.txtFecha_Ejecucion.value	= "";
	loF.txtCosto.value				= "";
	loF.cmbEstado.value				= "A";
	
	loF.txtNombre.disabled			= false;
	loF.txtCantidad.disabled		= false;
	loF.cmbUnidad.disabled			= false;
	loF.txtObservacion.disabled		= false;
	loF.txtFecha_Estimada.disabled	= false;
	loF.cmbResponsable.disabled		= false;
	loF.cmbCondicion.disabled		= false;
	loF.txtFecha_Ejecucion.disabled	= true;
	loF.txtCosto.disabled			= true;
	$('#cmbUnidad, #cmbResponsable, #cmbCondicion').trigger('change.select2');
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
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Pendiente';
				
				//asignacion de valores
				loF.txtOperacion.value			= 'modificar';
				loF.txtNombre.value				= laResultado.laDatos.txtNombre;
				loF.txtCantidad.value			= laResultado.laDatos.txtCantidad;
				loF.cmbUnidad.value				= laResultado.laDatos.cmbUnidad;
				loF.txtObservacion.value		= laResultado.laDatos.txtObservacion;
				loF.txtFecha_Estimada.value		= laResultado.laDatos.txtFecha_Estimada;
				loF.cmbResponsable.value		= laResultado.laDatos.cmbResponsable;
				loF.cmbCondicion.value			= laResultado.laDatos.cmbCondicion;
				loF.txtFecha_Ejecucion.value	= laResultado.laDatos.txtFecha_Ejecucion;
				loF.txtCosto.value				= laResultado.laDatos.txtCosto;
				loF.cmbEstado.value				= laResultado.laDatos.cmbEstatus;
				
				$('#cmbUnidad, #cmbResponsable, #cmbCondicion').trigger('change.select2');
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
	Funcion que busca un registro a partir de su codigo y si lo encuentra muestra el formulario para 
	su finalización, activa los campos de fecha de ejecución y el costo
****************************************************************************************************/
function fpFinalizar(piCodigo)
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
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Pendiente';
				
				//asignacion de valores
				loF.txtOperacion.value			= 'finalizar';
				loF.txtNombre.value				= laResultado.laDatos.txtNombre;
				loF.txtCantidad.value			= laResultado.laDatos.txtCantidad;
				loF.cmbUnidad.value				= laResultado.laDatos.cmbUnidad;
				loF.txtObservacion.value		= laResultado.laDatos.txtObservacion;
				loF.txtFecha_Estimada.value		= laResultado.laDatos.txtFecha_Estimada;
				loF.cmbResponsable.value		= laResultado.laDatos.cmbResponsable;
				loF.cmbCondicion.value			= laResultado.laDatos.cmbCondicion;
				loF.txtFecha_Ejecucion.value	= laResultado.laDatos.txtFecha_Ejecucion;
				loF.txtCosto.value				= laResultado.laDatos.txtCosto;
				loF.cmbEstado.value				= laResultado.laDatos.cmbEstatus;
				
				loF.txtNombre.disabled			= true;
				loF.txtCantidad.disabled		= true;
				loF.cmbUnidad.disabled			= true;
				loF.txtObservacion.disabled		= true;
				loF.txtFecha_Estimada.disabled	= true;
				loF.cmbResponsable.disabled		= true;
				loF.cmbCondicion.disabled		= true;
				loF.txtFecha_Ejecucion.disabled	= false;
				loF.txtCosto.disabled			= false;
				
				$('#cmbUnidad, #cmbResponsable, #cmbCondicion').trigger('change.select2');
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
		fpMostrar_Mensaje("Disculpe, el nombre no puede estar en blanco",'a');
	}
	else if(Number(loF.txtCantidad.value) <= 0)
	{
		fpMostrar_Mensaje("Disculpe, la cantidad no puede estar en blanco",'a');
	}
	else if(loF.cmbUnidad.value == "")
	{
		fpMostrar_Mensaje("Disculpe, seleccione el tipo de unidad",'a');
	}
	else if(loF.txtFecha_Estimada.value == "")
	{
		fpMostrar_Mensaje("Disculpe, la fecha estimada no puede estar en blanco",'a');
	}
	else if(loF.cmbResponsable.value == "")
	{
		fpMostrar_Mensaje("Disculpe, seleccione el responsable",'a');
	}
	else if(loF.txtFecha_Ejecucion.value == "" && loF.txtObservacion.value == 'finalizar')
	{
		fpMostrar_Mensaje("Disculpe, seleccione la fecha de ejecución",'a');
	}
	else if(Number(loF.txtCosto.value) <= 0 && loF.txtObservacion.value == 'finalizar')
	{
		fpMostrar_Mensaje("Disculpe, debe ingresar el costo",'a');
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
		lsTitulos			: 'Código,Fecha Estimada,Nombre,Responsable,Condicion,Estado,Opciones',
		lsPorcentajes		: '10%,15%,30%,10%,10%,10%,15%',
		lsAlineacion		: 'right,left,left,left,left,left,left',
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
			title				: 'Eliminar',
			icono				: 'far fa-trash-alt',
			color				: 'btn-danger',
			tipo				: 'boton',
			posicion_valor		: 0,
			posicion_condicion	: 5,
			valor_condicion		: 'Activo'
			
		},
		2: {
			nombre				: 'btnActivar',
			onClick				: 'fpPreguntar_Activar',
			title				: 'Activar',
			icono				: 'fas fa-redo',
			color				: 'btn-info',
			tipo				: 'boton',
			posicion_valor		: 0,
			posicion_condicion	: 5,
			valor_condicion		: 'Inactivo'
		},
		3: {
			nombre					: 'btnFinalizar',
			onClick					: 'fpFinalizar',
			title					: 'Finalizar',
			icono					: 'fas fa-check',
			color					: 'btn-success',
			tipo					: 'boton',
			posicion_valor			: 0,
			posicion_condicion		: 4,
			valor_condicion_multiple: 'Pendiente,Proceso'
		}
	}
	fpImprimir_Listado(laDatos, laFormulario, laBotones, gsUrl);
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
	código de la unidad y que al momento de buscar lo haga por coincidencia del nombre y no por código
****************************************************************************************************/
function fpVerificar_Pendiente()
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
