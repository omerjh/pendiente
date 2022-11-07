<?php
	/*
	* visPendiente.php
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
				<form method="POST" name="frmFiltro" id="frmFiltro" class="" target="_blank" action="../reportes/xlsPendiente.php">
					<input type="hidden" name="txtFiltro_Operacion" id="txtFiltro_Operacion" value="generar">
					<input type="hidden" name="txtFiltro_Archivo" id="txtFiltro_Archivo" value="<?php print $lsArchivo; ?>">
					<input type="hidden" name="txtFiltro_Pagina" id="txtFiltro_Pagina" value="1">
					<input type="hidden" name="txtFiltro_Codigo" id="txtFiltro_Codigo" value="">
					<div class="container-fluid bg-white">
						<div class="row">
							<div class="btn-group col-xs-3 col-sm-2 col-md-1">
								<button class="btn btn-primary" type='button' name='btnNuevo' id="btnNuevo" onclick='fpNuevo()'> Nuevo </button>
							</div>
							
							<div class="btn-group col-xs-3 col-sm-2 col-md-1">
								<button class="btn btn-success" type='button' name='btnBusqueda' id="btnBusqueda" onclick='fpListar()'> Buscar </button>
							</div>
							
							<div class="btn-group col-xs-3 col-sm-2 col-md-1">
								<button class="btn btn-info" type='button' name='btnExcel' id="btnExcel" onclick='fpGenerar_Excel()'> Excel </button>
							</div>
							
							<div class="btn-group col-xs-3 col-sm-2 col-md-1">
								<button class="btn btn-default d-block d-sm-none" type='button' name='btnFiltro' id="btnFiltro" data-toggle="collapse" data-target=".navbar-ex1-collapse"> Filtro </button>
							</div><!--  -->
						</div>
						<div class="collapse navbar-collapse navbar-ex1-collapse show">
							<div class="form-group row mt-1">
								<label for="txtFiltro_Fecha" class="col-form-label col-sm-2 col-md-1 mt-1">Fecha:</label>
								<div class="col-sm-3 mt-1">
									<div class="input-group mb-3">
										<input class="form-control" type="text" name="txtFiltro_Fecha" id="txtFiltro_Fecha"  placeholder="Fecha"/>
										<div class="input-group-append">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
									</div>
								</div>
								
								<label for="txtFiltro_Nombre" class="col-form-label col-sm-2 col-md-1 mt-1">Nombre:</label>
								<div class="col-sm-4 mt-1">
									<input type="text" class="form-control" name="txtFiltro_Nombre" id="txtFiltro_Nombre" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo'); fpVerificar_Pendiente();" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'cmbFiltro_Estado')" value="" placeholder="Nombre">
								</div>
								
								<label for="cmbFiltro_Estado" class="col-form-label col-sm-2 col-md-1 mt-1">Estado:</label>
								<div class="col-sm-3 col-md-2 mt-1">
									<select class="form-control combos" name="cmbFiltro_Estado" id="cmbFiltro_Estado" onChange="fpListar()" onKeyPress="return fbCambiar_Foco(event,'cmbFiltro_Numero_Filas')" >
										<option value="-">Todos</option>
										<option value="A">Activo</option>
										<option value="I">Inactivo</option>
									</select>
								</div>
								
								<label for="cmbFiltro_Numero_Filas" class="col-form-label col-sm-2 col-md-1 mt-1">Filas:</label>
								<div class="col-sm-2 mt-1">
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
						<form method="POST" name="frmF" id="frmF" class="" action="../../controladores/corPendiente.php">
							<input type="hidden" name="txtOperacion" id="txtOperacion" value="">
							<input type="hidden" name="txtCodigo" id="txtCodigo" value="">
							<input type="hidden" name="txtArchivo" id="txtArchivo" value="<?php print $lsArchivo; ?>">

							<div class="form-group row">
								<label for="txtNombre" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Nombre:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtNombre" id="txtNombre" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtCantidad')" value="" placeholder="Nombre" maxlength="30">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>

							<div class="form-group row">
								<label for="txtCantidad" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Cantidad:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtCantidad" id="txtCantidad" onBlur="fsBorrar_Espacios(this,'numero_decimales')" onKeyPress="return fbSolo_Numeros(event, this, 'cmbUnidad')" value="" placeholder="Cantidad" maxlength="30">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div> 

							<div class="form-group row">
								<label for="cmbUnidad" class="col-form-label col-12 col-sm-3 offset-sm-1">Unidad:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbUnidad" id="cmbUnidad" onKeyPress="return fbCambiar_Foco(event,'txtObservacion')" >
										<option value="">Seleccion un Valor...</option>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtObservacion" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Observación:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtObservacion" id="txtObservacion" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtFecha_Estimada')" value="" placeholder="Observación" maxlength="30">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>

							<div class="form-group row">
								<label for="txtFecha_Estimada" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Fecha Estimada:</label>
								<div class="col-11 col-sm-6">
									<div class="input-group">
										<input class="form-control" type="text" name="txtFecha_Estimada" id="txtFecha_Estimada"  placeholder="Fecha Estimada" onKeyPress="return fbCambiar_Foco(event,'cmbResponsable')"/>
										<div class="input-group-append">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
									</div>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>

							<div class="form-group row">
								<label for="cmbResponsable" class="col-form-label col-xs-12 col-sm-3 offset-sm-1">Responsable:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbResponsable" id="cmbResponsable" onKeyPress="return fbCambiar_Foco(event,'cmbCondicion')">
										<option value="">Seleccion un Valor...</option>
										<option value="V">Vanessa</option>
										<option value="O">Omer</option>
										<option value="A">Ambos</option>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span>
								</div>
							</div>
							
							<div class="form-group row">
								<label for="cmbCondicion" class="col-form-label col-xs-12 col-sm-3 offset-sm-1">Condición:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbCondicion" id="cmbCondicion" onKeyPress="return fbCambiar_Foco(event,'txtFecha_Ejecucion')">
										<option value="P">Pendiente</option>
										<option value="R">Proceso</option>
										<option value="C">Completado</option>
										<option value="I">Incompleto</option>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span>
								</div>
							</div>
							
							<hr>
							<div class="form-group row">
								<label for="txtFecha_Ejecucion" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Fecha Ejecución:</label>
								<div class="col-11 col-sm-6">
									<div class="input-group">
										<input class="form-control" type="text" name="txtFecha_Ejecucion" id="txtFecha_Ejecucion" placeholder="Fecha Ejecución" onKeyPress="return fbCambiar_Foco(event,'txtCosto')" disabled />
										<div class="input-group-append">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
									</div>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>


							<div class="form-group row">
								<label for="txtCosto" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Costo:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtCosto" id="txtCosto" onBlur="fsBorrar_Espacios(this,'numero_decimales')" onKeyPress="return fbSolo_Numeros(event, this,'btnGuardar')" value="" placeholder="Cantidad" maxlength="12" disabled>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>

							<div class="form-group row">
								<label for="cmbEstado" class="col-form-label col-xs-12 col-sm-3 offset-sm-1">Estado:</label>
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
<script language="javascript" src="./js/jsPendiente.js"></script>

</body>
</html>
