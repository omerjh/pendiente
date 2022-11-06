<?php
	/*
	*	corCombo.php
	*	
	*	Copyright 2021 Hernández^3
	*	
	*	Este programa es software libre, puede redistribuirlo y / o modificar
	*	Bajo los términos de la GNU Licensia Pública General(GPL) publicada por
	*	La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
	*	
	*	Este programa se distribuye con la esperanza de que sea útil,
	*	Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
	*	COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
	*/
	/*error_reporting(E_ALL);
	ini_set("display_errors", 1); */
	session_start();
	
	if(file_exists("../../modelos/clsCombo.php"))
	{
	   require_once("../../modelos/clsCombo.php");
	}
	else
	{
	   require_once("../modelos/clsCombo.php");
	}
	
	/****************************************************************************************************
		Verificación si se ha enviado algún dato por POST, para que valide si ha iniciado sesion y 
		permita cargar el combo deseado desde este mismo archivo.
	****************************************************************************************************/
	if(array_key_exists('txtOperacion',$_POST) === true)
	{
		require_once("../modelos/clsSeguridad.php");
		$loTabla = new clsSeguridad();
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
			$_SESSION["sogem_lsHacer"]	= "Malo";
			$_SESSION["sogem_lsMensaje"]= "Disculpe, debe iniciar sesión para ingresar al sistema";
			header("location: ../index.php");
		}
		elseif($loTabla->fbPermiso($_SESSION["sogem_u_permisos"], $_POST["txtArchivo"]) === false)
		{
			$_SESSION["sogem_lsHacer"]	= "Malo";
			$_SESSION["sogem_lsMensaje"]	= "Disculpe, no tiene permiso para ver la página anterior";
			header("location: ../vistas/formularios/visInicio.php");
		}
		elseif(array_key_exists('txtOperacion',$_POST) === false or $_POST["txtOperacion"] == NULL)
		{
			$_SESSION["sogem_lsHacer"]	= "Malo";
			$_SESSION["sogem_lsMensaje"]	= "Disculpe, la página solicitada necesita unos datos que no fueron suministrados";
			header("location: ../vistas/formularios/visInicio.php");
		}
		elseif($_POST['txtOperacion'] != 'cargar_combo')
		{
			$_SESSION["sogem_lsHacer"]	= "Malo";
			$_SESSION["sogem_lsMensaje"]	= "Disculpe, la página solicitada necesita unos datos que no fueron suministrados";
			header("location: ../vistas/formularios/visInicio.php");
		}
		else
		{
			$loCombo = new corCombo();
			$loCombo->fpPintar($_POST["txtOpcion"], $_POST["txtSeleccion"], $_POST["txtDependiente"]);
		}
	}
	
	class corCombo
	{
		public function __construct()
		{
		}
		
		public function __destruct()
		{
		}
		
		public function fpPintar($psOpcion, $piCod=0, $paDependiente = '')
		{
			$lobjCombo=new clsCombo();
			switch($psOpcion)
			{
				case "modulo": // Lista todos los modulos activos del sistema
					$lobjCombo->fpSetSql("SELECT mod_codigo, mod_nombre FROM modulo WHERE mod_estatus = 'A' ORDER BY mod_nombre");
					$lobjCombo->fpSetCampo1("mod_codigo");
					$lobjCombo->fpSetCampo2("mod_nombre");
					break;
				
				case "rol": // Lista todos los roles activos del sistema
					// rol_codigo > '0' AND 
					$lobjCombo->fpSetSql("SELECT rol_codigo, rol_nombre FROM rol WHERE rol_estatus = 'A' ORDER BY rol_nombre");
					$lobjCombo->fpSetCampo1("rol_codigo");
					$lobjCombo->fpSetCampo2("rol_nombre");
					break;
					
				case "tipo_producto": // Lista todos los tipos de productos activos del sistema
					$lobjCombo->fpSetSql("SELECT tpr_codigo, tpr_nombre FROM tipo_producto WHERE tpr_estatus = 'A' ORDER BY tpr_nombre");
					$lobjCombo->fpSetCampo1("tpr_codigo");
					$lobjCombo->fpSetCampo2("tpr_nombre");
					break;
					
				case "categoria": // Lista todas las categorías activas del sistema
					$lobjCombo->fpSetSql("SELECT cat_codigo, cat_nombre FROM categoria WHERE cat_estatus = 'A' ORDER BY cat_nombre");
					$lobjCombo->fpSetCampo1("cat_codigo");
					$lobjCombo->fpSetCampo2("cat_nombre");
					break;
					
				case "marca": // Lista todas las marcas activas del sistema
					$lobjCombo->fpSetSql("SELECT mar_codigo, mar_nombre FROM marca WHERE mar_estatus = 'A' ORDER BY mar_nombre");
					$lobjCombo->fpSetCampo1("mar_codigo");
					$lobjCombo->fpSetCampo2("mar_nombre");
					break;
					
				case "unidad_base": // Lista todas las unidades principales activas del sistema
					$lobjCombo->fpSetSql("SELECT uni_codigo, uni_nombre FROM unidad WHERE uni_estatus = 'A' AND uni_tipo = '1' ORDER BY uni_nombre");
					$lobjCombo->fpSetCampo1("uni_codigo");
					$lobjCombo->fpSetCampo2("uni_nombre");
					break;
					
				case "unidad_secundaria": // Lista todas las unidades principales activas del sistema
					$laData[1]['lsNombre']	= 'factor';
					$laData[1]['lsCampo']	= 'uni_factor';
					$lobjCombo->fpSetSql("SELECT uni_codigo, (uni_abreviatura ||' - '|| uni_nombre) AS uni_nombre, uni_factor FROM unidad WHERE uni_estatus = 'A' AND uni_tipo = '2' ORDER BY uni_nombre");
					$lobjCombo->fpSetCampo1("uni_codigo");
					$lobjCombo->fpSetCampo2("uni_nombre");
					$lobjCombo->fpSetData($laData);
					break;

				case "banco": // Lista todos los bancos activos del sistema
					$lobjCombo->fpSetSql("SELECT ban_codigo, ban_nombre FROM banco WHERE ban_estatus = 'A' ORDER BY ban_nombre");
					$lobjCombo->fpSetCampo1("ban_codigo");
					$lobjCombo->fpSetCampo2("ban_nombre");
					break;

				case "moneda": // Lista todos las monedas activas del sistema
					$lobjCombo->fpSetSql("SELECT mon_codigo, mon_nombre FROM moneda WHERE mon_estatus = 'A' ORDER BY mon_nombre");
					$lobjCombo->fpSetCampo1("mon_codigo");
					$lobjCombo->fpSetCampo2("mon_nombre");
					break;
					
				case "empresa": // Lista todos las monedas activas del sistema
					$lobjCombo->fpSetSql("SELECT emp_codigo, emp_nombre_comercial FROM empresa WHERE emp_estatus = 'A' ORDER BY emp_nombre_comercial");
					$lobjCombo->fpSetCampo1("emp_codigo");
					$lobjCombo->fpSetCampo2("emp_nombre_comercial");
					break;
					
				case "almacen": // Lista todos los almacenes activos del sistema
					$lobjCombo->fpSetSql("SELECT alm_codigo, alm_nombre FROM almacen WHERE alm_estatus = 'A' ORDER BY alm_nombre");
					$lobjCombo->fpSetCampo1("alm_codigo");
					$lobjCombo->fpSetCampo2("alm_nombre");
					break;
				
				//Si solo manejaran 1 solo almacen entonces hay que permitir que puedan seleccionar el mismo y este combo no se usa por ahora
				// case "almacen_destino": // Lista todos los almacenes activos del sistema y que no tenga el almacen que fue pasado por parametros
					// $lobjCombo->fpSetSql("SELECT alm_codigo, alm_nombre FROM almacen WHERE alm_estatus = 'A' AND alm_codigo != '".intval($paDependiente[0])."' ORDER BY alm_nombre");
					// $lobjCombo->fpSetCampo1("alm_codigo");
					// $lobjCombo->fpSetCampo2("alm_nombre");
					// break;
					
				case "sistema": // Lista todos los sistemas activos del sistema
					$lobjCombo->fpSetSql("SELECT sis_codigo, sis_nombre FROM sistema WHERE sis_estatus = 'A' ORDER BY sis_nombre");
					$lobjCombo->fpSetCampo1("sis_codigo");
					$lobjCombo->fpSetCampo2("sis_nombre");
					break;
					
				case "documento": // Lista todos los documentos activos del sistema
					$lobjCombo->fpSetSql("SELECT doc_codigo, doc_nombre FROM documento WHERE doc_estatus = 'A' AND doc_cod_sistema = '".intval($paDependiente[0])."' ORDER BY doc_nombre");
					$lobjCombo->fpSetCampo1("doc_codigo");
					$lobjCombo->fpSetCampo2("doc_nombre");
					break;
					
				case "talonario": // Lista todos los talonarios activos del sistema
					$lobjCombo->fpSetSql("SELECT tal_codigo, tal_serie FROM talonario WHERE tal_estatus = 'A' AND tal_cod_sistema = '".intval($paDependiente[0])."' AND tal_cod_documento = '".intval($paDependiente[1])."' ORDER BY tal_serie");
					$lobjCombo->fpSetCampo1("tal_codigo");
					$lobjCombo->fpSetCampo2("tal_serie");
					break;
					
				case "punto_venta": // Lista todos los puntos de ventas activos del sistema
					$lobjCombo->fpSetSql("SELECT pve_codigo, pve_nombre FROM punto_venta WHERE pve_estatus = 'A' ORDER BY pve_nombre");
					$lobjCombo->fpSetCampo1("pve_codigo");
					$lobjCombo->fpSetCampo2("pve_nombre");
					break;
					
				case "punto_venta_usuario": // Lista todos los puntos de ventas asignados a un usuario
					$lobjCombo->fpSetSql("SELECT pve_codigo, pve_nombre FROM usuario_punto_venta JOIN punto_venta ON pve_codigo = upv_cod_puntoventa WHERE pve_estatus = 'A' AND upv_estatus = 'A' AND upv_cod_usuario = '".intval($paDependiente[0])."' ORDER BY pve_nombre");
					$lobjCombo->fpSetCampo1("pve_codigo");
					$lobjCombo->fpSetCampo2("pve_nombre");
					break;
					
				case "documento_punto_venta": // Lista todos los documentos asignados a un punto de venta y sistema 
					$laData[1]['lsNombre']	= 'defecto';
					$laData[1]['lsCampo']	= 'doc_defecto';
					$lobjCombo->fpSetSql("
						SELECT doc_codigo, doc_nombre, doc_defecto
						FROM talonario_punto_venta
						JOIN talonario ON tal_codigo = tpv_cod_talonario
						JOIN documento ON doc_codigo = tal_cod_documento
						WHERE tpv_estatus = 'A' AND tal_estatus = 'A' AND doc_estatus = 'A'
						AND tpv_cod_puntoventa = '".intval($paDependiente[0])."'
						AND doc_cod_sistema = '".intval($paDependiente[1])."'
						ORDER BY doc_nombre DESC
					");
					$lobjCombo->fpSetCampo1("doc_codigo");
					$lobjCombo->fpSetCampo2("doc_nombre");
					$lobjCombo->fpSetData($laData);
					break;
					
				case "talonario_documento": // Lista todos los talonarios de un documento asignado a un punto de venta y sistema
					$lobjCombo->fpSetSql("
						SELECT tal_codigo, tal_serie
						FROM talonario_punto_venta
						JOIN talonario ON tal_codigo = tpv_cod_talonario
						WHERE tpv_estatus = 'A' AND tal_estatus = 'A'
						AND tpv_cod_puntoventa = '".intval($paDependiente[0])."'
						AND tal_cod_documento = '".intval($paDependiente[1])."'
						ORDER BY tal_serie DESC
					");
					$lobjCombo->fpSetCampo1("tal_codigo");
					$lobjCombo->fpSetCampo2("tal_serie");
					$lobjCombo->fpSetData($laData);
					break;
					
				case "caja": // Lista todas las cajas activas del sistema
					$lobjCombo->fpSetSql("SELECT caj_codigo, caj_nombre FROM caja WHERE caj_estatus = 'A' ORDER BY caj_nombre");
					$lobjCombo->fpSetCampo1("caj_codigo");
					$lobjCombo->fpSetCampo2("caj_nombre");
					break;
					
			}
			if ($psOpcion!="")
			{
				$lobjCombo->fbGenerar($piCod);
			}
		}
	}
?>
