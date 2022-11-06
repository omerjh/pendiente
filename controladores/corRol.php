<?php
	/*
	*	corRol.php
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
	session_start();
	require_once("../modelos/clsSeguridad.php");
	
	$loSeguridad = new clsSeguridad();
	
	/****************************************************************************************************
		Validación que evita que un usuario:
			-Hacer uso de este archivo si no ha iniciado sesión.
			-Pueda acceder al controlador directamente sin enviar la operación a realizar.
			-Pueda acceder a este controlador sino tiene permiso.
		
		Si se intenta algunas de esas opciones, se envia un mensaje al usuario y es redirigido al index
		principal.
	****************************************************************************************************/
	if(array_key_exists('sogem_u_crol',$_SESSION) === false)
	{
		$_SESSION["sogem_lsHacer2"]	= false;
		$_SESSION["sogem_lsMensaje2"]	= "Disculpe, debe iniciar sesión para ingresar al sistema";
		header("location: ../index.php");
	}
	elseif($loSeguridad->fbPermiso($_SESSION["sogem_u_permisos"], $_POST["txtArchivo"]) === false)
	{
		$_SESSION["sogem_lsHacer2"]	= false;
		$_SESSION["sogem_lsMensaje2"]	= "Disculpe, no tiene permiso para ver la página anterior";
		header("location: ../vistas/formularios/visInicio.php");
	}
	elseif(array_key_exists('txtOperacion',$_POST) === false or $_POST["txtOperacion"] == NULL)
	{
		$_SESSION["sogem_lsHacer2"]	= false;
		$_SESSION["sogem_lsMensaje2"]	= "Disculpe, la página solicitada necesita unos datos que no fueron suministrados";
		header("location: ../vistas/formularios/visInicio.php");
	}
	else
	{
		require_once("../modelos/clsRol.php");
		$loRol					= new clsRol();
		if($loRol->fpSetFormulario($_POST) === true)
		{
			switch($_POST["txtOperacion"])
			{
				case "buscar":
					if($loRol->fbBuscar() === true)
					{
						$laRespuesta['lbEstado']= true;
						$laRespuesta['laDatos']	= $loRol->faGetVariables();
					}
					else
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, el código que intenta buscar no se encuentra registrado";
					}
					break;
					
				case "incluir":
					if($loRol->fbBuscar_Nombre() === true)
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, el nombre del rol que desea guardar se encuentra registrado";
					}
					elseif($loRol->fbIncluir() === true)
					{
						$laRespuesta['lbEstado']	= true;
						$laRespuesta['lsMensaje']	= "Se ha guardado exitosamente";
					}
					else
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
		
				case "modificar":
					if($loRol->fbBuscar_Nombre(true) === true)
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, el nombre del módulo que desea guardar se encuentra registrado";
					}
					elseif($loRol->fbModificar() === true)
					{
						$laRespuesta['lbEstado']	= true;
						$laRespuesta['lsMensaje']	= "Se ha modificado exitosamente";
					}
					else
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
		
				case "activar":
					if($loRol->fbActivar() === true)
					{
						$laRespuesta['lbEstado']	= true;
						$laRespuesta['lsMensaje']	= "Se ha activado exitosamente";
					}
					else
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
		
				case "desactivar":
					if($loRol->fbDesactivar() === true)
					{
						$laRespuesta['lbEstado']	= true;
						$laRespuesta['lsMensaje']	= "Se ha desactivado exitosamente";
					}
					else
					{
						$laRespuesta['lbEstado']	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
				
				case "listar":
					require_once("../modelos/clsTabla.php");
					if($loRol->fbListar($_POST["laFormulario"]) === true)
					{
						$laVariables= $loRol->faGetVariables();
						$loTabla	= new clsTabla();
						$loTabla->fpSetPorcentaje($_POST["lsPorcentajes"]);
						$loTabla->fpSetAlineacion($_POST["lsAlineacion"]);
						$loTabla->fpSetTitulos($_POST["lsTitulos"]);
						$loTabla->fpSetFondo_Encabezado($_POST["lsFondo_Encabezado"]);
						$loTabla->fpSetTitles($_POST["lsTitles"]);
						$loTabla->fpSetPie_Tabla($_POST["lbPie_Tabla"]);
						$loTabla->fpSetBotones($_POST["laBotones"]);
						$loTabla->fpPintar($laVariables);
						$loTabla->fpPaginar($loRol->fiNumero_Resultados($_POST["laFormulario"]), $_POST["laFormulario"]['txtFiltro_Pagina']);
						
						$laRespuesta["lbEstado"]	= true;
						$laRespuesta["laDatos"]		= $loTabla->faGetVariables();
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, no se encontró resultados";
					}
					break;
			
				case "listar_servicios":
					require_once("../modelos/clsTabla.php");
					$loTabla = new clsTabla();
					if($loTabla->fpPintar_Modulos_Servicios($_POST["laFormulario"]['txtCodigo']) === true)
					{
						$laRespuesta = $loTabla->faGetVariables();
						$laRespuesta["lbEstado"]= true;
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, no se encontró resultados";
					}
					break;
				
				case "autocompletado":
					if($loRol->fbListar_Autocompletado($_POST["txtBuscar"]) === true)
					{
						$laRespuesta= $loRol->faGetVariables();
					}
					else
					{
					}
					break;
					
				case "listar_roles":
					require_once("../modelos/clsTabla.php");
					$loTabla = new clsTabla();
					if($loTabla->fiPintar_Listado_Roles() === true)
					{
						$laRespuesta["laDatos"]	= $loTabla->faGetVariables();
						$laRespuesta["lbEstado"]= true;
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, no se encontró resultados";
					}
					break;
			
			}
		}
		else
		{
			$laRespuesta['lbEstado']	= false;
			$laRespuesta['lsMensaje']	= "Disculpe, los datos necesarios para llevar a cabo la operación no se encontraron";
		}
		$loRol->fpDesconectar();
		print json_encode($laRespuesta);
	}
?>