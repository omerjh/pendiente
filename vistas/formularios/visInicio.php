<?php
	/*
	* visInicio.php
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
	require_once("../../modelos/clsTabla.php");
	$loTabla = new clsTabla();
	
	// Obtener el nombre de la pagina que se esta visualizando
	$lsArchivo = $_SERVER['SCRIPT_NAME'];
	if( strpos($lsArchivo, '/') !== FALSE )
	{
		$lsArchivo = array_pop(explode('/', $lsArchivo));
	}
	
	// Se verifica que el usuario haya iniciado sesión
	if(!array_key_exists("sogem_u_codigo", $_SESSION))
	{// Si no inicio sesión: Se lo redirige a la vista de inicio junto con un mensaje
		$_SESSION["sogem_lsHacer2"]	= "Malo";
		$_SESSION["sogem_lsMensaje2"] = "Disculpe, Debe iniciar sesión para ingresar al sistema";
		header("location: ../../index.php");
	}
	
	// print "<pre>";
	// print_r($_SESSION);
	// print "<pre>";

	// Variables de control -Operación exitosa, error y otros-
	$lsHacer	=$_SESSION["sogem_lsHacer2"];
	$lsMensaje=$_SESSION["sogem_lsMensaje2"];
	
	date_default_timezone_set('America/Caracas');
	$loFecha = new DateTime();
	// $lnHora = intval(date("H",time()-1800));
	$lnHora = intval($loFecha->format('H'));
	
	if($lnHora >= 5 and $lnHora < 12)
	{
		$lsSaludo = 'Buenos días: ';
	}
	elseif($lnHora >= 12 and $lnHora < 19)
	{
		$lsSaludo = 'Buenas tardes: ';
	}
	elseif($lnHora >= 19)
	{
		$lsSaludo = 'Buenas noches: ';
	}
	elseif($lnHora >= 0 and $lnHora < 5)
	{
		$lsSaludo = 'Buenas noches: ';
	}
	
	$lsSaludo .= mb_convert_case($_SESSION["sogem_u_nombre"], MB_CASE_TITLE, "UTF-8");
	$pbInicio = true;
	$laRespuesta['lsServicio_Largo'] = 'Inicio';
	
	unset($_SESSION["sogem_lsHacer2"]);
	unset($_SESSION["sogem_lsMensaje2"]);
	unset($_SESSION["sogem_lsHacer"]);
	unset($_SESSION["sogem_lsOperacion"]);
	unset($_SESSION["sogem_lsMensaje"]);
	unset($_SESSION["sogem_laVariables"]);
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
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1><?php echo $lsSaludo; ?></h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">Home</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
	</div>
	<!-- /.content-wrapper -->
	<?php require_once("./plantillas/footer.php"); ?>
</div>
<!-- ./wrapper -->

<?php require_once("./plantillas/javascript.php"); ?>

</body>
</html>
