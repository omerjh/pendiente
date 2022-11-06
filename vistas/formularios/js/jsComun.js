/***************************** CONFIGURACIONES INICIALES DEL FORMULARIO *****************************/
/****************************************************************************************************
	Funciones que evitan enviar el formulario con submit por error
****************************************************************************************************/
if (document.addEventListener){
	window.addEventListener('load',fpCarga_Principal,false);
} else {
	window.attachEvent('onload',fpCarga_Principal);
}

function fpCarga_Principal()
{
	if(document.querySelector('#frmFiltro') != undefined){
		loF.onsubmit = () => {
			event.preventDefault();
			return false;
		};
	}
	
	if(document.querySelector('#frmFiltro') != undefined){
		loFiltro.onsubmit = () => {
			event.preventDefault();
			return false;
		};
	}
	
	$('.combos').select2({
		language: "es"
	});
};

/****************************************************************************************************
	Funcion que ejecuta la limpieza del formulario al momento de ocultarse
****************************************************************************************************/
if(document.querySelector('#divFormulario') != undefined){
	$('#divFormulario').on('hidden.bs.modal', function (e) {
		fpCancelar();
	});
}

/****************************************************************************************************
	Declaracion de plugin Toast que sirve para generar los mensajes que se muestran en pantalla
****************************************************************************************************/
var Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	// timer: 10000
});

/************************* FIN DE CONFIGURACIONES INICIALES DEL FORMULARIO *************************/

/****************************************************************************************************
	Funcion fpImprimir_Listado() permite hacer la busqueda con jquery e imprimir el resultado en la
	capa donde se hacen los listados
****************************************************************************************************/
function fpImprimir_Listado(paDatos, paFormulario, paBotones, psUrl)
{
	let loDiv_Capa_Listado= document.querySelector('#'+paDatos.lsCapa_Listado);
	let loDivCapa_Paginado= document.querySelector('#'+paDatos.lsCapa_Paginado);
	fpEliminar_Hijos(loDiv_Capa_Listado);
	fpEliminar_Hijos(loDivCapa_Paginado);
	fpInsertar_Imagen(loDiv_Capa_Listado, './imagenes/cargando.gif', 48, 48);
	
	const loFormulario = new FormData();
	Object.keys(paDatos).forEach((key) => {
		loFormulario.append(key, paDatos[key]);
	});
	Object.keys(paFormulario).forEach((key) => {
		loFormulario.append('laFormulario['+key+']', paFormulario[key]);
	});
	Object.keys(paBotones).forEach((key) => {
		Object.keys(paBotones[key]).forEach((key2) => {
			loFormulario.append('laBotones['+key+']['+key2+']', paBotones[key][key2]);
		});
	});

	fpEnviar(loFormulario, laResultado => {
		if(laResultado.lbEstado == true)
		{
			loDiv_Capa_Listado.innerHTML =laResultado.laDatos.lsTabla_Principal;
			loDivCapa_Paginado.innerHTML =laResultado.laDatos.lsTabla_Paginacion;
			$('#'+paDatos.lsTabla_Interna).DataTable({
				"paging": false,
				"lengthChange": false,
				"searching": false,
				"ordering": true,
				"info": false,
				"autoWidth": false,
				"responsive": true
			});
		}
		else
		{
			fpEliminar_Hijos(loDiv_Capa_Listado);
			let loH3			= document.createElement("h3");
			let loP				= document.createElement("p");
			loP.className		= "text-center";
			let loTexto			= document.createTextNode(laResultado.lsMensaje);
			let loBr			= document.createElement("br");
			
			loP.appendChild(loTexto);
			loP.appendChild(loBr);
			fpInsertar_Imagen(loP, 'imagenes/cancelar.png', 48, 48)
			loH3.appendChild(loP);
			loDiv_Capa_Listado.appendChild(loH3);
		}
	});
}
/****************************************************************************************************
	Fin de la funcion fpImprimir_Listado()
****************************************************************************************************/

