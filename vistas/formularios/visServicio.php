<?php
	/*
	* visServicio.php
	* 
	* Copyright 2021 Hernández^3
	* 		
	* Este programa es software libre, puede redistribuirlo y / o modificar
	* Bajo los términos de la GNU LicenciaPública General(GPL) publicada por
	* La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
	* 		
	* Este programa se distribuye con la esperanza de que sea útil,
	* Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
	* COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
	*/
	session_start();
	require_once("../../modelos/clsSeguridad.php");
	require_once("../../modelos/clsTabla.php");
	require_once("../../controladores/corCombo.php");
	
	$loSeguridad= new clsSeguridad();
	$loTabla	= new clsTabla();
	$loCombo	= new corCombo();
	
	// Obtener el nombre de la pagina que se esta visualizando
	$lsArchivo = $_SERVER['SCRIPT_NAME'];
	if ( strpos($lsArchivo, '/') !== FALSE )
	{
		$lsArchivo = array_pop(explode('/', $lsArchivo));
	}
	
	// Se verifica que el usuario haya iniciado sesión
	if(!array_key_exists("sogem_u_codigo", $_SESSION))
	{//	Si no inicio sesión: Se lo redirige a la vista de inicio junto con un mensaje
		$_SESSION["lsHacer"]	= "Malo";
		$_SESSION["lsMensaje"]	= "Disculpe, Debe iniciar sesión para ingresar al sistema";
		header("location: ../../index.php");
	}
	else
	{//	Verificamos si el usuario tiene permisos de visualizar la pagina
		if($loSeguridad->fbPermiso($_SESSION["sogem_u_permisos"], $lsArchivo) === false)
		{
			$_SESSION["lsHacer"]	= "Malo";
			$_SESSION["lsMensaje"]	= "Disculpe, no tiene permiso para ver la página anterior";
			header("location: visInicio.php");
		}
	}
	
	$laRespuesta = $loTabla->fpModulo_Vista($_SESSION["sogem_u_permisos"], $lsArchivo);
	$pbInicio = true;
