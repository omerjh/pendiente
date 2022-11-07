<?php
	/*
	*	clsPendiente.php
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
	class clsPendiente extends clsFunciones
	{
		private $aiCodigo;
		private $asNombre;
		private $afCantidad;
		private $aiUnidad;
		private $asObservacion;
		private $asFecha_Estimada;
		private $asResponsable;
		private $asCondicion;
		private $asFecha_Ejecucion;
		private $afCosto;
		private $aaVariables;
		private $asArchivo;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->aiCodigo			= '';
			$this->asNombre			= '';
			$this->afCantidad		= '';
			$this->aiUnidad			= '';
			$this->asObservacion	= '';
			$this->asFecha_Estimada	= '';
			$this->asResponsable	= '';
			$this->asCondicion		= '';
			$this->asFecha_Ejecucion= '';
			$this->afCosto			= '';
			$this->aaVariables		= array();
			$this->asArchivo		= '';
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
			$this->afCantidad		= parent::fiVerificar_Numeros_Punto($paFormulario["txtCantidad"]);
			$this->aiUnidad			= parent::fiVerificar_Numeros_Enteros($paFormulario["cmbUnidad"]);
			$this->asObservacion	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtObservacion"]);
			$this->asFecha_Estimada	= parent::fsFecha_B(parent::fsVerificar_Fecha($paFormulario["txtFecha_Estimada"]),'2000-01-01');
			$this->asResponsable	= parent::fsVerificar_Opciones($paFormulario["cmbResponsable"], 'VOA');
			$this->asCondicion		= parent::fsVerificar_Opciones($paFormulario["cmbCondicion"], 'PRCI');
			$this->asFecha_Ejecucion= parent::fsFecha_B(parent::fsVerificar_Fecha($paFormulario["txtFecha_Ejecucion"]),'2000-01-01');
			$this->afCosto			= parent::fiVerificar_Numeros_Punto($paFormulario["txtCosto"]);
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
			elseif(	$paFormulario["txtOperacion"] == "finalizar")
			{
				if($this->aiCodigo == "" or $this->asFecha_Ejecucion == "" or $this->afCosto == "" or $this->asArchivo == "")
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
			Inicio del metodo buscar(), permite buscar una pendiente en la base de datos por medio del codigo
		****************************************************************************************************/
		public function fbBuscar()
		{
			$lbEnc=false;
			$lsSql="SELECT	pen_codigo, pen_nombre, pen_cantidad, pen_observacion, pen_persona, 
							pen_codigo_unidad, pen_condicion, pen_costo, pen_estado,
							DATE_FORMAT(pen_fecha_estimada,'%d/%m/%Y') AS pen_fecha_estimada,
							DATE_FORMAT(pen_fecha_ejecucion,'%d/%m/%Y') AS pen_fecha_ejecucion,
							DATE_FORMAT(pen_fecha_creacion,'%d/%m/%Y %h:%i %p') AS pen_fecha_creacion
					FROM pendiente 
					WHERE pen_codigo = '$this->aiCodigo'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]			=$laArreglo["pen_codigo"];
				$this->aaVariables["txtNombre"]			=$laArreglo["pen_nombre"];
				$this->aaVariables["txtCantidad"]		=$laArreglo["pen_cantidad"];
				$this->aaVariables["cmbUnidad"]			=$laArreglo["pen_codigo_unidad"];
				$this->aaVariables["txtObservacion"]	=$laArreglo["pen_observacion"];
				$this->aaVariables["txtFecha_Estimada"]	=$laArreglo["pen_fecha_estimada"];
				$this->aaVariables["cmbResponsable"]	=$laArreglo["pen_persona"];
				$this->aaVariables["cmbCondicion"]		=$laArreglo["pen_condicion"];
				$this->aaVariables["txtFecha_Ejecucion"]=$laArreglo["pen_fecha_ejecucion"];
				$this->aaVariables["txtCosto"]			=$laArreglo["pen_costo"];
				$this->aaVariables["txtFecha_Creacion"]	=$laArreglo["pen_fecha_creacion"];
				$this->aaVariables["cmbEstatus"]		=$laArreglo["pen_estado"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo buscar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Nombre(), permite buscar una pendiente en la base de datos por medio 
			del nombre
		****************************************************************************************************/
		public function fbBuscar_Nombre($pbCodigo = false)
		{
			if($pbCodigo === true)
			{
				$lsCripterio = "AND pen_codigo != '$this->aiCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT pen_codigo, pen_nombre, pen_estado
					FROM pendiente 
					WHERE UPPER(pen_nombre) = UPPER('$this->asNombre')
					AND pen_fecha_estimada = '$this->asFecha_Estimada'
					AND pen_estado = 'A'
					$lsCripterio";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]	=$laArreglo["pen_codigo"];
				$this->aaVariables["txtNombre"]	=$laArreglo["pen_nombre"];
				$this->aaVariables["cmbEstatus"]=$laArreglo["pen_estado"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Nombre()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbIncluir(), permite insertar una pendiente en la base de datos
		****************************************************************************************************/
		public function fbIncluir()
		{
			$lbHecho=false;
			$lsSql="INSERT INTO pendiente (pen_fecha_creacion, pen_nombre, pen_cantidad, pen_observacion, 
					pen_persona, pen_fecha_estimada, pen_codigo_unidad, pen_condicion
					) VALUES (
					NOW(), UPPER('$this->asNombre'), '$this->afCantidad', UPPER('$this->asObservacion'),
					'$this->asResponsable', '$this->asFecha_Estimada', '$this->aiUnidad', '$this->asCondicion'
					)";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{	
				$lsMensaje = "Insertó la pendiente ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"INSERTAR",$lsMensaje);
			}
			return $lbHecho;
		
		}
		/****************************************************************************************************
			Fin del metodo fbIncluir()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbModificar(), permite modificar una pendiente en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbModificar()
		{
			$lbHecho=false;
			$lsSql="UPDATE pendiente SET 
					pen_nombre			= UPPER('$this->asNombre'),
					pen_cantidad		= '$this->afCantidad',
					pen_observacion		= UPPER('$this->asObservacion'),
					pen_persona			= '$this->asResponsable',
					pen_fecha_estimada	= '$this->asFecha_Estimada',
					pen_codigo_unidad	= '$this->aiUnidad',
					pen_condicion		= '$this->asCondicion'
					WHERE pen_codigo = '$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Modificó la pendiente ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbFinalizar(), permite modificar un pendiente en la base de datos por medio de 
			su codigo y darlo como finalizado
		****************************************************************************************************/
		public function fbFinalizar()
		{
			$lbHecho=false;
			$lsSql="UPDATE pendiente SET  
					pen_costo			= '$this->afCosto',
					pen_fecha_ejecucion	= '$this->asFecha_Ejecucion',
					pen_condicion		= 'C'
					WHERE pen_codigo = '$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Modificó la pendiente ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbDesactivar(), permite inactivar una pendiente en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbDesactivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE pendiente 
					SET pen_estado = 'I'
					WHERE pen_codigo  ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Desactivó el código de la pendiente ".$this->aiCodigo;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbDesactivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbActivar(), permite activar una pendiente en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbActivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE pendiente 
					SET pen_estado = 'A'
					WHERE pen_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$lsMensaje = "Activó el código de la pendiente ".$this->aiCodigo;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$lsMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbActivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar(), permite listar las pendientes registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE pen_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " pen_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " pen_estado = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}

			$lsSql="SELECT	pen_codigo, pen_nombre,
							DATE_FORMAT(pen_fecha_estimada,'%d/%m/%Y') AS pen_fecha_estimada,
							CASE pen_persona
								WHEN 'V' THEN 'Vanessa'
								WHEN 'O' THEN 'Omer'
								WHEN 'A' THEN 'Ambos'
							END AS pen_persona,
							
							CASE pen_condicion
								WHEN 'P' THEN 'Pendiente'
								WHEN 'R' THEN 'Proceso'
								WHEN 'C' THEN 'Completado'
								WHEN 'I' THEN 'Incompleto'
							END AS pen_condicion,
							
							CASE pen_estado
								WHEN 'A' THEN 'Activo'
								WHEN 'I' THEN 'Inactivo'
							END AS pen_estado
					FROM pendiente 
					$lsCripterio
					ORDER BY pen_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI][0]=$laArreglo["pen_codigo"];
					$this->aaVariables[$liI][1]=$laArreglo["pen_fecha_estimada"];
					$this->aaVariables[$liI][2]=mb_convert_case($laArreglo["pen_nombre"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI][3]=$laArreglo["pen_persona"];
					$this->aaVariables[$liI][4]=$laArreglo["pen_condicion"];
					$this->aaVariables[$liI][5]=$laArreglo["pen_estado"];
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
			Inicio del metodo fiNumero_Resultados(), permite listar las pendientes registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fiNumero_Resultados($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE pen_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " pen_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " pen_estado = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			$lsSql="SELECT COUNT(pen_codigo) AS total
					FROM pendiente 
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
			Inicio del metodo fbListar_Autocompletado(), permite listar el código y nombre de las pendientes
			registrados en la base de datos por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Autocompletado($psBusqueda)
		{
			$lbHecho = false;
			$lsSql="SELECT pen_codigo, pen_nombre
					FROM pendiente
					WHERE pen_estado = 'A' 
					AND (UPPER(pen_nombre) LIKE UPPER('%".$psBusqueda."%')
					OR pen_codigo = '".intval($psBusqueda)."')
					ORDER BY pen_codigo";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					// si no imprime el autocomplete dejar el utf8_encode
					$this->aaVariables[$liI]['codigo']	=mb_convert_case($laArreglo["pen_codigo"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI]['label']	=mb_convert_case($laArreglo["pen_nombre"], MB_CASE_TITLE, "UTF-8");
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
			Inicio del metodo fbListar_Excel(), permite listar las pendientes registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar_Excel($paFormulario='')
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE pen_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " pen_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " pen_estado = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = $paFormulario['txtFiltro_Pagina'] - 1;
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}

			$lsSql="SELECT pen_codigo, pen_nombre,
					CASE pen_estado
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS pen_estado
					
					FROM pendiente 
					$lsCripterio
					ORDER BY pen_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI]['liCodigo']	=$laArreglo["pen_codigo"];
					$this->aaVariables[$liI]['lsNombre']	=$laArreglo["pen_nombre"];
					$this->aaVariables[$liI]['lsEstatus']	=$laArreglo["pen_estado"];
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