/****************************************************************************************************
	Funcion faObtener_Datos_Formulario() permite reorganizar un formulario formulario para que pueda
	ser enviado como paramentro y que este sea leido de manera facil por el controlador
****************************************************************************************************/
function faObtener_Datos_Formulario(psFormulario)
{
	let laFormulario_Original = $("#"+psFormulario).serializeArray();
	let laFormulario_Nuevo = {};
	
	$.map(laFormulario_Original, function(n, i){
		laFormulario_Nuevo[n['name']] = n['value'];
	});
	return laFormulario_Nuevo;
}

/****************************************************************************************************
	Funcion fpInsertar_Imagen() permite insertar una imagen
****************************************************************************************************/
function fpInsertar_Imagen(poPadre, psRuta, piAncho, piAlto)
{
	let loImg = document.createElement("img");
	loImg.setAttribute('src',psRuta);
	loImg.setAttribute('height',piAncho);
	loImg.setAttribute('width',piAlto);
	poPadre.appendChild(loImg);
	return poPadre;
	
}
/****************************************************************************************************
	Fin de la funcion fpInsertar_Imagen()
****************************************************************************************************/

/****************************************************************************************************
	Funcion fpEliminar_Hijos() permite eliminar todos los objetos que se encuentren dentro de otro
****************************************************************************************************/
function fpEliminar_Hijos(poPadre)
{
	while (poPadre.firstChild) {
		poPadre.removeChild(poPadre.firstChild);
	}
}
/****************************************************************************************************
	Fin de la funcion fpImprimir_Listado()
****************************************************************************************************/

/****************************************************************************************************
	Funcion fpMostrar_Icono() permite imprimir un icono en un div, al tipearlo en el campo
	correspondiente
****************************************************************************************************/
function fpMostrar_Icono(poCampo, psCapa_Demostracion, psSpan_Demostracion)
{
	if(poCampo.value != "")
	{
		let liI = 0;
		let laClases = poCampo.value.toLowerCase().split(' ');
		document.querySelector('#'+psCapa_Demostracion).classList.remove('d-none');
		for(liI = 0 ; liI < laClases.length ; liI++)
		{
			document.querySelector('#'+psSpan_Demostracion).classList.add(laClases[liI]);
		}
	}
	else
	{
		document.querySelector('#'+psCapa_Demostracion).classList.add('form-group','row','d-none');
	}
}
/****************************************************************************************************
	Fin de la funcion fpMostrar_Icono()
****************************************************************************************************/


/********************************************	INICIO DE AREA DE MENSAJES		********************************************/
/****************************************************************************************************
	Funcion que permite mostrar el cuadro donde se muestra los mensajes
****************************************************************************************************/
function fpMostrar_Mensaje(psMensaje, psImagen)
{
	let lsIcono = "";
	if(psImagen=='+')//MENSAJE E IMAGEN DE OPERACION EXITOSA
	{
		lsIcono = "success";
	}
	else if(psImagen=='-')//MENSAJE E IMAGEN DE OPERACION ERRONEA
	{
		lsIcono = "error";
	}
	else if(psImagen=='a')//MENSAJE E IMAGEN PARA ADVERTENCIAS
	{
		lsIcono = "warning";
	}
	
	setTimeout(() => {
		Swal.fire({
			icon: lsIcono,
			title: psMensaje
		});
	}, 700);
}
/********************************************	FIN DE AREA DE MENSAJES			********************************************/

/****************************************************************************************************
	Funcion que muestra un mensaje pregutando si realmene desea desactilet o actilet un registro.
****************************************************************************************************/
function fpPreguntar_Accion(piCodigo,psAccion)
{
	loF.txtCodigo.value = piCodigo;
	if(psAccion == 'a' || psAccion == 'A')
	{
		fpMostrar_Mensaje("¿Realmente desea actilet el registro? ", 'ac');
	}
	else if(psAccion == 'i' || psAccion == 'I')
	{
		fpMostrar_Mensaje("¿Realmente desea desactilet el registro? ", 'd');
	}
}	

