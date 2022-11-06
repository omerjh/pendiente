<?php
	/*
	* visUsuario.php
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
				<form method="POST" name="frmFiltro" id="frmFiltro" class="" target="_blank" action="../reportes/xlsUsuario.php">
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
									<input type="text" class="form-control" name="txtFiltro_Nombre" id="txtFiltro_Nombre" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo'); fpVerificar_Usuario()" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'cmbFiltro_Estado')" value="" placeholder="Usuario">
								</div>
								
								<label for="cmbFiltro_Rol" class="col-form-label col-sm-2 col-md-1 mt-1">Rol:</label>
								<div class="col-sm-3 col-md-3 mt-1">
									<select class="form-control combos" name="cmbFiltro_Rol" id="cmbFiltro_Rol" onChange="fpListar()" onKeyPress="return fbCambiar_Foco(event,'cmbFiltro_Numero_Filas')" >
										<option value="-">Todos</option>
										<?php 
											$loCombo->fpPintar('rol','');
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
						<form method="POST" name="frmF" id="frmF" class="" action="../../controladores/corUsuario.php">
							<input type="hidden" name="txtOperacion" id="txtOperacion" value="">
							<input type="hidden" name="txtCodigo" id="txtCodigo" value="">
							<input type="hidden" name="txtArchivo" id="txtArchivo" value="<?php print $lsArchivo; ?>">
							<input type="hidden" name="txtUsuario_Registrado" id="txtUsuario_Registrado" value="">
							<input type="hidden" name="txtRol_Anterior" id="txtRol_Anterior" value="">
							<input type="hidden" name="txtFila" id="txtFila" value="0">

							<div class="form-group row">
								<label for="txtNombre" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Nombre de Usuario:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtNombre" id="txtNombre" onBlur="fpVerificar_Nombre_Usuario(this)" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtNombre_Persona')" value="" placeholder="Nombre de Usuario" maxlength="30" autocomplete="off">
									<div id="divMensaje_Usuario" class="text-danger d-none">Este usuario no está disponible</div>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtNombre_Persona" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Nombres y Apellidos:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtNombre_Persona" id="txtNombre_Persona" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtClave')" value="" placeholder="Nombres y Apellidos" maxlength="100">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtClave" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Contraseña:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="password" name="txtClave" id="txtClave" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'txtRepita_Clave')" value="" placeholder="Contraseña" maxlength="40" autocomplete="off">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtRepita_Clave" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Repita Contraseña:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="password" name="txtRepita_Clave" id="txtRepita_Clave" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'cmbPregunta1')" value="" placeholder="Repita Contraseña" maxlength="40">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="cmbPregunta1" class="col-form-label col-12 col-sm-3 offset-sm-1">Pregunta Secreta N° 1:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbPregunta1" id="cmbPregunta1" onKeyPress="return fbCambiar_Foco(event,'txtRespuesta1')" >
										<option value="-">Seleccione un valor...</option>
										<option value="Lugar de nacimiento del abuelo materno">¿Lugar de nacimiento del abuelo materno?</option>
										<option value="Nombre de su abuela materna">¿Nombre de su abuela materna?</option>
										<option value="Cuál es su mejor amigo de la infancia">¿Cuál es su mejor amigo de la infancia?</option>
										<option value="Cuál es su postre favorito">¿Cuál es su postre favorito?</option>
										<option value="Nombre de su primer novio(a)">¿Nombre de su primer novio(a)?</option>
										<option value="Marca de su primera bicicleta">¿Marca de su primera bicicleta?</option>
										<option value="Personaje histórico favorito">¿Personaje histórico favorito?</option>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtRespuesta1" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Respuesta Secreta N° 1:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtRespuesta1" id="txtRespuesta1" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'cmbPregunta2')" value="" placeholder="Respuesta Secreta N° 1" maxlength="40">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="cmbPregunta2" class="col-form-label col-12 col-sm-3 offset-sm-1">Pregunta Secreta N° 2:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbPregunta2" id="cmbPregunta2" onKeyPress="return fbCambiar_Foco(event,'txtRespuesta2')" >
										<option value="-">Seleccione un valor...</option>
										<option value="Ocupación del abuelo paterno">¿Ocupación del abuelo paterno?</option>
										<option value="Cuál es su pelicula favorita">¿Cuál es su pelicula favorita?</option>
										<option value="Cuál es su lugar favorito">¿Cuál es su lugar favorito?</option>
										<option value="En qué municipio se casó">¿En qué municipio se casó?</option>
										<option value="Nombre de su primer hijo(a)">¿Nombre de su primer hijo(a)?</option>
										<option value="Nombre de su mascota">¿Nombre de su mascota?</option>
										<option value="Cuál es su profesor favorito">¿Cuál es su profesor favorito?</option>
									</select>
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="txtRespuesta2" class="col-form-label col-12 col-sm-3 offset-sm-1 justify-content-center">Respuesta Secreta N° 2:</label>
								<div class="col-11 col-sm-6">
									<input class="form-control" type="text" name="txtRespuesta2" id="txtRespuesta2" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event,'cmbPregunta2')" value="" placeholder="Respuesta Secreta N° 2" maxlength="40">
								</div>
								<div class="col-1 col-sm-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>
							
							<div class="form-group row">
								<label for="cmbRol" class="col-form-label col-12 col-sm-3 offset-sm-1">Rol:</label>
								<div class="col-11 col-sm-6">
									<select class="form-control combos" name="cmbRol" id="cmbRol" onKeyPress="return fbCambiar_Foco(event,'btnGuardar')" >
										<option value="-">Seleccione un valor...</option>
										<?php 
											$loCombo->fpPintar('rol','');
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
							
							
							<fieldset>
								<legend>Buscar Punto de Venta</legend>
								<div class="form-group row">
									<div class="col-12 col-sm-4">
										<label for="cmbPunto_Venta" class="col-12">Punto de Venta:</label>
										<div class="col-12">
											<select class="form-control combos" name="cmbPunto_Venta" id="cmbPunto_Venta">
												<option value="-">Seleccione un valor...</option>
												<?php 
													$loCombo->fpPintar("punto_venta");
												?>
											</select>
										</div>
									</div>
									
									<div class="col-12 col-sm-3">
										<label for="radDefecto2" class="col-12">Por Defecto:</label>
										<div class="col-12">
											<div class="form-group clearfix">
												<div class="icheck-primary d-inline">
													<input type="radio" name="radDefectol" id="radDefecto1" value="S" onKeyPress="return fbCambiar_Foco(event,'btnAgregar_Punto_Venta')">
													<label for="radDefecto1">Si</label>
												</div>
												<div class="icheck-primary d-inline">
													<input type="radio" name="radDefectol" id="radDefecto2" value="N" onKeyPress="return fbCambiar_Foco(event,'btnAgregar_Punto_Venta')" checked>
													<label for="radDefecto2">No</label>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-2 col-sm-1">
										<label class="col-12 text-white">:</label>
										<button class="btn btn-primary" type="button" name="btnAgregar_Punto_Venta" id="btnAgregar_Punto_Venta" onClick="fpAgregar_Punto_Venta()">
											<span class="fa fa-plus"></span>
										</button>
									</div>
									
								</div>
							</fieldset>
							
							<div class="table-responsive">
								<table id="tabPunto_Venta" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th width="60%">Punto de Venta</th>
											<th width="30%">Por Defecto</th>
											<th width="10%"></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
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
<script language="javascript" src="./js/jsUsuario.js"></script>

</body>
</html>
