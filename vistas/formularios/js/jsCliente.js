var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corCliente.php";
window.onload = () => {
	fpListar();
};

/****************************************************************************************************
	Funcion que muestra el modal con el formulario para ingresar o actualizar un registro
****************************************************************************************************/
function fpNuevo()
{
	document.querySelector('#h4Encabezado_Formulario').innerHTML='Registrar Cliente';
	loF.txtOperacion.value = 'incluir';
	$("#divFormulario").modal("show");
	setTimeout(() => { loF.radDocumento1.focus(); }, 300);
}

/****************************************************************************************************
	Funcion que permite limpiar el formulario y ocultar el modal
****************************************************************************************************/
function fpCancelar()
{	
	loF.txtOperacion.value	= "";
	loF.txtCodigo.value		= "";
	loF.radDocumento1.checked	= true;
	loF.radDocumento2.checked	= false;
	loF.radDocumento3.checked	= false;
	loF.txtNumero.value		= "";
	loF.txtNombre.value		= "";
	loF.txtDireccion.value	= "";
	loF.txtTelefono.value	= "";
	loF.txtEmail.value		= "";
	loF.radTipo1.checked	= true;
	loF.radTipo2.checked	= false;
	loF.cmbEstado.value		= "A";
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
				document.querySelector('#h4Encabezado_Formulario').innerHTML='Actualizar Cliente';
				
				//asignacion de valores
				loF.txtOperacion.value	= 'modificar';
				loF.txtNumero.value		= laResultado.laDatos.txtNumero;
				loF.txtNombre.value		= laResultado.laDatos.txtNombre;
				loF.txtDireccion.value	= laResultado.laDatos.txtDireccion;
				loF.txtTelefono.value	= laResultado.laDatos.txtTelefono;
				loF.txtEmail.value		= laResultado.laDatos.txtEmail;
				loF.cmbEstado.value		= laResultado.laDatos.cmbEstatus;
				if(laResultado.laDatos.radDocumento == 'C')
				{
					loF.radDocumento1.checked	= true;
				}
				else if(laResultado.laDatos.radDocumento == 'P')
				{
					loF.radDocumento2.checked	= true;
				}
				else
				{
					loF.radDocumento3.checked	= true;
				}
				if(laResultado.laDatos.radTipo == 'N')
				{
					loF.radTipo1.checked	= true;
				}
				else
				{
					loF.radTipo2.checked	= true;
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
	if(loF.txtNumero.value == "")
	{
		fpMostrar_Mensaje("Disculpe, el número no puede estar en blanco",'a');
	}
	else if(loF.txtDireccion.value == "")
	{
		fpMostrar_Mensaje("Disculpe, la dirección no puede estar en blanco",'a');
	}
	else if(loF.txtNombre.value == "")
	{
		fpMostrar_Mensaje("Disculpe, el nombre del cliente no puede estar en blanco",'a');
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
		lsTitulos			: "Cód,Doc,Núm,Nombre,Dir,Teléfono,Email,Tipo,Est,Opciones",
		lsTitles			: "Código,Documento,Número,Nombre,Dirección,Télefono,Email,Tipo,Estatus,Opciones",
		lsPorcentajes		: "7%,5%,6%,25%,25%,7%,10%,5%,5%,5%",
		lsAlineacion		: 'right,left,left,left,left,left,left,left,left,left',
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
			posicion_condicion	: 8,
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
			posicion_condicion	: 8,
			valor_condicion		: 'Inactivo'
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
	código del cliente y que al momento de buscar lo haga por coincidencia del nombre y no por código
****************************************************************************************************/
function fpVerificar_Cliente()
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