/****************************************************************************************************
	Funcion que permite enviar el formulario al controlador
****************************************************************************************************/
function fpEnviar(poFormulario, pmCallback, psUrl, psTipo_Respuesta)
{
	let lsUrl = psUrl || gsUrl;
	let lsTipo_Respuesta = psTipo_Respuesta || 'JSON';
	//Se ejecuta una peticion asincrona y se le pasa la url del controlador y los datos del formulario
	//y retorna un objeto de tipo promesa con la respuesta obtenida
	fetch(lsUrl, {
		method: 'POST',
		body: poFormulario
	})
	.then(response => {
		//response es la respuesta obtenida de la peticion, response.ok indica si la respuesta corresponde 
		//con un código 200 de XMLHttpRequest lo cual indica que fue correcta
		if(response.ok)
		{
			if(lsTipo_Respuesta == 'JSON')
			{
				//retornamos la respuesta en formato JSON para su manipulacion
				return response.json();
			}
			else
			{
				//retornamos la respuesta en formato texto para su manipulacion
				return response.text();
			}
		}
		else
		{
			throw "Disculpe, ocurrió un error al buscar los datos, por favor intente nuevamente";
		}
	})
	.then(laResultado => {
		//Obtenemos el objeto en formato JSON o TEXTO y ejecutamos una función callback que indicamos por parametro
		pmCallback(laResultado);
	})
	.catch((err) => {
		//En caso de error mostramos un mensaje al usuario y se limpia el formulario, ademas de mostrar el error por consola
		fpMostrar_Mensaje(err, '-');
		// fpCancelar();
		console.log(err);
	});
}

/****************************************************************************************************
	Funcion que permite cambiar de pagina en el filtro y en el listado
****************************************************************************************************/
function fpCambiar_Pagina(piPagina)
{
	loFiltro.txtFiltro_Pagina.value = piPagina;
	fpListar(piPagina);
}

