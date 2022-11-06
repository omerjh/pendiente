var loF		= document.querySelector('#frmF');
var loFiltro= document.querySelector('#frmFiltro');
var gsUrl	= "../../controladores/corRol.php";

window.onload = () => {
};

/****************************************************************************************************
	Funcion que envia el formulario del filtro al archivo que generará el reporte en formato excel
****************************************************************************************************/
function fpGenerar_Excel()
{
	loFiltro.submit();
}

/****************************************************************************************************
	Funcion que envia el formulario del filtro al archivo que generará el reporte en formato excel
****************************************************************************************************/
function fpGenerar_Pdf()
{
	// cambiar action para que mande al pdf
	let lsRuta = loFiltro.action;
	loFiltro.action = "../reportes/pdfReporte_Listado_Permisos.php";
	loFiltro.submit();
	loFiltro.action = lsRuta;
}

/****************************************************************************************************
	Funcion que selecciona todos los roles del formulario del filtro
****************************************************************************************************/
function fpSeleccionar_Todo(poCampo)
{
	for(liI = 1 ; liI < loFiltro.txtCantidad_Roles.value ; liI++)
	{
		document.querySelector("#chkRol"+liI).checked = poCampo.checked;
	}
}