<?php
	/*
	* index.php
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
	
	if(isset($_SESSION["sogem_u_nrol"]))
	{//	Si no inicio sesión: Se lo redirige a la vista de inicio junto con un mensaje
		header("location: vistas/formularios/visInicio.php");
	}
	else
	{
		$lsArchivo = $_SERVER['SCRIPT_NAME'];
		if ( strpos($lsArchivo, '/') !== FALSE )
		{
			$lsArchivo = array_pop(explode('/', $lsArchivo));
		}
		
		$lsHacer	=$_SESSION["sogem_lsHacer2"];
		$lsMensaje	=$_SESSION["sogem_lsMensaje2"];
		unset($_SESSION["sogem_lsHacer2"]);
		unset($_SESSION["sogem_lsMensaje2"]);
		
		unset($_SESSION["sogem_u_codigo"]);
		unset($_SESSION["sogem_u_nombre_usuario"]);
		unset($_SESSION["sogem_u_nombre"]);
		unset($_SESSION["sogem_u_rol"]);
		unset($_SESSION["sogem_u_permisos"]);
		session_destroy();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SOGEM</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="vistas/formularios/admin/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="vistas/formularios/admin/css/ionicons.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="vistas/formularios/admin/css/icheck-bootstrap.min.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="vistas/formularios/admin/plugins/sweetalert2/sweetalert2.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="vistas/formularios/admin/css/toastr.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="vistas/formularios/admin/css/adminlte.min.css">
	<!-- Font: Source Sans Pro -->
	<link href="vistas/formularios/admin/css/fonts.css" rel="stylesheet">
	<!-- Icono del sistema -->
	<link rel="shortcut icon" href="vistas/formularios/imagenes/icono.png" />
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<b>SOGEM</b>
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Iniciar Sesión</p>

			<form id="frmF" name="frmF" action="controladores/corValidar.php" method="post" onSubmit="return fbValidar()">
				<input type="hidden" name="txtOperacion" id="txtOperacion" value="iniciar_sesion">
				<input type="hidden" name="txtHacer" id="txtHacer" value="<?php print $lsHacer; ?>">
				<input type="hidden" name="txtMensaje" id="txtMensaje" value="<?php print $lsMensaje; ?>">
				<input type="hidden" name="txtArchivo" id="txtArchivo" value="<?php print $lsArchivo; ?>">

				<div class="input-group mb-3">
					<input type="text" class="form-control"	name="txtNombre" id="txtNombre" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event)" placeholder="Usuario" value="">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-user"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" class="form-control" name="txtClave" id="txtClave" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo')" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event)" placeholder="Contraseña" value="">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
					</div>
					<!-- /.col -->
				</div>

				<div id="divFormulario" class="modal fade">
					<div class="modal-dialog modal-md">
						<div class="modal-content">
							<!-- Inicio del encabezado -->
							<div id="divFormulario_encabezado" class="modal-header">
								<h4 id="h4Encabezado_Formulario">¿Olvidó su Contraseña?</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<!-- Fin del encabezado -->
							<!-- Inicio del contenido -->
							<div class="modal-body">
								<div class="form-group row">
									<!--<label for="txtNombre_Recuperar" class="col-form-label col-xs-12 col-sm-3 offset-sm-1 justify-content-center">Ingrese su usuario</label>-->
									<div class="input-group col-12 col-sm-12">
										<input class="form-control" type="text" name="txtNombre_Recuperar" id="txtNombre_Recuperar" onBlur="fsBorrar_Espacios(this,'texto_numero_simbolo');" onKeyPress="return fbSolo_Texto_Numeros_Simbolos(event)" value="" placeholder="Ingrese su usuario" maxlength="50">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<span class="fas fa-user"></span> 
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row justify-content-center">
									<div class="btn-group col-12 col-sm-6  margenes_botones">
									</div>
								</div>
							</div>
							<!-- Fin del contenido -->
							<div class="modal-footer">
								<button class="btn btn-danger" type="button" name="btnCancelar" id="btnCancelar" onClick="fpCancelar_Recuperar()" data-dismiss="modal"> Cancelar </button>
								<button class="btn btn-primary" type="button" name="btnRecuperar" id="btnRecuperar" onClick="fpRecuperar_Enviar()"> Enviar </button>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal Formulario -->
				
			</form>

			<p class="mb-1">
				<a href="#divFormulario" data-toggle='modal'>Olvidé mi Contraseña</a>
			</p>
		</div>
		<!-- /.login-card-body -->
		
		
		
	</div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="./vistas/formularios/admin/js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./vistas/formularios/admin/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./vistas/formularios/admin/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="./vistas/formularios/admin/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- Toastr -->
<script src="./vistas/formularios/admin/js/toastr.min.js"></script>
<!-- Index -->
<script src="./vistas/formularios/js/jsIndex.js"></script>
<!-- jsValidaciones -->
<script src="./vistas/formularios/js/jsValidaciones.js"></script>

</body>
</html>
