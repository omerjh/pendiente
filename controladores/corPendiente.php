<?php
	/*
	*	corPendiente.php
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
		$_SESSION["sogem_lsHacer"]	= false;
		$_SESSION["sogem_lsMensaje"]= "Disculpe, debe iniciar sesión para ingresar al sistema";
		header("location: ../index.php");
	}
	elseif($loSeguridad->fbPermiso($_SESSION["sogem_u_permisos"], $_POST["txtArchivo"]) === false)
	{
		$_SESSION["sogem_lsHacer"]	= false;
		$_SESSION["sogem_lsMensaje"]	= "Disculpe, no tiene permiso para ver la página anterior";
		header("location: ../vistas/formularios/visInicio.php");
	}
	elseif(array_key_exists('txtOperacion',$_POST) === false or $_POST["txtOperacion"] == NULL)
	{
		$_SESSION["sogem_lsHacer"]	= false;
		$_SESSION["sogem_lsMensaje"]	= "Disculpe, la página solicitada necesita unos datos que no fueron suministrados";
		header("location: ../vistas/formularios/visInicio.php");
	}
	else
	{
		require_once("../modelos/clsPendiente.php");
		$loPendiente				= new clsPendiente();
		if($loPendiente->fpSetFormulario($_POST) === true)
		{
			switch($_POST["txtOperacion"])
			{
				case "buscar":
					if($loPendiente->fbBuscar() === true)
					{
						$laRespuesta['lbEstado']= true;
						$laRespuesta['laDatos']	= $loPendiente->faGetVariables();
					}
					else
					{
						$laRespuesta["lbEstado"]	=false;
						$laRespuesta['lsMensaje']	="Disculpe, el código que intenta buscar no se encuentra registrado";
					}
					break;
					
				case "incluir":
					if($loPendiente->fbBuscar_Nombre() === true)
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, el nombre de la caja que desea guardar se encuentra registrado";
					}
					elseif($loPendiente->fbIncluir() === true)
					{
						$laRespuesta["lbEstado"]	= true;
						$laRespuesta['lsMensaje']	= "Se ha guardado exitosamente";
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
		
				case "modificar":
					if($loPendiente->fbBuscar_Nombre(true) === true)
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, el nombre de la caja que desea guardar se encuentra registrado";
					}
					elseif($loPendiente->fbModificar() === true)
					{
						$laRespuesta['lbEstado']	= true;
						$laRespuesta['lsMensaje']	= "Se ha modificado exitosamente";
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
					
				case "activar":
					if($loPendiente->fbActivar() === true)
					{
						$laRespuesta["lbEstado"]	= true;
						$laRespuesta['lsMensaje']	= "Se ha activado exitosamente";
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
		
				case "desactivar":
					if($loPendiente->fbDesactivar() === true)
					{
						$laRespuesta["lbEstado"]	= true;
						$laRespuesta['lsMensaje']	= "Se ha desactivado exitosamente";
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, ocurrió un error al guardar los datos, por favor intente nuevamente";
					}
					break;
		
				case "listar":
					require_once("../modelos/clsTabla.php");
					if($loPendiente->fbListar($_POST["laFormulario"]) === true)
					{
						$laVariables= $loPendiente->faGetVariables();
						$loTabla	= new clsTabla();
						$loTabla->fpSetPorcentaje($_POST["lsPorcentajes"]);
						$loTabla->fpSetAlineacion($_POST["lsAlineacion"]);
						$loTabla->fpSetTitulos($_POST["lsTitulos"]);
						$loTabla->fpSetFondo_Encabezado($_POST["lsFondo_Encabezado"]);
						$loTabla->fpSetTitles($_POST["lsTitles"]);
						$loTabla->fpSetPie_Tabla($_POST["lbPie_Tabla"]);
						$loTabla->fpSetBotones($_POST["laBotones"]);
						$loTabla->fpPintar($laVariables);
						$loTabla->fpPaginar($loPendiente->fiNumero_Resultados($_POST["laFormulario"]), $_POST["laFormulario"]['txtFiltro_Pagina']);
						
						$laRespuesta["lbEstado"]	= true;
						$laRespuesta["laDatos"]		= $loTabla->faGetVariables();
					}
					else
					{
						$laRespuesta["lbEstado"]	= false;
						$laRespuesta['lsMensaje']	= "Disculpe, no se encontró resultados";
					}
					break;
				
				case "autocompletado":
					if($loPendiente->fbListar_Autocompletado($_POST["txtBuscar"]) === true)
					{
						$laRespuesta= $loPendiente->faGetVariables();
					}
					else
					{
					}
					break;
			
			}
		}
		else
		{
			$laRespuesta["lbEstado"]	=false;
			$laRespuesta['lsMensaje']	="Disculpe, los datos necesarios para llevar a cabo la operación no se encontraron";
		}
		$loPendiente->fpDesconectar();
		print json_encode($laRespuesta);
	}
?>
