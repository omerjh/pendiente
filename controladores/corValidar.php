<?php
	/*
	*	corValidar.php
	*	
	*   Copyright 2021 Hernández^3
	*
	*	Este programa es software libre, puede redistribuirlo y / o modificar
	*	Bajo los términos de la GNU LicenciaPública General(GPL) publicada por
	*	La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
	*      
	*	Este programa se distribuye con la esperanza de que sea útil,
	*	Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
	*	COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
	*/
	// error_reporting(E_ALL);
	// ini_set('display_errors','1');
	session_start();
	require_once("../modelos/clsSeguridad.php");
	$loTabla = new clsSeguridad();
	
	/****************************************************************************************************
		Validación que evita que un usuario:
			-Pueda acceder al controlador directamente sin enviar la operación a realizar.

		Si se intenta algunas de esas opciones, se envia un mensaje al usuario y es redirigido al index
		principal.
	****************************************************************************************************/
	if(array_key_exists('txtOperacion',$_POST) === false or $_POST["txtOperacion"] == NULL)
	{
		/*$_SESSION["sogem_lsHacer2"]	= "Malo";
		$_SESSION["sogem_lsMensaje2"]	= "Disculpe, la página solicitada necesita unos datos que no fueron suministrados";*/
		header("location: ../vistas/formularios/visAcceso.php");
	}
	else
	{	
		require_once("../modelos/clsValidar.php");
		$lsOperacion=$_POST["txtOperacion"];
		$loUsuario	= new clsValidar();
		if($loUsuario->fpSetFormulario($_POST) === true)
		{
			$lsRuta = "../vistas/formularios/visAcceso.php";
			switch($lsOperacion)
			{
				case "iniciar_sesion":
					$_SESSION["sogem_u_nusuario"] = trim($_POST["txtNombre"]);
					$lbResultado = $loUsuario->fbIniciar_Sesion();
					if($lbResultado === true)
					{
						$lsRuta="../vistas/formularios/visInicio.php";
						$laUsuario = $loUsuario->faGetVariables();
						
						$_SESSION["sogem_u_codigo"]		=trim($laUsuario["u_codigo"]);
						$_SESSION["sogem_u_nusuario"]	=trim($laUsuario["u_nusuario"]);
						$_SESSION["sogem_u_nusuario2"]	=trim($laUsuario["u_nusuario"]);
						$_SESSION["sogem_u_nombre"]		=trim($laUsuario["u_nombre"]);
						$_SESSION["sogem_u_crol"]		=trim($laUsuario["u_crol"]);
						$_SESSION["sogem_u_nrol"]		=trim($laUsuario["u_nrol"]);
						$_SESSION["sogem_u_permisos"]	=$laUsuario["u_permisos"];
						
						if(trim($laUsuario["u_imagen_perfil"]) != "")
						{
							$_SESSION["sogem_u_imagen_perfil"]=trim($laUsuario["u_imagen_perfil"]);
						}
						else
						{
							$_SESSION["sogem_u_imagen_perfil"]="defecto.jpg";
						}
					}
					elseif($lbResultado === "inactivo")
					{
						$_SESSION["sogem_lsHacer2"]	="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Disculpe, su usuario se encuentra inactivo, comuníquese con el administrador de sistema";
					}
					elseif($lbResultado === "rol_inactivo")
					{
						$laUsuario = $loUsuario->faGetVariables();
						$_SESSION["sogem_lsHacer2"]	="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Disculpe, los usuarios de los ".$laUsuario["u_nrol"]."(S) estan temporalmente desactivado y no pueden iniciar sesión";
					}
					elseif($lbResultado === "mal_intento")
					{
						$_SESSION["sogem_lsHacer2"]	="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Disculpe, el usuario o la contraseña es invalida.";
					}
					elseif($lbResultado === "nuevo")
					{
						$laUsuario = $loUsuario->faGetVariables();
						$_SESSION["sogem_u_codigo"]	=trim($laUsuario["u_codigo"]);
						$_SESSION["sogem_u_nusuario"]	=trim($laUsuario["u_nusuario"]);
						$_SESSION["sogem_u_nombre"]	=trim($laUsuario["u_nombre"]);
						if(trim($laUsuario["u_imagen_perfil"]) != "")
						{
							$_SESSION["sogem_u_imagen_perfil"]=trim($laUsuario["u_imagen_perfil"]);
						}
						else
						{
							$_SESSION["sogem_u_imagen_perfil"]="defecto.jpg";
						}
						$lsRuta="../vistas/formularios/visCambiar_Clave_Nuevo.php";
					}
					else
					{
						$_SESSION["sogem_lsHacer2"]	="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Disculpe, el usuario o la contraseña es invalida. Recuerde que al ingresar la clave errada en tres (3) intentos consecutivos, su acceso será bloqueado por medidas de seguridad.";
					}
					break;
				
				case "cambiar_clave":
					if($loUsuario->fbCambiar_Clave_Nuevo() === true)
					{
						$_SESSION["sogem_lsHacer2"]		="Listo";
						$_SESSION["sogem_lsMensaje2"]	="Su contraseña e información de seguridad fue actualizada exitosamente";
					}
					else
					{
						$_SESSION["sogem_lsHacer2"]	="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Ocurrio un error y no se guardaron los datos por favor inicie sesion nuevamente con su contraseña asignada, ya que no fue cambiada";
					}
					break;
					
				case "recuperar_clave":
					if(!array_key_exists('sogem_u_crol',$_SESSION))
					{
						if($loUsuario->fbVerificar_Usuario() === true)
						{
							$lsRuta = "../vistas/formularios/visRecuperar_Clave.php";
							$laUsuario = $loUsuario->faGetVariables();
							$_SESSION["sogem_u_codigo"]		=trim($laUsuario["u_codigo"]);
							$_SESSION["sogem_u_nusuario"]	=trim($laUsuario["u_nusuario"]);
							$_SESSION["sogem_u_nombre"]		=trim($laUsuario["u_nombre"]);
							$_SESSION["sogem_u_pegunta_1"]	=trim($laUsuario["u_pegunta_1"]);
							$_SESSION["sogem_u_pegunta_2"]	=trim($laUsuario["u_pegunta_2"]);
							$_SESSION["sogem_u_pegunta_2"]	=trim($laUsuario["u_pegunta_2"]);
							if(trim($laUsuario["u_imagen_perfil"]) != "")
							{//FALTA LA FOTO DE PERFIL EN LA TABLA
								$_SESSION["sogem_u_imagen_perfil"]=trim($laUsuario["u_imagen_perfil"]);
							}
							else
							{
								$_SESSION["sogem_u_imagen_perfil"]="defecto.jpg";
							}
						}
						else
						{
							$_SESSION["sogem_lsHacer2"]	="Malo";
							$_SESSION["sogem_lsMensaje2"]	="Disculpe, el usuario proporcionado no se encuentra registrado";
						}
					}
					else
					{
						unset($_SESSION["sogem_u_nrol"]);
						$_SESSION["sogem_lsHacer2"]	="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Disculpe, usted tenía una sesión activa, por lo que no puede recuperar la contraseña";
					}
					break;
				
				case "verificar_respuestas":
					if($loUsuario->fbVerificar_Respuestas($_SESSION["sogem_u_nusuario"]) === true)
					{
						$lsRuta		= "../vistas/formularios/visCambiar_Clave_Olvido.php";
						$laUsuario	= $loUsuario->faGetVariables();
						$_SESSION["sogem_u_codigo"]		=trim($laUsuario["u_codigo"]);
						$_SESSION["sogem_u_nusuario"]	=trim($laUsuario["u_nusuario"]);
						$_SESSION["sogem_u_nombre"]		=trim($laUsuario["u_nombre"]);
					}
					else
					{
						print "malo";
						// $lsRuta = "../index.php";
						// $_SESSION["sogem_lsHacer2"]		="Malo";
						// $_SESSION["sogem_lsMensaje2"]	="Disculpe, las respuestas no coinciden con las que guardo anteriormente";
					}
					break;
				
				case "cambiar_clave_confirmado":
					$lsRuta = "../index.php";
					if($loUsuario->fbCambiar_Clave_olvido() === true)
					{
						$_SESSION["sogem_lsHacer2"]		="Listo";
						$_SESSION["sogem_lsMensaje2"]	="Su contraseña fue cambiada exitosamente";
					}
					else
					{
						$_SESSION["sogem_lsHacer2"]		="Malo";
						$_SESSION["sogem_lsMensaje2"]	="Ocurrio un error y no se guardaron los datos intentelo de nuevo";
					}
					break;
			}
		}
		else
		{
			$lsRuta = "../vistas/formularios/visInicio.php";
			$_SESSION["sogem_lsHacer2"]		="Malo";
			$_SESSION["sogem_lsMensaje2"]	="Disculpe, los datos necesarios para llevar a cabo la operación no se encontraron";
		}
		$loUsuario->fpDesconectar();
		header("location: $lsRuta");
	}
?>
