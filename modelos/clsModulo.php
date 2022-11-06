<?php
	/*
	*	clsModulo.php
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
	class clsModulo extends clsFunciones
	{
		private $aiCodigo;
		private $asNombre;
		private $asIcono;
		private $aiPosicion;
		private $aaVariables;
		private $asArchivo;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->aiCodigo		="";
			$this->asNombre		="";
			$this->asIcono		="";
			$this->aiPosicion	="";
			$this->aaVariables	=array();
			$this->asArchivo	="";
		}
		
		public function __destruct()
		{
		}
		
		/****************************************************************************************************
			Inicio de los metodos SET permite recibir los datos enviados desde el formulario.
		****************************************************************************************************/
		public function fpSetFormulario($paFormulario)
		{
			$lbBueno			= true;
			$this->aiCodigo		= parent::fiVerificar_Numeros_Enteros($paFormulario["txtCodigo"]);
			$this->asNombre		= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre"]);
			$this->asIcono		= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtIcono"]);
			$this->aiPosicion	= parent::fiVerificar_Numeros_Enteros($paFormulario["txtPosicion"]);
			$this->asArchivo	= $paFormulario["txtArchivo"];
			
			// Sección que verifica la operacion a realizar así como también si los datos
			// obligatorios no esten en blanco
			if($paFormulario["txtOperacion"] == "buscar")
			{
				if($this->aiCodigo == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "buscar_nombre" or $paFormulario["txtOperacion"] == "incluir" )
			{
				if($this->asNombre == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "modificar")
			{
				if($this->aiCodigo == "" or $this->asNombre == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "desactivar" or 
					$paFormulario["txtOperacion"] == "activar")
			{
				if($this->aiCodigo == "" or $this->asArchivo == "")
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
			Inicio del metodo buscar(), permite buscar un módulo en la base de datos por medio del codigo
		****************************************************************************************************/
		public function fbBuscar()
		{
			$lbEnc=false;
			$lsSql="SELECT mod_codigo, mod_nombre, mod_icono, mod_posicion, mod_estatus
					FROM modulo 
					WHERE mod_codigo = '$this->aiCodigo'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]		=$laArreglo["mod_codigo"];
				$this->aaVariables["txtNombre"]		=$laArreglo["mod_nombre"];
				$this->aaVariables["txtIcono"]		=$laArreglo["mod_icono"];
				$this->aaVariables["txtPosicion"]	=$laArreglo["mod_posicion"];
				$this->aaVariables["cmbEstatus"]	=$laArreglo["mod_estatus"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo buscar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Nombre(), permite buscar un módulo en la base de datos por medio 
			del nombre
		****************************************************************************************************/
		public function fbBuscar_Nombre($pbCodigo = false)
		{
			if($pbCodigo === true)
			{
				$lsCripterio = "AND mod_codigo != '$this->aiCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT mod_codigo, mod_nombre, mod_icono, mod_posicion, mod_estatus
					FROM modulo 
					WHERE UPPER(mod_nombre) = UPPER('$this->asNombre')
					$lsCripterio";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]		=$laArreglo["mod_codigo"];
				$this->aaVariables["txtNombre"]		=$laArreglo["mod_nombre"];
				$this->aaVariables["txtIcono"]		=$laArreglo["mod_icono"];
				$this->aaVariables["txtPosicion"]	=$laArreglo["mod_posicion"];
				$this->aaVariables["cmbEstatus"]	=$laArreglo["mod_estatus"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Nombre()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbIncluir(), permite insertar un módulo en la base de datos
		****************************************************************************************************/
		public function fbIncluir()
		{
			$lbHecho=false;
			$lsSql="INSERT INTO modulo (mod_nombre,mod_icono,mod_posicion) VALUES 
					(UPPER('$this->asNombre'),LOWER('$this->asIcono'), '$this->aiPosicion')";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{	
				$lsMensaje = "Insertó el Módulo ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"INSERTAR",$lsMensaje);
			}
			return $lbHecho;
		
		}
		/****************************************************************************************************
			Fin del metodo fbIncluir()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbModificar(), permite modificar un módulo en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbModificar()
		{
			$lbHecho=false;
			$lsSql="UPDATE modulo 
					SET mod_nombre	=UPPER('$this->asNombre'),
					mod_icono		=LOWER('$this->asIcono'),
					mod_posicion	='$this->aiPosicion'
					WHERE mod_codigo = '$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Modificó el Módulo ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbDesactivar(), permite inactivar un módulo en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbDesactivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE modulo 
					SET mod_estatus = 'I'
					WHERE mod_codigo  ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Desactivó el código del Módulo ".$this->aiCodigo;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbDesactivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbActivar(), permite activar un módulo en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbActivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE modulo 
					SET mod_estatus = 'A'
					WHERE mod_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Activó el código del Módulo ".$this->aiCodigo;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbActivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar(), permite listar los módulos registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE mod_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " mod_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " mod_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}

			$lsSql="SELECT mod_codigo, mod_nombre, mod_icono, mod_posicion,
					CASE mod_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS mod_estatus
					
					FROM modulo 
					$lsCripterio
					ORDER BY mod_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI][0]=$laArreglo["mod_codigo"];
					$this->aaVariables[$liI][1]=mb_convert_case($laArreglo["mod_nombre"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI][2]=$laArreglo["mod_icono"];
					$this->aaVariables[$liI][3]='<span class="'.$laArreglo["mod_icono"].'"></span>';
					$this->aaVariables[$liI][4]=$laArreglo["mod_posicion"];
					$this->aaVariables[$liI][5]=$laArreglo["mod_estatus"];
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbListar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiNumero_Resultados(), permite listar los módulos registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fiNumero_Resultados($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE mod_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " mod_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " mod_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			$lsSql="SELECT COUNT(mod_codigo) AS total
					FROM modulo 
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
			Fin del metodo fiNumero_Resultados()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar_Autocompletado(), permite listar el código y nombre de los módulos
			registrados en la base de datos por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Autocompletado($psBusqueda)
		{
			$lbHecho = false;
			$lsSql="SELECT mod_codigo, mod_nombre
					FROM modulo
					WHERE mod_estatus = 'A' 
					AND (UPPER(mod_nombre) LIKE UPPER('%".$psBusqueda."%')
					OR mod_codigo = '".intval($psBusqueda)."')
					ORDER BY mod_codigo";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					// si no imprime el autocomplete dejar el utf8_encode
					$this->aaVariables[$liI]['codigo']	=mb_convert_case($laArreglo["mod_codigo"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI]['label']	=mb_convert_case($laArreglo["mod_nombre"], MB_CASE_TITLE, "UTF-8");
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
			Inicio del metodo fbListar_Excel(), permite listar los módulos registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Excel($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Nombre'] != "")
			{//Busca por coincidencia
				$lsCripterio .= "WHERE mod_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " mod_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = $paFormulario['txtFiltro_Pagina'] - 1;
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}

			$lsSql="SELECT mod_codigo, mod_nombre, mod_icono, mod_posicion,
					CASE mod_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS mod_estatus
					
					FROM modulo 
					$lsCripterio
					ORDER BY mod_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI]['liCodigo']	=$laArreglo["mod_codigo"];
					$this->aaVariables[$liI]['lsNombre']	=$laArreglo["mod_nombre"];
					$this->aaVariables[$liI]['lsIcono']		=$laArreglo["mod_icono"];
					$this->aaVariables[$liI]['liPosicion']	=$laArreglo["mod_posicion"];
					$this->aaVariables[$liI]['lsEstatus']	=$laArreglo["mod_estatus"];
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbListar()
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
	}			
?>
