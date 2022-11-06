<?php
	/*
	*	clsServicio.php
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
	class clsServicio extends clsFunciones
	{
		private $aiCodigo;
		private $asNombre;
		private $asNombre_Largo;
		private $asUrl;
		private $asAbrir_Especial;
		private $asIcono;
		private $aiPosicion;
		private $aiModulo;
		private $aaVariables;
		private $asArchivo;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->aiCodigo			="";
			$this->asNombre			="";
			$this->asNombre_Largo	="";
			$this->asUrl			="";
			$this->asAbrir_Especial	="";
			$this->asIcono			="";
			$this->aiPosicion		="";
			$this->aiModulo			="";
			$this->aaVariables		=array();
			$this->asArchivo		="";
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
			$this->asNombre_Largo	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre_Largo"]);
			$this->asUrl			= parent::fsVerificar_Enlace($paFormulario["txtUrl"]);
			$this->asAbrir_Especial	= parent::fsVerificar_Opciones($paFormulario["radAbrir_Especial"], 'SN');
			$this->asIcono			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtIcono"]);
			$this->aiPosicion		= parent::fiVerificar_Numeros_Enteros($paFormulario["txtPosicion"]);
			$this->aiModulo			= parent::fiVerificar_Numeros_Enteros($paFormulario["cmbModulo"]);
			$this->asArchivo		= $paFormulario["txtArchivo"];
			
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
				//print "$this->asNombre == '' or $this->asUrl == '' or $this->aiModulo == '' or $this->asAbrir_Especial == '' or $this->asArchivo == ''";
				if($this->asNombre == "" or $this->asUrl == "" or $this->aiModulo == "" or $this->asAbrir_Especial == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "modificar")
			{
				if($this->aiCodigo == "" or $this->asNombre == "" or $this->asUrl == "" or $this->aiModulo == "" or $this->asAbrir_Especial == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif( $paFormulario["txtOperacion"] == "desactivar" or 
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
			Inicio del metodo buscar(), permite buscar un servicio en la base de datos por medio del codigo
		****************************************************************************************************/
		public function fbBuscar()
		{
			$lbEnc=false;
			$lsSql="SELECT ser_codigo, ser_nombre, ser_nombre_largo, ser_url,
					ser_abrir_especial, ser_icono, ser_posicion, ser_cod_modulo,
					ser_estatus
					FROM servicio 
					WHERE ser_codigo = '$this->aiCodigo'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]			=$laArreglo["ser_codigo"];
				$this->aaVariables["txtNombre"]			=$laArreglo["ser_nombre"];
				$this->aaVariables["txtNombre_Largo"]	=$laArreglo["ser_nombre_largo"];
				$this->aaVariables["txtUrl"]			=$laArreglo["ser_url"];
				$this->aaVariables["cmbModulo"]			=$laArreglo["ser_cod_modulo"];
				$this->aaVariables["radAbrir_Especial"]	=$laArreglo["ser_abrir_especial"];
				$this->aaVariables["txtIcono"]			=$laArreglo["ser_icono"];
				$this->aaVariables["txtPosicion"]		=$laArreglo["ser_posicion"];
				$this->aaVariables["cmbEstatus"]		=$laArreglo["ser_estatus"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo buscar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Nombre(), permite buscar un servicio en la base de datos por medio 
			del nombre
		****************************************************************************************************/
		public function fbBuscar_Nombre($pbCodigo = false)
		{
			if($pbCodigo === true)
			{
				$lsCripterio = "AND ser_codigo != '$this->aiCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT ser_codigo, ser_nombre, ser_nombre_largo, ser_url,
					ser_abrir_especial, ser_icono, ser_posicion, ser_cod_modulo,
					ser_estatus
					FROM servicio 
					WHERE UPPER(ser_nombre) = UPPER('$this->asNombre') 
					AND UPPER(ser_url) = UPPER('$this->asUrl')
					$lsCripterio";
			// print "<pre>";
			// print_r($lsSql);
			// print "</pre>";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]			=$laArreglo["ser_codigo"];
				$this->aaVariables["txtNombre"]			=$laArreglo["ser_nombre"];
				$this->aaVariables["txtNombre_Largo"]	=$laArreglo["ser_nombre_largo"];
				$this->aaVariables["txtUrl"]			=$laArreglo["ser_url"];
				$this->aaVariables["cmbModulo"]			=$laArreglo["ser_cod_modulo"];
				$this->aaVariables["radAbrir_Especial"]	=$laArreglo["ser_abrir_especial"];
				$this->aaVariables["txtIcono"]			=$laArreglo["ser_icono"];
				$this->aaVariables["txtPosicion"]		=$laArreglo["ser_posicion"];
				$this->aaVariables["cmbEstatus"]		=$laArreglo["ser_estatus"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Nombre()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbIncluir(), permite insertar un servicio en la base de datos
		****************************************************************************************************/
		public function fbIncluir()
		{
			$lbHecho=false;
			$lsSql="INSERT INTO servicio (ser_nombre, ser_nombre_largo, ser_url, 
					ser_abrir_especial, ser_icono, ser_posicion,
					ser_cod_modulo) VALUES (
					UPPER('$this->asNombre'), UPPER('$this->asNombre_Largo'), '$this->asUrl', 
					'$this->asAbrir_Especial', LOWER('$this->asIcono'), $this->aiPosicion, 
					'$this->aiModulo')";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Insertó el Servicio ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"INSERTAR",$lsMensaje);
			}
			return $lbHecho;
		
		}
		/****************************************************************************************************
			Fin del metodo fbIncluir()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbModificar(), permite modificar un servicio en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbModificar()
		{
			$lbHecho=false;
			$lsSql="UPDATE servicio SET
					ser_nombre			= UPPER('$this->asNombre'),
					ser_nombre_largo	= UPPER('$this->asNombre_Largo'),
					ser_url				= '$this->asUrl',
					ser_abrir_especial	= '$this->asAbrir_Especial',
					ser_icono			= LOWER('$this->asIcono'),
					ser_posicion		= '$this->aiPosicion',
					ser_cod_modulo		= '$this->aiModulo'
					WHERE ser_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Modificó el Servicio ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbDesactivar(), permite inactivar un servicio en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbDesactivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE servicio 
					SET ser_estatus = 'I'
					WHERE ser_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Desactivó el código del Servicio ".$this->aiCodigo;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbDesactivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbActivar(), permite activar un servicio en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbActivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE servicio 
					SET ser_estatus = 'A'
					WHERE ser_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Activó el código del Servicio ".$this->aiCodigo;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbActivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo faListar(), permite listar los servicios registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por coincidencia
				$lsCripterio .= "WHERE ser_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Modulo'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_cod_modulo = '".$paFormulario['cmbFiltro_Modulo']."'";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}
			
			$lsSql="SELECT ser_codigo, ser_nombre, ser_url, ser_icono,
					ser_posicion, mod_nombre,
					CASE ser_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS ser_estatus
					FROM servicio 
					JOIN modulo ON mod_codigo = ser_cod_modulo
					$lsCripterio
					ORDER BY ser_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI][0]=$laArreglo["ser_codigo"];
					$this->aaVariables[$liI][1]=mb_convert_case($laArreglo["ser_nombre"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI][2]=mb_convert_case($laArreglo["mod_nombre"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI][3]=$laArreglo["ser_url"];
					$this->aaVariables[$liI][4]='<span class="'.$laArreglo["ser_icono"].'"></span>';
					$this->aaVariables[$liI][5]=$laArreglo["ser_posicion"];
					$this->aaVariables[$liI][6]=$laArreglo["ser_estatus"];
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
			Inicio del metodo fiNumero_Resultados(), permite listar los módulos registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fiNumero_Resultados($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Modulo'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_cod_modulo = '".$paFormulario['cmbFiltro_Modulo']."'";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			$lsSql="SELECT COUNT(ser_codigo) AS total
					FROM servicio 
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
			$lsSql="SELECT ser_codigo, ser_nombre
					FROM servicio
					WHERE ser_estatus = 'A' 
					AND (UPPER(ser_nombre) LIKE UPPER('%".$psBusqueda."%')
					OR ser_codigo = '".intval($psBusqueda)."')
					ORDER BY ser_codigo";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					// si no imprime el autocomplete dejar el utf8_encode
					//$this->aaVariables[$liI]['codigo']	=mb_convert_case(utf8_encode($laArreglo["mod_codigo"]), MB_CASE_TITLE, "UTF-8");
					//$this->aaVariables[$liI]['label']	=mb_convert_case(utf8_encode($laArreglo["mod_nombre"]), MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI]['codigo']	=mb_convert_case($laArreglo["ser_codigo"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI]['label']	=mb_convert_case($laArreglo["ser_nombre"], MB_CASE_TITLE, "UTF-8");
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
			Inicio del metodo fbListar_Excel(), permite listar los servicios registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Excel($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por coincidencia
				$lsCripterio .= "WHERE ser_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Modulo'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_cod_modulo = '".$paFormulario['cmbFiltro_Modulo']."'";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " ser_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}
			
			$lsSql="SELECT ser_codigo, ser_nombre, ser_nombre_largo, ser_url, ser_icono,
					ser_posicion, mod_nombre,
					CASE ser_abrir_especial
						WHEN 'S' THEN 'Si'
						WHEN 'N' THEN 'No'
					END AS ser_abrir_especial,
					CASE ser_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS ser_estatus
					FROM servicio 
					JOIN modulo ON mod_codigo = ser_cod_modulo
					$lsCripterio
					ORDER BY ser_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{	
					$this->aaVariables[$liI]['liCodigo']		=$laArreglo["ser_codigo"];
					$this->aaVariables[$liI]['lsNombre']		=$laArreglo["ser_nombre"];
					$this->aaVariables[$liI]['lsNombre_Largo']	=$laArreglo["ser_nombre_largo"];
					$this->aaVariables[$liI]['lsUrl']			=$laArreglo["ser_url"];
					$this->aaVariables[$liI]['lsAbrir_Especial']=$laArreglo["ser_abrir_especial"];
					$this->aaVariables[$liI]['lsIcono']			=$laArreglo["ser_icono"];
					$this->aaVariables[$liI]['liPosicion']		=$laArreglo["ser_posicion"];
					$this->aaVariables[$liI]['lsModulo']		=$laArreglo["mod_nombre"];
					$this->aaVariables[$liI]['lsEstatus']		=$laArreglo["ser_estatus"];
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
	}
?>
