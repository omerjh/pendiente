<?php
	/*
	*   visSalir.php
	*      
	*   Copyright 2021 Hernández^3
	*      
	*   Este programa es software libre, puede redistribuirlo y / o modificar
	*   Bajo los términos de la GNU LicenciaPública General(GPL) publicada por
	*   La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
	*      
	*   Este programa se distribuye con la esperanza de que sea útil,
	*   Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
	*   COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
	*/
	session_start();
	unset($_SESSION["sogem_u_codigo"]);
	unset($_SESSION["sogem_u_cedula"]);
	unset($_SESSION["sogem_u_nombre_usuario"]);
	unset($_SESSION["sogem_u_nombre"]);
	unset($_SESSION["sogem_u_apellidos"]);
	unset($_SESSION["sogem_u_rol"]);
	unset($_SESSION["sogem_u_permisos"]);
	session_destroy();
	header("location: visAcceso.php");
?>