/****************************************************************************************************
	Funcion que permite activar un registro
****************************************************************************************************/
function fpActivar()
{
	loF.txtOperacion.value	= "activar";
	const loFormulario		= new FormData(document.querySelector('#frmF'));
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

/****************************************************************************************************
	Funcion que permite desactivar un registro
****************************************************************************************************/
function fpDesactivar()
{
	loF.txtOperacion.value	= "desactivar";
	const loFormulario		= new FormData(document.querySelector('#frmF'));
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

/****************************************************************************************************
	Funcion que muestra el modal donde se pregunta si se desea desactivar un registro
****************************************************************************************************/
function fpPreguntar_Desactivar(piCodigo)
{
	loF.txtCodigo.value = piCodigo;
	$("#divModal_Desactivar").modal("show");
}

/****************************************************************************************************
	Funcion que muestra el modal donde se pregunta si se desea activar un registro
****************************************************************************************************/
function fpPreguntar_Activar(piCodigo)
{
	loF.txtCodigo.value = piCodigo;
	$("#divModal_Activar").modal("show");
}

/****************************************************************************************************
	Funcion que permite crear un objeto de acuerdo a los parametros que se le envian, por ahora solo
	crea input y le asigna valores basicos, luego se podrá solicitar mas parametros para crear otros
	tipos de objetos de acuerdo a las necesidades
****************************************************************************************************/
function foCrear_Objeto(psObjeto, psTipo, psNombre, psValor, psClase, psPlaceholder)
{
	let loInput	= document.createElement(psObjeto);	
	loInput.name		= psNombre;
	loInput.id			= psNombre;

	if(psClase != '' && psClase != undefined){
		loInput.className	= psClase;
	}
	if(psTipo != '' && psTipo != undefined){
		loInput.type	= psTipo;
	}
	if(psValor != '' && psValor != undefined){
		loInput.value	= psValor;
	}
	if(psPlaceholder != '' && psPlaceholder != undefined){
		loInput.placeholder	= psPlaceholder;
	}
	return loInput;
}


/*-------------------------------------------	INICIO DE AREA DE COMBOS		-------------------------------------------*/
/****************************************************************************************************
	Funcion que permite cargar un combo dependiente cuando el combo primario cambie de valor
	
	ESTA FUNCION FALTA POR REVISAR Y CORREGIR
****************************************************************************************************/
function fpCombo_Dependiente(psCampo_Origen, psCombo, psOpcion, psSeleccion = '', pbMostrar_Vacio='S', pmCallback)
{
	let laCampo_Origen	= psCampo_Origen.split(',');
	let liI				= 0;
	let liError			= 0;
	let laDependiente	= [];
	let lsCombo_Origen	= '';
	let lsVacio			= '';
	
	const loFormulario = new FormData();
	loFormulario.append('txtOperacion', 'cargar_combo');
	loFormulario.append('txtArchivo', loF.txtArchivo.value);
	loFormulario.append('txtOpcion', psOpcion);
	loFormulario.append('txtSeleccion', psSeleccion);
	
	for(liI = 0 ; liI < laCampo_Origen.length ; liI++)
	{
		let lsCombo_Origen = document.querySelector("#"+laCampo_Origen[liI]).value;
		if(lsCombo_Origen != '-')
		{
			loFormulario.append('txtDependiente['+liI+']', lsCombo_Origen);
		}
		else
		{
			liError++;
		}
	}
	
	if(liError == 0){
		fpEnviar(loFormulario, lsResultado => {
			let loCombo = document.querySelector("#"+psCombo);
			loCombo.removeAttribute("disabled");
			
			loCombo.options.length	= 0;
			if(pbMostrar_Vacio == 'S')
			{
				lsVacio = '<option value="-">Seleccione un valor...</option>';
			}
			loCombo.innerHTML = lsVacio + lsResultado;
			
			$('#'+psCombo).trigger('change.select2');
			if(typeof(pmCallback) === 'function')
			{
				pmCallback();
			}
		},
		"../../controladores/corCombo.php",
		'texto');
	}else{
		// fpMostrar_Mensaje('Disculpe, algunos parametros no cumplen', '-');
	}
}

/****************************************************************************************************
	Funcion que permite cargar un combo dependiente cuando el combo primario cambie de valor
****************************************************************************************************/
function fpRecargar_Combo(psCombo, psOpcion = '', psSeleccion = '')
{
	const loFormulario = new FormData();
	loFormulario.append('txtOperacion', 'cargar_combo');
	loFormulario.append('txtArchivo', loF.txtArchivo.value);
	loFormulario.append('txtOpcion', psOpcion);
	loFormulario.append('txtSeleccion', psSeleccion);
	
	fpEnviar(loFormulario, lsResultado => {
		let loCombo = document.querySelector("#"+psCombo);
		loCombo.removeAttribute("disabled");
		loCombo.value			= '';
		loCombo.options.length	= 1;
		loCombo.innerHTML = loCombo.innerHTML + lsResultado;
	},
	"../../controladores/corCombo.php",
	'texto');	
}

/****************************************************************************************************
	Funcion que permite cargar un combo dependiente cuando el combo primario cambie de valor
****************************************************************************************************/
function fpCargar_Combo_Valores(psCombo, paDatos, piCantidad, piSeleccion)
{
	let liI		= 0;
	let liJ		= 0;
	let loCombo	= document.querySelector("#"+psCombo);
	loCombo.options.length	= 0;
	let loOpcion			= '';
	
	for(liI = 1 ; liI <= piCantidad ; liI++)
	{
		// paDatos[liI].laData.forEach(element => console.log(element));
		loOpcion		= document.createElement('option');
		loOpcion.value	= paDatos[liI].lsValor;
		loOpcion.text	= paDatos[liI].lsTexto;

		if(paDatos[liI].laData != undefined)
		{
			laData_Nombre	= Object.values(paDatos[liI].laData.lsNombre)
			laData_Valor	= Object.values(paDatos[liI].laData.lsValor)
			for(liJ = 0 ; liJ < laData_Nombre.length ; liJ++){
				loOpcion.setAttribute('data-'+laData_Nombre[liJ],laData_Valor[liJ]);
				
			}
		}
		
		if(piSeleccion == paDatos[liI].lsValor)
		{
			loOpcion.setAttribute('selected','selected');
		}
		loCombo.append(loOpcion);		
	}
	$("#"+psCombo).trigger('change.select2');
}

/****************************************************************************************************
	Funcion que permite colocar el valor por defecto al combo de unidad
****************************************************************************************************/
function fpLimpiar_Combo(psCombo)
{
	let laOpcion ={
		1: {
			lsValor: '-',
			lsTexto: 'Seleccione un valor...'
		}
	}
	fpCargar_Combo_Valores(psCombo, laOpcion, 1);
}

/*-------------------------------------------	FIN DE AREA DE COMBOS			-------------------------------------------*/


