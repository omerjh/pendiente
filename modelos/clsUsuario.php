<?php
	/*
	*	clsUsuario.php
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
	class clsUsuario extends clsFunciones
	{
		private $aiCodigo;
		private $asNombre;
		private $asNombre_Persona;
		private $asClave;
		private $asPregunta_1;
		private $asPregunta_2;
		private $asRespuesta_1;
		private $asRespuesta_2;
		private $aiRol;
		private $asRol;
		private $aaPunto_Venta;
		private $aaVariables;
		private $asArchivo;
		private $asMensaje;
		
		private $aiCodigo_Vendedor_Anterior;
		private $asRif_Anterior;
		private $asNombre_Persona_Anterior;
		private $asRol_Anterior;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->aiCodigo		="";
			$this->asNombre		="";
			$this->asNombre_Persona	="";
			$this->asClave			="";
			$this->asPregunta_1		="";
			$this->asPregunta_2		="";
			$this->asRespuesta_1	="";
			$this->asRespuesta_2	="";
			$this->aiRol			="";
			$this->asRol			="";
			$this->aaPunto_Venta	=array();
			$this->aaVariables		=array();
			$this->asArchivo		="";
			$this->asMensaje		="";
			$this->asRol_Anterior	="";
		}
		
		public function __destruct()
		{
		}
		
		/****************************************************************************************************
			Inicio de los metodos SET permite recibir los datos enviados desde el formulario.
		****************************************************************************************************/
		public function fpSetFormulario($paFormulario)
		{
			/*print "<pre>";
			print_r($paFormulario);
			print "</pre>";*/
			$lbBueno				= true;
			$this->aiCodigo			= parent::fiVerificar_Numeros_Enteros($paFormulario["txtCodigo"]);
			$this->asNombre			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre"]);
			$this->asNombre_Persona	= parent::fsVerificar_Nombres($paFormulario["txtNombre_Persona"]);
			$this->asClave			= parent::fsVerificar_Clave($paFormulario["txtClave"]);
			$this->asPregunta_1		= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["cmbPregunta1"]);
			$this->asPregunta_2		= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["cmbPregunta2"]);
			$this->asRespuesta_1	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtRespuesta1"]);
			$this->asRespuesta_2	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtRespuesta2"]);
			$this->aiRol			= parent::fiVerificar_Numeros_Enteros($paFormulario["cmbRol"]);
			$this->asRol			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtRol"]);
			$this->asArchivo		= $paFormulario["txtArchivo"];
			$this->asRol_Anterior	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtRol_Anterior"]);
			
			$liJ = 1;//Contador para el arreglo de los checkbox que esten llenos
			$liK = 1;//Contador para el arreglo de los servicios que se van a desactivar
			for($liI = 1 ; $liI <= $paFormulario["txtFila"] ; $liI++)
			{
				if(	$paFormulario["txtPunto_Venta_Codigo_".$liI] != "" and
					$paFormulario["txtPunto_Venta_Nombre_".$liI] != "" and
					$paFormulario["txtDefecto_".$liI] != ""
				){
					$this->aaPunto_Venta[$liJ]["liPunto_Venta"]	= parent::fiVerificar_Numeros_Enteros($paFormulario["txtPunto_Venta_Codigo_".$liI]);
					$this->aaPunto_Venta[$liJ]["lsCodigo_Barras"]	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtPunto_Venta_Nombre_".$liI]);
					$this->aaPunto_Venta[$liJ]["lsDefecto"]		= parent::fsVerificar_Opciones($paFormulario["txtDefecto_".$liI], 'SN');
					$liJ++;
				}
			}
			
			// Sección que verifica la operacion a realizar así como también si los datos
			// obligatorios no esten en blanco
			if($paFormulario["txtOperacion"] == "buscar")
			{
				if($this->aiCodigo == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "buscar_nombre")
			{
				if($this->asNombre == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "incluir" )
			{
				
				//print $this->asNombre.' == "" or '.$this->asNombre_Persona.' == "" or '.$this->asClave.' == "" or '.$this->asPregunta_1.' == "" or '.$this->asPregunta_2.' == "" or '.$this->asRespuesta_1.' == "" or '.$this->asRespuesta_2.' == "" or '.$this->aiRol.' == "" or '.$this->asArchivo.' == ""';
				if($this->asNombre == "" or $this->asNombre_Persona == "" or $this->asClave == "" or $this->asPregunta_1 == "" or $this->asPregunta_2 == "" or $this->asRespuesta_1 == "" or $this->asRespuesta_2 == "" or $this->aiRol == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "desactivar" or 
					$paFormulario["txtOperacion"] == "activar")
			{
				if($this->aiCodigo == "" or $this->asNombre == "" or $this->asNombre_Persona == "" or $this->aiRol == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif(	$paFormulario["txtOperacion"] == "modificar")
			{
				if($this->aiCodigo == "" or $this->asNombre == "" or $this->asNombre_Persona == "" or $this->aiRol == "" or $this->asRol_Anterior == "" or $this->asArchivo == "")
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
			Inicio del metodo buscar(), permite buscar un usuario en la base de datos por medio del codigo
		****************************************************************************************************/
		public function fbBuscar()
		{
			$lbEnc=false;
			$lsSql="SELECT usu_codigo, usu_nombre, usu_nombre_persona,
					usu_cod_rol, usu_estatus, rol_nombre
					FROM usuario
					LEFT JOIN rol	ON rol_codigo = usu_cod_rol
					WHERE usu_codigo = '$this->aiCodigo'";
					// AND usu_codigo != '0'
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]			=$laArreglo["usu_codigo"];
				$this->aaVariables["txtNombre"]			=trim($laArreglo["usu_nombre"]);
				$this->aaVariables["txtNombre_Persona"]	=str_replace("+-","'",$laArreglo["usu_nombre_persona"]);
				$this->aaVariables["cmbRol"]			=$laArreglo["usu_cod_rol"];
				$this->aaVariables["txtRol"]			=$laArreglo["rol_nombre"];
				$this->aaVariables["cmbEstatus"]		=$laArreglo["usu_estatus"];
				$lbEnc=true;
				
				$liI=1;
				$lsSql2="SELECT	upv_codigo, upv_defecto,
								pve_codigo, pve_nombre
						FROM usuario_punto_venta
						JOIN punto_venta ON pve_codigo = upv_cod_puntoventa  
						WHERE upv_cod_usuario = '".$laArreglo["usu_codigo"]."'
						AND upv_estatus = 'A'
						ORDER BY upv_codigo";
				$lrTb2=parent::frFiltro($lsSql2);
				while($laArreglo2=parent::faProximo($lrTb2))
				{
					$this->aaVariables["laPunto_Venta"][$liI]['liPunto_Venta']	= $laArreglo2["pve_codigo"];
					$this->aaVariables["laPunto_Venta"][$liI]['lsPunto_Venta']	= $laArreglo2["pve_nombre"];
					$this->aaVariables["laPunto_Venta"][$liI]['lsDefecto']		= $laArreglo2["upv_defecto"];
					$liI++;
				}
				parent::fpCierraFiltro($lrTb2);
				$this->aaVariables["txtCantidad_Punto_Venta"] = $liI-1;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo buscar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Nombre(), permite buscar un usuario en la base de datos por medio 
			del nombre
		****************************************************************************************************/
		public function fbBuscar_Nombre($pbCodigo = false)
		{
			if($pbCodigo === true)
			{
				$lsCripterio = "AND usu_codigo != '$this->aiCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT usu_codigo, usu_nombre, usu_nombre_persona,
					usu_cod_rol, usu_estatus, rol_nombre
					FROM usuario
					JOIN rol	ON rol_codigo = usu_cod_rol
					WHERE UPPER(usu_nombre) = UPPER('$this->asNombre')
					$lsCripterio";
					// AND usu_codigo != '0'
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["txtCodigo"]			=$laArreglo["usu_codigo"];
				$this->aaVariables["txtNombre"]			=trim($laArreglo["usu_nombre"]);
				$this->aaVariables["txtNombre_Persona"]	=str_replace("+-","'",$laArreglo["usu_nombre_persona"]);
				$this->aaVariables["cmbRol"]			=$laArreglo["usu_cod_rol"];
				$this->aaVariables["txtRol"]			=$laArreglo["rol_nombre"];
				$this->aaVariables["cmbEstatus"]		=$laArreglo["usu_estatus"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Nombre()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbVerificar_Usuario(), permite verificar si el nombre de un usuario ya ha sido 
			registrado
		****************************************************************************************************/
		public function fbVerificar_Usuario()
		{
			$lbHecho=true;
			if($this->aiCodigo != '')
			{
				$lsCripterio = "AND usu_codigo != '$this->aiCodigo'";
			}
			$lsSql="SELECT usu_codigo
					FROM usuario
					WHERE UPPER(usu_nombre) = UPPER('$this->asNombre')
					$lsCripterio";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$lbHecho=false;
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbVerificar_Usuario()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbIncluir(), permite insertar un usuario en la base de datos
		****************************************************************************************************/
		public function fbIncluir()
		{
			$lbHecho= false;
			$this->asClave		= sha1(md5($this->asClave));
			$this->asRespuesta_1= sha1(md5($this->asRespuesta_1));
			$this->asRespuesta_2= sha1(md5($this->asRespuesta_2));
			$lsSql="INSERT INTO usuario (usu_nombre, usu_nombre_persona, usu_pegunta_1, usu_pegunta_2,
					usu_respuesta_1, usu_respuesta_2, usu_clave, usu_cod_rol) VALUES 
					(UPPER('$this->asNombre'), UPPER('$this->asNombre_Persona'), '$this->asPregunta_1',
					'$this->asPregunta_2', '$this->asRespuesta_1', '$this->asRespuesta_2', '$this->asClave',
					'$this->aiRol') RETURNING usu_codigo";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
			    $this->asMensaje = "Insertó el usuario: ".$this->asNombre.", nombre de la persona: ".$this->asNombre_Persona." en el rol ".$this->aiRol;
				parent::fpRegistro_Evento($this->asArchivo,"INCLUIR",$this->asMensaje);
				
				$liCodigo=parent::fiGetUltimo_Codigo();
				$liI = 1;
				while($this->aaPunto_Venta[$liI]["liPunto_Venta"] != '' and $lbHecho === true)
				{
					$lsSql="INSERT INTO usuario_punto_venta (upv_defecto, upv_cod_puntoventa, 
							upv_cod_usuario) VALUES (
							'".$this->aaPunto_Venta[$liI]["lsDefecto"]."', '".$this->aaPunto_Venta[$liI]["liPunto_Venta"]."',
							'".$liCodigo."')";
					$lbHecho=parent::fbEjecutar($lsSql);
					if($lbHecho === true)
					{
						$lsMensaje = "Insertó el Punto de Venta: ".$this->aaPunto_Venta[$liI]["lsPunto_Venta"]." y lo asignó al usuario: ".$this->asNombre;
						parent::fpRegistro_Evento($this->asArchivo,"INSERTAR",$lsMensaje);
					}
					else
					{
						$liError++;
					}
					$liI++;
				}
			}
			return $lbHecho;
		
		}
		/****************************************************************************************************
			Fin del metodo fbIncluir()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbModificar(), permite modificar un usuario en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbModificar()
		{
			$lbHecho	=false;
			$lsCripterio="";
			$lsMensaje	="";
			
			if($this->asRol_Anterior != $this->asRol)
			{
				$lsMensaje .= ", cambió el rol del usuario de ".$this->asRol_Anterior;
				$lsMensaje .= " por ".$this->asRol;
			}
			
			if($this->asClave != "")
			{
				$this->asClave= sha1(md5($this->asClave));
				$lsCripterio .= ", usu_clave = '$this->asClave'";
				$lsMensaje .= ", también cambió la contraseña";
			}
			
			if($this->asPregunta_1 != "" and $this->asPregunta_1 != "-" and $this->asRespuesta_1 != "")
			{
				$this->asRespuesta_1= sha1(md5($this->asRespuesta_1));
				$lsCripterio .= ", usu_pegunta_1 = '$this->asPregunta_1'";
				$lsCripterio .= ", usu_respuesta_1 = '$this->asRespuesta_1'";
				$lsMensaje .= ", asi mismo cambió la primera pregunta y respuesta secreta";
			}
			
			if($this->asPregunta_2 != "" and $this->asPregunta_2 != "-" and $this->asRespuesta_2 != "")
			{
				$this->asRespuesta_2= sha1(md5($this->asRespuesta_2));
				$lsCripterio .= ", usu_pegunta_2 = '$this->asPregunta_2'";
				$lsCripterio .= ", usu_respuesta_2 = '$this->asRespuesta_2'";
				$lsMensaje .= ", de igual manera cambió la segunda pregunta y respuesta secreta";
			}
			
			$lsSql="UPDATE usuario 
					SET usu_nombre = UPPER('$this->asNombre'),
					usu_nombre_persona = UPPER('$this->asNombre_Persona'),
					usu_cod_rol='$this->aiRol'
					$lsCripterio
					WHERE usu_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$this->asMensaje = "Modificó el usuario ".$this->aiCodigo.$lsMensaje;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
				
				$lsSql="UPDATE usuario_punto_venta 
						SET upv_estatus = 'I'
						WHERE upv_cod_usuario = '$this->aiCodigo'";
				parent::fbEjecutar($lsSql);
				
				$liI = 1;
				while($this->aaPunto_Venta[$liI]["liPunto_Venta"] != '' and $lbHecho === true)
				{
					$lsSql="SELECT upv_codigo
							FROM usuario_punto_venta 
							WHERE upv_cod_usuario = '$this->aiCodigo'
							AND upv_cod_puntoventa = '".$this->aaPunto_Venta[$liI]["liPunto_Venta"]."'";
					$lrTb=parent::frFiltro($lsSql);
					if($laArreglo=parent::faProximo($lrTb))
					{
						$lsSql="UPDATE usuario_punto_venta
								SET upv_estatus	= 'A',
								upv_defecto		= '".$this->aaPunto_Venta[$liI]["lsDefecto"]."'
								WHERE upv_codigo = '".$laArreglo['upv_codigo']."'";
						$lsMensaje = "Activó el Punto de Venta: ".$this->aaPunto_Venta[$liI]["lsPunto_Venta"]." al usuario: ".$this->asNombre;
						$lsAccion = "MODIFICAR";
					}
					else
					{
						$lsSql="INSERT INTO usuario_punto_venta (upv_defecto, upv_cod_puntoventa, 
								upv_cod_usuario) VALUES (
								'".$this->aaPunto_Venta[$liI]["lsDefecto"]."', '".$this->aaPunto_Venta[$liI]["liPunto_Venta"]."',
								'".$this->aiCodigo."')";
						$lsMensaje = "Insertó el Punto de Venta: ".$this->aaPunto_Venta[$liI]["lsPunto_Venta"]." y lo asignó al usuario: ".$this->asNombre;
						$lsAccion = "INSERTAR";
					}
					
					$lbHecho=parent::fbEjecutar($lsSql);
					if($lbHecho === true)
					{
						parent::fpRegistro_Evento($this->asArchivo,$lsAccion,$lsMensaje);
					}
					else
					{
						$liError++;
					}
					$liI++;
				}
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbDesactivar(), permite inactivar un usuario en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbDesactivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE usuario
					SET usu_estatus = 'I'
					WHERE usu_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$this->asMensaje = "Desactivó el usuario ".$this->aiCodigo." ".$this->asNombre." cédula número ".$this->asRif." código de vendedor ".$this->aiCodigo_Vendedor." en el rol ".$this->aiRol;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbDesactivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbActivar(), permite activar un usuario en la base de datos por medio de 
			su codigo
		****************************************************************************************************/
		public function fbActivar()
		{
			$lbHecho=false;
			$lsSql="UPDATE usuario 
					SET usu_estatus = 'A'
					WHERE usu_codigo ='$this->aiCodigo'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === true)
			{
				$this->asMensaje = "Activó el usuario ".$this->aiCodigo." ".$this->asNombre." cédula número ".$this->asRif." código de vendedor ".$this->aiCodigo_Vendedor." en el rol ".$this->aiRol;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbActivar()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbListar(), permite listar los usuarios registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbListar($paFormulario)
		{
			$lbHecho	= false;
			$lsCripterio= "";
			$lsLimite	= "";
			if($paFormulario['txtFiltro_Codigo'] != "")
			{//Busca por codigo
				$lsCripterio .= "WHERE usu_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Rol'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_cod_rol = '".$paFormulario['cmbFiltro_Rol']."'";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			// $lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
			// $lsCripterio .= " usu_codigo != '0'";
			// $lsCripterio .= " AND usu_codigo != '1'";
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = ($paFormulario['txtFiltro_Pagina'] - 1) * $paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}
			
			$lsSql="SELECT usu_codigo, usu_nombre, usu_nombre_persona, rol_nombre,
					CASE usu_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS usu_estatus
					FROM usuario
					JOIN rol	ON rol_codigo = usu_cod_rol
					$lsCripterio
					ORDER BY usu_codigo";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI][0]=$laArreglo["usu_codigo"];
					$this->aaVariables[$liI][1]=$laArreglo["usu_nombre"];
					$this->aaVariables[$liI][2]=str_replace("+-","'",$laArreglo["usu_nombre_persona"]);
					$this->aaVariables[$liI][3]=$laArreglo["rol_nombre"];
					$this->aaVariables[$liI][4]=$laArreglo["usu_estatus"];
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
				$lsCripterio .= "WHERE usu_codigo = '".$paFormulario['txtFiltro_Codigo']."'";
			}
			
			if($paFormulario['txtFiltro_Nombre'] != "" and $paFormulario['txtFiltro_Codigo'] == "")
			{//Busca por coincidencia
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Rol'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_cod_rol = '".$paFormulario['cmbFiltro_Rol']."'";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			// $lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
			// $lsCripterio .= " usu_codigo != '0'";
			// $lsCripterio .= " AND usu_codigo != '1'";
			
			$lsSql="SELECT COUNT(usu_codigo) AS total
					FROM usuario	
					JOIN rol	ON rol_codigo = usu_cod_rol 
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
			$lsSql="SELECT usu_codigo, usu_nombre
					FROM usuario
					WHERE usu_estatus = 'A' 
					AND (UPPER(usu_nombre) LIKE UPPER('%".$psBusqueda."%')
					OR usu_codigo = '".intval($psBusqueda)."')
					ORDER BY usu_codigo";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					// si no imprime el autocomplete dejar el utf8_encode
					$this->aaVariables[$liI]['codigo']	=mb_convert_case($laArreglo["usu_codigo"], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI]['label']	=mb_convert_case($laArreglo["usu_nombre"].' - '.$laArreglo["usu_nombre_persona"], MB_CASE_TITLE, "UTF-8");
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
				$lsCripterio .= "WHERE usu_nombre LIKE UPPER('%".$paFormulario['txtFiltro_Nombre']."%')";
			}
			
			if($paFormulario['cmbFiltro_Estado'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_estatus = '".$paFormulario['cmbFiltro_Estado']."'";
			}
			
			if($paFormulario['cmbFiltro_Rol'] != "-")
			{
				$lsCripterio .= $lsCripterio != '' ? ' AND ' : ' WHERE ';
				$lsCripterio .= " usu_cod_rol = '".$paFormulario['cmbFiltro_Rol']."'";
			}
			
			if($paFormulario['cmbFiltro_Numero_Filas']>0)
			{
				$pagina	 = $paFormulario['txtFiltro_Pagina'] - 1;
				$lsLimite.= " LIMIT ".$paFormulario['cmbFiltro_Numero_Filas'];
				$lsLimite.= " OFFSET ".$pagina;
			}

			$lsSql="SELECT usu_codigo, usu_nombre, usu_nombre_persona, rol_nombre,
					CASE usu_estatus
						WHEN 'A' THEN 'Activo'
						WHEN 'I' THEN 'Inactivo'
					END AS usu_estatus
					FROM usuario 
					JOIN rol	ON rol_codigo = usu_cod_rol
					$lsCripterio
					ORDER BY usu_codigo
					$lsLimite";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					$this->aaVariables[$liI]['liCodigo']		=$laArreglo["usu_codigo"];
					$this->aaVariables[$liI]['lsNombre']		=$laArreglo["usu_nombre"];
					$this->aaVariables[$liI]['lsNombre_Persona']=$laArreglo["usu_nombre_persona"];
					$this->aaVariables[$liI]['lsRol']			=$laArreglo["rol_nombre"];
					$this->aaVariables[$liI]['lsEstatus']		=$laArreglo["usu_estatus"];
					
					$lsPunto_Venta = '';
					$lsSql2="SELECT	pve_nombre
							FROM usuario_punto_venta
							JOIN punto_venta ON pve_codigo = upv_cod_puntoventa  
							WHERE upv_cod_usuario = '".$laArreglo["usu_codigo"]."'
							AND upv_estatus = 'A'
							ORDER BY upv_codigo";
					$lrTb2=parent::frFiltro($lsSql2);
					while($laArreglo2=parent::faProximo($lrTb2))
					{
						$lsPunto_Venta .= $lsPunto_Venta != '' ? ', ' : '';
						$lsPunto_Venta .= $laArreglo2["pve_nombre"];
					}
					parent::fpCierraFiltro($lrTb2);
					
					$this->aaVariables[$liI]['lsPunto_Venta'] = $lsPunto_Venta;
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
			Inicio del metodo fbReestablecer_Usuario(), permite restablecer un usuario del sistema, quitando 
			las preguntas y respuestas de seguridad, asi como tambien activando el usuario por si este se 
			encontraba inactivo. La nueva contraseña será el mismo nombre de usuario
		****************************************************************************************************/
		public function fbReestablecer_Usuario()
		{
			$lbHecho=false;
			$this->asClave = sha1(md5($this->asNombre));
			$lsSql="UPDATE usuario 
					SET usu_pegunta_1='-', usu_pegunta_2='-', usu_respuesta_1='-', 
						usu_respuesta_2='-', usu_clave='$this->asClave',
						usu_estatus = 'A', usu_intento_inicio='0'
					WHERE usu_nombre ='$this->asNombre'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho == true)
			{
				$this->asMensaje = "Reestableció el Usuario ".$this->asNombre;
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbReestablecer_Usuario()
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
