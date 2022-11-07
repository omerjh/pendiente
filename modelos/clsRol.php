<?php
	/*
	*	clsRol.php
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
	require_once("clsFunciones.php");
	class clsRol extends clsFunciones
	{
		private $aiCodigo;
		private $asNombre;
		private $aaServicios;
		private $aaVariables;
		private $asArchivo;
		private $asMensaje;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->aiCodigo			="";
			$this->asNombre			="";
			$this->aaServicios		=array();
			$this->aaVariables		=array();
			$this->asArchivo		="";
			$this->asMensaje		="";
		}
		
		public function __destruct()
		{
		}
		
		/****************************************************************************************************
			Inicio de los metodos SET permite recibir los datos enviados desde el formulario.
		****************************************************************************************************/
		public function fpSetFormulario($paFormulario)
		{
			$lbBueno				= true;
			$this->aiCodigo			= parent::fiVerificar_Numeros_Enteros($paFormulario["txtCodigo"]);
			$this->asNombre			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre"]);
			$this->asArchivo		= $paFormulario["txtArchivo"];
			
			$liJ = 1;//Contador para el arreglo de los checkbox que esten llenos
			$liK = 1;//Contador para el arreglo de los servicios que se van a desactivar
			for($liI = 1 ; $liI < $paFormulario["txtCantidad_Servicios"] ; $liI++)
			{
				if($paFormulario["chkServicio".$liI] != "" and $paFormulario["txtCod_Servicio_Rol".$liI] != "" and $paFormulario["txtAsignado".$liI] == "N")
				{//Si esta marcado y tiene codigo y no esta asignado (Activar)
					$this->aaServicios[$liJ]["Codigo_Servicio"]	= parent::fiVerificar_Numeros_Enteros($paFormulario["chkServicio".$liI]);
					$this->aaServicios[$liJ]["Nombre_servicio"]	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre_Servicio".$liI]);
					$this->aaServicios[$liJ]["Codigo"]			= parent::fiVerificar_Numeros_Enteros($paFormulario["txtCod_Servicio_Rol".$liI]);
					$liJ++;
				}
				elseif($paFormulario["chkServicio".$liI] == "" and $paFormulario["txtCod_Servicio_Rol".$liI] != "" and $paFormulario["txtAsignado".$liI] == "S")
				{//Si no esta marcado y tiene codigo y estaba asignado (Desactivar)
					$this->aaServicios["Desactivar"][$liK]["Codigo"] = parent::fiVerificar_Numeros_Enteros($paFormulario["txtCod_Servicio_Rol".$liI]);
					$this->aaServicios["Desactivar"][$liK]["Nombre"] = parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre_Servicio".$liI]);
					$liK++;
				}
				elseif($paFormulario["chkServicio".$liI] != "" and $paFormulario["txtCod_Servicio_Rol".$liI] == "")
				{//Si esta marcado y no tiene codigo y no esta asignado (Nuevo)
					$this->aaServicios[$liJ]["Codigo_Servicio"]	= parent::fiVerificar_Numeros_Enteros($paFormulario["chkServicio".$liI]);
					$this->aaServicios[$liJ]["Nombre_servicio"]	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre_Servicio".$liI]);
					$this->aaServicios[$liJ]["Codigo"]			= parent::fiVerificar_Numeros_Enteros($paFormulario["txtCod_Servicio_Rol".$liI]);
					$liJ++;
				}
			}
			/*print "<pre>";
			print_r($this->aaServicios);
			print "</pre>";*/
			// Sección que verifica la operacion a realizar así como también si los datos
			// obligatorios no esten en blanco
			if($paFormulario["txtOperacion"] == "buscar")
			{
				if($this->aiCodigo == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "incluir" )
			{
				$lbBueno2 = true;
				$liI = 1;
				while($this->aaServicios[$liI]["Codigo_Servicio"] != "" and $lbBueno2 === true)
				{
					if($this->aaServicios[$liI]["Nombre_servicio"] == "")
					{
						$lbBueno2 = false;
					}
					$liI++;
				}
				if($this->asNombre == "" or $liI == 1 or $lbBueno2 === false or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "modificar")
			{
				$lbBueno2 = true;
				$liI = 1;
				while($this->aaServicios[$liI]["Codigo_Servicio"] != "" and $lbBueno2 === true)
				{
					if($this->aaServicios[$liI]["Nombre_servicio"] == "")
					{
						$lbBueno2 = false;
					}
					$liI++;
				}
				if($this->aiCodigo == "" or $this->asNombre == "" or $liI == 1 and $lbBueno2 === false  or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "desactivar" or 
					$paFormulario["txtOperacion"] == "activar")
			{
				$lbBueno2 = true;
				$liI = 1;
				while($this->aaServicios[$liI]["Codigo_Servicio"] != "" and $lbBueno2 === true)
				{
					if($this->aaServicios[$liI]["Nombre_servicio"] == "")
					{
						$lbBueno2 = false;
					}
					$liI++;
				}
				if($this->aiCodigo == "" or $liI == 1 and $lbBueno2 === false  or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			return $lbBueno;
		}
		/****************************************************************************************************
			Fin de los metodos SET
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio de los metodos GET
		****************************************************************************************************/
		public function faGetVariables()
		{
			return $this->aaVariables;
		}
		/****************************************************************************************************
			Fin de los metodos GET
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo buscar(), permite buscar un rol y los servicios asociados a el  en la base de
			datos por medio del codigo
		****************************************************************************************************/
		public function fbBuscar()
		{
			$lbEnc=false;
			$lsSql="SELECT rol_codigo, rol_nombre, rol_estatus
					FROM rol 
					WHERE rol_codigo = '$this->aiCodigo'
					";
					// AND rol_codigo != '0'
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]	=$laArreglo["rol_codigo"];
				$this->aaVariables["txtNombre"]	=$laArreglo["rol_nombre"];
				$this->aaVariables["cmbEstatus"]=$laArreglo["rol_estatus"];
				$lbEnc=true;
				
				
			}
			parent::fpCierraFiltro($lrTb);
			
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo buscar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Nombre(), permite buscar un rol y los servicios asociados a el y los 
			servicios asociados a el en la base de datos por medio del nombre
		****************************************************************************************************/

		public function fbBuscar_Nombre($pbCodigo = false)
		{
			if($pbCodigo === true)
			{
				$lsCripterio = "AND rol_codigo != '$this->aiCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT rol_codigo, rol_nombre, rol_estatus
					FROM rol 
					WHERE UPPER(rol_nombre) = UPPER('$this->asNombre')
					$lsCripterio";
					// AND rol_codigo != '0'
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]		=$laArreglo["rol_codigo"];
				$this->aaVariables["txtNombre"]		=$laArreglo["rol_nombre"];
				$this->aaVariables["cmbEstatus"]	=$laArreglo["rol_estatus"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Nombre()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbIncluir(), permite insertar un rol y los servicios asociados a el en la base 
			de datos
		****************************************************************************************************/
		public function fbIncluir()
		{
			$liError	= 0;
			$lbHecho	= false;
			$liCodigo	= 0;
			parent::fpBegin();
			$lsSql="INSERT INTO rol (rol_nombre) VALUES (UPPER('$this->asNombre')) RETURNING rol_codigo";
			$lbHecho=parent::fbEjecutar($lsSql);

			if($lbHecho === true)
			{
				$this->asMensaje = "Insertó el Rol ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
									
				$liCodigo=parent::fiGetUltimo_Codigo();
				
				//print "<pre>";
				//print_r();
				//print "</pre>";
				
				$liJ = 1;
				while($this->aaServicios[$liJ]["Codigo_Servicio"] != "" and $lbHecho === true)
				{
					//Se verifica si el servicio ya fue asignado al rol
					$liCodigo_Servicio_Rol = $this->fbBuscar_Servicio_Rol($liCodigo, $this->aaServicios[$liJ]["Codigo_Servicio"]);
					if($liCodigo_Servicio != 0)
					{//Si tiene un codigo se activa el servicio al rol
						$lsSql="UPDATE servicio_rol
								SET sro_estatus = 'A'
								WHERE sro_codigo = '".$liCodigo_Servicio."'";
						$this->asMensaje = "Activó el servicio ".$this->aaServicios[$liJ]["Nombre_servicio"]." al Rol ".$this->asNombre;
						$lsOperacion = "MODIFICAR";
					}
					else
					{
						$lsSql="INSERT INTO servicio_rol (sro_cod_rol, sro_cod_servicio) VALUES 
								('$liCodigo', '".$this->aaServicios[$liJ]["Codigo_Servicio"]."')";
						$this->asMensaje = "Insertó el servicio ".$this->aaServicios[$liJ]["Nombre_servicio"]." al Rol ".$this->asNombre;						
						$lsOperacion = "INSERTAR";
					}
					$lbHecho=parent::fbEjecutar($lsSql);
					if($lbHecho === true)
					{
						parent::fpRegistro_Evento($this->asArchivo,$lsOperacion,$this->asMensaje);
					}
					$liJ++;
				}
			}
			else
			{
				$liError++;
			}
			
			if($liError > 0)
			{
				$lbHecho = false;
				parent::fpRollback();
			}
			else
			{
				$lbHecho = true;
				parent::fpCommit();
			}
			return $lbHecho;
		
		}
		/****************************************************************************************************
			Fin del metodo fbIncluir()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbModificar(), permite modificar un rol y los servicios asociados a el en la 
			base de datos por medio de su codigo
		****************************************************************************************************/
		public function fbModificar()
		{
			$liError	= 0;
			$lbHecho	=true;
			parent::fpBegin();
			$lsSql="UPDATE rol 
					SET rol_nombre = UPPER('$this->asNombre')
					WHERE rol_codigo ='$this->aiCodigo'";
			parent::fbEjecutar($lsSql);

			if($lbHecho === true)
			{
				$this->asMensaje = "Modificó el Rol ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
				
				$liK = 1;
				while($this->aaServicios["Desactivar"][$liK]["Codigo"])
				{
					$lsSql="UPDATE servicio_rol
							SET sro_estatus = 'I'
							WHERE sro_codigo = '".$this->aaServicios["Desactivar"][$liK]["Codigo"]."'";
					$lbHecho=parent::fbEjecutar($lsSql);
					if($lbHecho === true)
					{
						$this->asMensaje = "Desactivó el servicio ".$this->aaServicios["Desactivar"][$liK]["Nombre"]." asignado al Rol ".$this->asNombre;
						parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
					}
					else
					{
						$liError++;
					}
					$liK++;
				}
				
				$liJ = 1;
				while($this->aaServicios[$liJ]["Codigo_Servicio"] != "" and $lbHecho === true)
				{
					if($this->aaServicios[$liJ]["Codigo"] == "")
					{
						//Se verifica si el servicio ya fue asignado al rol
						$this->aaServicios[$liJ]["Codigo"] = $this->fbBuscar_Servicio_Rol($this->aiCodigo, $this->aaServicios[$liJ]["Codigo_Servicio"]);
					}
					
					if($this->aaServicios[$liJ]["Codigo"] != 0)
					{//Si tiene un codigo se activa el servicio al rol
						$lsSql="UPDATE servicio_rol
								SET sro_estatus = 'A'
								WHERE sro_codigo ='".$this->aaServicios[$liJ]["Codigo"]."'";
						$this->asMensaje = "Activó el servicio ".$this->aaServicios[$liJ]["Nombre_servicio"]." al Rol ".$this->asNombre;
						$lsOperacion = "MODIFICAR";
					}
					else
					{
						$lsSql="INSERT INTO servicio_rol (sro_cod_rol, sro_cod_servicio) VALUES 
						('".$this->aiCodigo."', '".$this->aaServicios[$liJ]["Codigo_Servicio"]."')";
						$this->asMensaje = "Insertó el servicio ".$this->aaServicios[$liJ]["Nombre_servicio"]." al Rol ".$this->asNombre;
						$lsOperacion = "INSERTAR";
					}
					$lbHecho=parent::fbEjecutar($lsSql);
					if($lbHecho === true)
					{	
						parent::fpRegistro_Evento($this->asArchivo,$lsOperacion,$this->asMensaje);
					}
					else
					{
						$liError++;
					}
					$liJ++;
				}
			}
			else
			{
				$liError++;
			}
			
			if($liError > 0)
			{
				$lbHecho = false;
				parent::fpRollback();
			}
			else
			{
				$lbHecho = true;
				parent::fpCommit();
				//parent::fpRollback();
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbDesactivar(), permite inactivar un rol en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbDesactivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE rol 
					SET rol_estatus = 'I'
					WHERE rol_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$this->asMensaje = "Desactivó el Rol ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbDesactivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbActivar(), permite activar un rol en la base de datos por medio de su codigo
		****************************************************************************************************/
		public function fbActivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE rol 
					SET rol_estatus = 'A'
					WHERE rol_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$this->asMensaje = "Activó el Rol ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbActivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar(), permite listar los roles registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE rol_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " rol_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " rol_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}
			
			$lsSql="SELECT rol_codigo, rol_nombre,
					CASE rol_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS rol_estatus
					FROM rol 
					$lsCripterio
					ORDER BY rol_codigo
					$lsLimite
					";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI][0]=$laArreglo["rol_codigo"];
					$this->aaVariables[$liI][1]=mb_convert_case($laArreglo["rol_nombre"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI][2]=$laArreglo["rol_estatus"];
						
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo faListar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiNumero_Resultados(), permite obtener la cantidad de páginas que se van a 
			mostrar a partir de la cantidad de resultados entre la cantidad de registros a mostrar
		****************************************************************************************************/
		public function fiNumero_Resultados($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE rol_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " rol_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " rol_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			$lsSql="SELECT COUNT(rol_codigo) AS total
					FROM rol 
					$lsCripterio";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$liNumero=$laArreglo["total"];
			}
			else
			{
				$liNumero=0;
			}
			parent::fpCierraFiltro($lrTb);
			if($paFormulario['cmbFiltro_Numero_Filas'] > 0)
			{
				$liTotal_Paginas = ceil($liNumero/$paFormulario['cmbFiltro_Numero_Filas']);
			}
			else
			{
				$liTotal_Paginas = 0;
			}
			return $liTotal_Paginas;
		}
		/****************************************************************************************************
			Fin del metodo fbListar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar_Autocompletado(), permite listar el código y nombre de los módulos
			registrados en la base de datos por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Autocompletado($psBusqueda)
		{
			$lbHecho = false;
			$lsSql="SELECT rol_codigo, rol_nombre
					FROM rol
					WHERE rol_estatus = 'A' 
					AND (UPPER(rol_nombre) LIKE UPPER('%".$psBusqueda."%')
					OR rol_codigo = '".intval($psBusqueda)."')
					
					ORDER BY rol_codigo";
					//AND rol_codigo > 0
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI]['codigo']	=mb_convert_case($laArreglo["rol_codigo"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI]['label']	=mb_convert_case($laArreglo["rol_nombre"], MB_CASE_TITLE, "UTF-8");
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbListar_Autocompletado()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar_Excel(), permite listar los roles registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Excel($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE rol_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " rol_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " rol_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}
			
			$lsSql="SELECT rol_codigo, rol_nombre,
					CASE rol_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS rol_estatus
					FROM rol 
					$lsCripterio
					ORDER BY rol_codigo
					$lsLimite
					";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI]['liCodigo']	=$laArreglo["rol_codigo"];
					$this->aaVariables[$liI]['lsNombre']	=$laArreglo["rol_nombre"];
					$this->aaVariables[$liI]['lsEstatus']	=$laArreglo["rol_estatus"];
						
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo faListar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar_Excel(), permite listar los roles registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Roles_Permisos($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			
			$liI=0;
			while($paFormulario['cmbFiltro_Rol'][$liI] != '')
			{
				$lsCodigo .= $lsCodigo != '' ? ','.$paFormulario['cmbFiltro_Rol'][$liI] : $paFormulario['cmbFiltro_Rol'][$liI];
				$liI++;
			}
			
			if($lsCodigo != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE rol_codigo IN (".$lsCodigo.")";
			}
			
			$lsSql="SELECT rol_codigo, rol_nombre,
					CASE rol_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS rol_estatus
					FROM rol 
					$lsCripterio
					ORDER BY rol_nombre
					";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI]['liCodigo']	=$laArreglo["rol_codigo"];
					$this->aaVariables[$liI]['lsNombre']	=$laArreglo["rol_nombre"];
					$this->aaVariables[$liI]['lsEstatus']	=$laArreglo["rol_estatus"];

					$lsSql2="SELECT mod_codigo, mod_nombre, ser_codigo, ser_nombre, 
							ser_url,
							CASE ser_abrir_especial
								WHEN 'S' THEN 'Si'
								WHEN 'N' THEN 'No'
							END AS ser_abrir_especial,
							CASE ser_estatus
								WHEN 'A' THEN 'Activo'
								WHEN 'I' THEN 'Inactivo'
							END AS ser_estatus
							FROM servicio_rol
							JOIN servicio	ON ser_codigo = sro_cod_servicio
							JOIN modulo		ON mod_codigo = ser_cod_modulo
							WHERE sro_cod_rol = '".$laArreglo["rol_codigo"]."'
							AND sro_estatus ='A'
							AND mod_estatus = 'A'
							ORDER BY mod_nombre, ser_nombre ";
					$liM=0;
					$lrTb2=parent::frFiltro($lsSql2);
					while($laArreglo2=parent::faProximo($lrTb2))
					{
						if($this->aaVariables[$liI]["laModulo"][$liM]["liCodigo"] != $laArreglo2["mod_codigo"])
						{
							$liM++;
							$this->aaVariables[$liI]["laModulo"][$liM]["liCodigo"] = $laArreglo2["mod_codigo"];
							$this->aaVariables[$liI]["laModulo"][$liM]["lsNombre"] = $laArreglo2["mod_nombre"];
							
							$liS = 1;
						}
						else
						{
							$liS++;
						}
						
						$this->aaVariables[$liI]["laModulo"][$liM]["laServicio"][$liS]["liCodigo"]			= $laArreglo2["ser_codigo"];
						$this->aaVariables[$liI]["laModulo"][$liM]["laServicio"][$liS]["lsNombre"]			= $laArreglo2["ser_nombre"];
						$this->aaVariables[$liI]["laModulo"][$liM]["laServicio"][$liS]["lsUrl"]				= $laArreglo2["ser_url"];
						$this->aaVariables[$liI]["laModulo"][$liM]["laServicio"][$liS]["lsNueva_Ventana"]	= $laArreglo2["ser_abrir_especial"];
						$this->aaVariables[$liI]["laModulo"][$liM]["laServicio"][$liS]["lsEstatus"]			= $laArreglo2["ser_estatus"];
					}
					parent::fpCierraFiltro($lrTb2);
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo faListar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fpDesconectar(), permite cerrar la conexión con la base de datos
		****************************************************************************************************/
		public function fpDesconectar()
		{
			parent::fpDesconectar();
		}
		/****************************************************************************************************
			Fin del metodo fpDesconectar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiUltimo(), permite buscar el codigo de un rol activo que se insertó en la base 
			de datos, para que pueda ser utilizado al momento de asignar el/los servicios asociados a dicho 
			rol
		****************************************************************************************************/
		private function fiUltimo()
		{
			$liCodigo = 0;
			$lsSql="SELECT rol_codigo FROM rol WHERE UPPER(rol_nombre) = UPPER('$this->asNombre') AND rol_estatus = 'A'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$liCodigo = $laArreglo["rol_codigo"];
			}
			parent::fpCierraFiltro($lrTb);
			return $liCodigo;
		}
		/****************************************************************************************************
			Fin del metodo fiUltimo()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Servicio_Rol(), permite verificar si existe el codigo de un servicio
			asignado a un rol, si se encuentra se devuelve dicho codigo
		****************************************************************************************************/
		public function fbBuscar_Servicio_Rol($piCodigo_Rol, $piCodigo_Servicio)
		{
			$liCodigo = 0;
			$lsSql="SELECT sro_cod_rol
					FROM servicio_rol 
					WHERE sro_cod_rol = '".$piCodigo_Rol."'
					AND sro_cod_servicio = '".$piCodigo_Servicio."'
					";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$liCodigo = $laArreglo["sro_codigo"];
			}
			parent::fpCierraFiltro($lrTb);
			return $liCodigo;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Servicio_Rol()
		****************************************************************************************************/	
	}		
?>