?>
<!DOCTYPE html>
<html>
<?php require_once("./plantillas/head.php"); ?>
<body class="hold-transition sidebar-mini accent-blue">
<div class="wrapper">
	<!-- Navbar -->
	<?php require_once("./plantillas/navbar.php"); ?>
	<!-- /.navbar -->

	<!-- Main Sidebar Container -->
	<?php require_once("./plantillas/aside.php"); ?>
	<!-- /.Main Sidebar Container -->
	
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1><?php echo $laRespuesta['lsServicio_Largo']; ?></h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="visInicio.php">Home</a></li>
							<li class="breadcrumb-item"><?php echo $laRespuesta['lsModulo']; ?></li>
							<li class="breadcrumb-item active"><?php echo $laRespuesta['lsServicio_Corto']; ?></li>
						</ol>
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<!-- Inicio del formulario del filtro -->
				<form method="POST" name="frmFiltro" id="frmFiltro" class="" target="_blank" action="../reportes/xlsServicio.php">
					<input type="hidden" name="txtFiltro_Operacion" id="txtFiltro_Operacion" value="generar">
					<input type="hidden" name="txtFiltro_Archivo" id="txtFiltro_Archivo" value="<?php print $lsArchivo; ?>">
					<input type="hidden" name="txtFiltro_Pagina" id="txtFiltro_Pagina" value="1">
					<input type="hidden" name="txtFiltro_Codigo" id="txtFiltro_Codigo" value="">
					<div class="container-fluid bg-white">
						<div class="row">
							<div class="btn-group col-3 col-sm-2 col-md-1">
								<button class="btn btn-primary" type='button' name='btnNuevo' id="btnNuevo" onclick='fpNuevo()'> Nuevo </button>
							</div>
							
							<div class="btn-group col-3 col-sm-2 col-md-1">
								<button class="btn btn-success" type='button' name='btnBusqueda' id="btnBusqueda" onclick='fpListar()'> Buscar </button>
							</div>
							
							<div class="btn-group col-3 col-sm-2 col-md-1">
								<button class="btn btn-info" type='button' name='btnExcel' id="btnExcel" onclick='fpGenerar_Excel()'> Excel </button>
							</div>
							
							<div class="btn-group col-3 col-sm-2 col-md-1">
								<button class="btn btn-default d-block d-sm-none" type='button' name='btnFiltro' id="btnFiltro" data-toggle="collapse" data-target=".navbar-ex1-collapse"> Filtro </button>
							</div><!--  -->
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse show">
							<div class="form-group row mt-1">
								<label for="txtFiltro_Nombre" class="col-form-label col-sm-2 col-md-1 mt-1">Nombre:</label>
								<div class="col-sm-3 mt-1">
									<input type="text" class="form-control" name="txtFiltro_Nombre" id="txtFiltro_Nombre" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo'); fpVerificar_Servicio()" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'cmbFiltro_Estado')" value="" placeholder="Servicio">
								</div>
								
								<label for="cmbFiltro_Modulo" class="col-form-label col-sm-2 col-md-1 mt-1">Módulo:</label>
								<div class="col-sm-3 col-md-3 mt-1">
									<select class="form-control combos" name="cmbFiltro_Modulo" id="cmbFiltro_Modulo" onChange="fpListar()" onKeyPress="return fbCambiar_Foco(event,'cmbFiltro_Numero_Filas')" >
										<option value="-">Todos</option>
										<?php 
											$loCombo->fpPintar("modulo");
										?>
									</select>
								</div>
								
								<label for="cmbFiltro_Estado" class="col-form-label col-sm-2 col-md-1 mt-1">Estado:</label>
								<div class="col-sm-3 col-md-2 mt-1">
									<select class="form-control combos" name="cmbFiltro_Estado" id="cmbFiltro_Estado" onChange="fpListar()" onKeyPress="return fbCambiar_Foco(event,'cmbFiltro_Numero_Filas')" >
										<option value="-">Todos</option>
										<option value="A">Activo</option>
										<option value="I">Inactivo</option>
									</select>
								</div>
								
							</div>
							<div class="row">
								<label for="cmbFiltro_Numero_Filas" class="col-form-label col-sm-2 col-md-1">Filas:</label>
								<div class="col-sm-2">
									<select class="form-control combos" name="cmbFiltro_Numero_Filas" id="cmbFiltro_Numero_Filas" onChange="fpListar()" onKeyPress="return fbCambiar_Foco(event,'btnBusqueda')" >
										<option value="">Todos</option>
										<option value="10" selected="selected">10</option>
										<option value="20">20</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select>
								</div>
							</div>
						</div>
						<!-- Fin de Filtro -->
					
						<!-- Inicio de Resultados -->
						<div class="row ">
							<div class="container-fluid listado">
								<div id="divListado" class="fondo-blanco"></div>
								<div id="divPaginado" class="fondo-blanco"></div>
							</div>
						</div>
						<!-- Fin de Resultados -->
					</div>
				</form>
				<!-- Fin del formulario del filtro -->
			</div>
			<!-- /.container-fluid -->
		</section>
				
		<div id="divFormulario" class="modal fade" id="modal-lg">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Inicio del encabezado -->
					<div id="divFormulario_encabezado" class="modal-header">
						<h4 id="h4Encabezado_Formulario"></h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<!-- Fin del encabezado -->
					<!-- Inicio del contenido -->
					<div class="modal-body">
						<!-- Inicio del Formulario principal -->
						<form method="POST" name="frmF" id="frmF" class="" action="../../controladores/corServicio.php">
							<input type="hidden" name="txtOperacion" id="txtOperacion" value="">
							<input type="hidden" name="txtCodigo" id="txtCodigo" value="">
							<input type="hidden" name="txtArchivo" id="txtArchivo" value="<?php print $lsArchivo; ?>">

							<div class="form-group row">
								<label for="txtNombre" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Nombre:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtNombre" id="txtNombre" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtNombre_Largo')" value="" placeholder="Nombre" maxlength="50">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtNombre_Largo" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Nombre Largo:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtNombre_Largo" id="txtNombre_Largo" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtUrl')" value="" placeholder="Nombre Largo" maxlength="100">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtUrl" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Url:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtUrl" id="txtUrl" onBlur="fsBorrar_Espacios(this,'enlace')" onKeyPress="return fbSolo_Enlace(event,'radAbrir_Especial2')" value="" placeholder="Url" maxlength="100">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtUrl" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Abrir en otra pestaña:</label>
								<div class="col-11 col-sm-6">
									<div class="form-group clearfix">
										<div class="icheck-primary d-inline">
											<input type="radio" name="radAbrir_Especial" id="radAbrir_Especial1" value="S" onKeyPress="return fbCambiar_Foco(event,'txtIcono')">
											<label for="radAbrir_Especial1">Si</label>
										</div>
										<div class="icheck-primary d-inline">
											<input type="radio" name="radAbrir_Especial" id="radAbrir_Especial2" value="N" onKeyPress="return fbCambiar_Foco(event,'txtIcono')" checked>
											<label for="radAbrir_Especial2">No</label>
										</div>
									</div>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtIcono" class="col-form-label col-12 col-sm-3 offset-sm-1">Ícono:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtIcono" id="txtIcono" onBlur="fpMostrar_Icono(this, 'divDemostracion', 'spaIcono')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtPosicion')" value="" placeholder="Ícono" maxlength="50">
								</div>
								<div class="col-1 col-sm-1">
									<!--<span class="fa fa-asterisk text-danger"></span>-->
								</div>
							</div>
							
							<div class="form-group row d-none" id="divDemostracion">
								<label for="" class="col-form-label col-12 col-sm-3 offset-sm-1">Demostración:</label>
								<div class="col-11 col-sm-6">
									<span class="" id="spaIcono"></span>
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtPosicion" class="col-form-label col-12 col-sm-3 offset-sm-1">Posición:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="number" name="txtPosicion" id="txtPosicion" onBlur="fsBorrar_Espacios(this,'numero')" onKeyPress="return fbSolo_Numeros(event,this,'btnGuardar')" value="0" placeholder="Posición" maxlength="3" min="0" max="50">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="cmbModulo" class="col-form-label col-12 col-sm-3 offset-sm-1">Módulo:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbModulo" id="cmbModulo" onKeyPress="return fbCambiar_Foco(event,'btnGuardar')" >
										<option value="-">Seleccione un valor...</option>
										<?php 
											$loCombo->fpPintar("modulo");
										?>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="cmbEstado" class="col-form-label col-12 col-sm-3 offset-sm-1">Estado:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control" name="cmbEstado" id="cmbEstado" disabled>
										<option value="A">Activo</option>
										<option value="I">Inactivo</option>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<!--<span class="fa fa-asterisk text-danger"></span> -->
								</div>
							</div>
							
							<div class="form-group row justify-content-center">
								<div class="btn-group col-12 col-sm-6  margenes_botones">
									<button class="btn col-sm-6 btn-danger" type="button" name="btnCancelar" id="btnCancelar" onClick="fpCancelar()"> Cancelar </button>
									<button class="btn col-sm-6 btn-primary" type="button" name="btnGuardar" id="btnGuardar" onClick="fpGuardar()"> Guardar </button>
								</div>
							</div>
							
						</form>
						<!-- Fin del formulario principal -->
					</div>
					<!-- Fin del contenido -->
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal Formulario -->
		
		<?php require_once("./plantillas/modal_activar.php"); ?>
		<?php require_once("./plantillas/modal_desactivar.php"); ?>
		
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<?php require_once("./plantillas/footer.php"); ?>
</div>
<!-- ./wrapper -->

<?php require_once("./plantillas/javascript.php"); ?>
<script language="javascript" src="./js/jsServicio.js"></script>

</body>
</html>
