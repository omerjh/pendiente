<?php
	/*
	* visCambiar_Clave_Olvido.php
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
	
	$loSeguridad= new clsSeguridad();
	$loTabla	= new clsTabla();
	
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
	$laRespuesta['lsServicio_Largo']= 'Cambiar de Contraseña';
	$laRespuesta['lsServicio_Corto']= 'Cambiar de Contraseña';
	$laRespuesta['lsModulo']		= 'Usuario';
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
				<form method="POST" name="frmF" id="frmF" class="" action="../../controladores/corValidar.php">
					<input type="hidden" name="txtOperacion" id="txtOperacion" value="cambiar_clave_confirmado">
					<input type="hidden" name="txtArchivo" id="txtArchivo" value="<?php print $lsArchivo; ?>">
					<div class="container-fluid bg-white">
						<div class="collapse navbar-collapse navbar-ex1-collapse show">
							<div class="form-group row justify-content-center">
								<label for="txtClave" class="col-form-label col-sm-4 col-md-4 col-lg-3 mt-1">Contraseña Nueva:</label>
								<div class="col-11 col-sm-5 mt-1">
									<input type="password" class="form-control" name="txtClave" id="txtClave" onBlur="fsBorrar_Espacios(this,'clave')" onKeyPress="return fbSolo_Clave(event,'txtRepita_Clave')" value="" placeholder="Contraseña Nueva">
								</div>
								<div class="col-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>

							<div class="form-group row justify-content-center">
								<label for="txtRepita_Clave" class="col-form-label col-sm-4 col-md-4 col-lg-3 mt-1">Repita Contraseña:</label>
								<div class="col-11 col-sm-5 mt-1">
									<input type="password" class="form-control" name="txtRepita_Clave" id="txtRepita_Clave" onBlur="fsBorrar_Espacios(this,'clave')" onKeyPress="return fbSolo_Clave(event,'btnGuardar')" value="" placeholder="Repita la Contraseña">
								</div>
								<div class="col-1">
									<span class="fa fa-asterisk text-danger"></span> 
								</div>
							</div>

							<div class="form-group row justify-content-center">
								<div class="btn-group col-12 col-sm-2  margenes_botones">
									<button class="btn btn-primary" type="button" name="btnGuardar" id="btnGuardar" onClick="fpGuardar()"> Guardar </button>
								</div>
							</div>
						</div>
						<!-- Fin de Filtro -->
					
					</div>
				</form>
				<!-- Fin del formulario del filtro -->
			</div>
			<!-- /.container-fluid -->
		</section>
		
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<?php require_once("./plantillas/footer.php"); ?>
</div>
<!-- ./wrapper -->

<?php require_once("./plantillas/javascript.php"); ?>
<script language="javascript" src="./js/jsCambiar_Clave_Olvido.js"></script>

</body>
</html>
