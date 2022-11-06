<?php
	/*
	*	clsValidar.php
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
	class clsValidar extends clsFunciones
	{
		private $asNombre;
		private $asNombre_Persona;
		private $asNombre_Recuperar;
		private $asClave;
		private $asPregunta1;
		private $asRespuesta1;
		private $asPregunta2;
		private $asRespuesta2;
		private $aiRol;
		private $aaVariables;
		private $asBusqueda;
		private $asCoincidencia;
		
		private $asClave_Anterior;
		private $asArchivo;
		private $asMensaje;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->asNombre				="";
			$this->asNombre_Persona		="";
			$this->asNombre_Recuperar	="";
			$this->asClave				="";
			$this->asPregunta1			="";
			$this->asRespuesta1			="";
			$this->asPregunta2			="";
			$this->asRespuesta2			="";
			$this->aiRol				="";
			$this->asEstatus			="";
			$this->aaVariables			=array();
			$this->asBusqueda			="";
			$this->asClave_Anterior		="";
			$this->asArchivo			="";
			$this->asMensaje			="";
		}
		
		public function __destruct()
		{
		}
		
		/****************************************************************************************************
			Inicio de los metodos SET permite recibir los datos enviados desde el formulario.
		****************************************************************************************************/
		public function fpSetFormulario($paFormulario)
		{
			$lbBueno					= true;
			$this->asNombre				= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre"]);
			$this->asNombre_Persona		= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre_Persona"]);
			$this->asClave				= parent::fsVerificar_Texto_Numeros_Simbolos(mb_convert_case($paFormulario["txtClave"],  MB_CASE_UPPER, "UTF-8"));
			$this->asPregunta1			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["cmbPregunta1"]);
			$this->asRespuesta1			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtRespuesta1"]);
			$this->asPregunta2			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["cmbPregunta2"]);
			$this->asRespuesta2			= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtRespuesta2"]);
			$this->aiRol				= parent::fiVerificar_Numeros_Enteros($paFormulario["cmbRol"]);
			$this->asNombre_Recuperar	= parent::fsVerificar_Texto_Numeros_Simbolos($paFormulario["txtNombre_Recuperar"]);
			$this->asClave_Anterior		= parent::fsVerificar_Texto_Numeros_Simbolos(mb_convert_case($paFormulario["txtClave_Anterior"],  MB_CASE_UPPER, "UTF-8"));
			$this->asArchivo			= $paFormulario["txtArchivo"];
			
			// Sección que verifica la operacion a realizar así como también si los datos
			// obligatorios no esten en blanco
			if($paFormulario["txtOperacion"] == "cambiar_clave" )
			{
				if($this->asClave == "" or $this->asRespuesta1 == "" or $this->asRespuesta2 == "" or $this->asPregunta1  == "" or $this->asPregunta2 == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "verificar_respuestas" )
			{
				if($this->asRespuesta1 == "" or $this->asRespuesta2 == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "cambiar_clave_confirmado" )
			{
				if($this->asClave == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "iniciar_sesion" )
			{
				if($this->asNombre == "" or $this->asClave == "" or $this->asArchivo == "")
				{
					$lbBueno = false;
				}
			}
			elseif($paFormulario["txtOperacion"] == "recuperar_clave" )
			{
				if($this->asNombre_Recuperar == "" or $this->asArchivo == "")
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
			Inicio del metodo fbIniciar_Sesion(), permite iniciar sesión a un usuario en el sistema, pasando 
			unas validaciones de seguridad que van mas alla de que coincidan el usuario y la contraseña, como 
			por ejemplo: el rol al que pertenece el usuario debe estar activo, el usuario debe estar activo,
			el usuario tiene tres (3) intentos por dia para iniciar sesión antes de que sea bloqueado.
		****************************************************************************************************/
		public function fbIniciar_Sesion()
		{
			$lbEnc=false;
			$lsClave_Normal= $this->asClave;
			$this->asClave	= sha1(md5($this->asClave));
			$lsSql="SELECT	usu_codigo, usu_nombre, usu_nombre_persona, usu_cod_rol, usu_estatus, 
							usu_clave, usu_intento_inicio, rol_estatus
					FROM usuario
					JOIN rol	ON rol_codigo = usu_cod_rol
					WHERE UPPER(usu_nombre) = UPPER('$this->asNombre')";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				if($laArreglo["usu_clave"] == $this->asClave)
				{
					$this->aaVariables["u_codigo"]		=trim($laArreglo["usu_codigo"]);
					$this->aaVariables["u_cedula"]		=trim($laArreglo["ven_rif"]);
					$this->aaVariables["u_nusuario"]	=trim($laArreglo["usu_nombre"]);
					$this->aaVariables["u_nombre"]		=trim($laArreglo["usu_nombre_persona"]);
					$this->aaVariables["u_crol"]		=trim($laArreglo["usu_cod_rol"]);
					$this->aaVariables["u_nrol"]		=trim($laArreglo["rol_nombre"]);
					
					if($laArreglo["usu_intento_inicio"] >= 3)
					{
						$lbEnc="inactivo";
					}
					elseif($laArreglo["usu_estatus"] != 'A')
					{
						$lbEnc="inactivo";
					}
					elseif($laArreglo["rol_estatus"] != 'A')
					{
						$lbEnc="rol_inactivo";
					}
					else
					{
						$lsSql2="SELECT mod_nombre, mod_icono, ser_nombre, ser_nombre_largo,
								ser_url, ser_abrir_especial, ser_icono
								FROM servicio_rol 
								JOIN servicio	ON ser_codigo = sro_cod_servicio
								JOIN modulo		ON mod_codigo = ser_cod_modulo
								WHERE sro_cod_rol = '".$laArreglo["usu_cod_rol"]."'
								AND ser_estatus = 'A'
								AND sro_estatus = 'A'
								AND mod_estatus = 'A'
								ORDER BY mod_posicion, mod_nombre, ser_posicion, ser_nombre, ser_url";
						$lrTb2=parent::frFiltro($lsSql2);
						$liM = 0;
						$liS = 1;
						while($laArreglo2=parent::faProximo($lrTb2))
						{
							if($laServicio[$liM]["lsModulo"] != $laArreglo2["mod_nombre"])
							{
								$liM++;
								$liS=1;
								$laServicio[$liM]["lsModulo"]	= $laArreglo2["mod_nombre"];
								$laServicio[$liM]["lsIcono"]	= $laArreglo2["mod_icono"];
							}
							else
							{
								$liS++;
							}
							$laServicio[$liM][$liS]["lsServicio"]		= $laArreglo2["ser_nombre"];
							$laServicio[$liM][$liS]["lsServicio_Largo"]	= $laArreglo2["ser_nombre_largo"];
							$laServicio[$liM][$liS]["lsUrl"]			= $laArreglo2["ser_url"];
							$laServicio[$liM][$liS]["lsAbrir_Especial"]	= $laArreglo2["ser_abrir_especial"];
							$laServicio[$liM][$liS]["lsIcono"]			= $laArreglo2["ser_icono"];
						}
						$this->aaVariables["u_permisos"] = $laServicio;
						$lbEnc=true;
						if($lbEnc === true)
						{
							$this->asMensaje = "Inició sesión el usuario ".$this->aaVariables["u_codigo"]." ".$this->aaVariables["u_nusuario"]." con el rol ".$this->aaVariables["u_nrol"];
							parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje,$this->aaVariables["u_nusuario"]);
						}
					}
				}
				else
				{	
					if($laArreglo["usu_intento_inicio"] >= 3)
					{//Si el usuario ha intentado iniciar sesión mas de 2 veces, su usuario pasa a inactivo y no puede iniciar sesión
						$lbEnc="inactivo";
					}
					elseif($laArreglo["usu_estatus"] == 'A')
					{
						$lbEnc="mal_intento";
						
						if($laArreglo["usu_intento_inicio"] < 3)
						{//Si el usuario ha intentado iniciar sesion con la clave errada, se suma 1 mas a sus intentos de inicio de sesión
						//El usuario tiene 3 oportunidades por dia de iniciar sesion mientras este activo, cuando se le terminen dichas oportunidades sera inactivado
							$this->asMensaje = "Intentó iniciar sesión el usuario ".$this->asNombre.$lsMensaje;
							if($laArreglo["usu_fecha_intento_inicio"] != date("Y-m-d"))
							{//Si es un dia diferente el intento sera 1
								$liIntento = 1;
							}
							else
							{// Si el dia es el mismo, se suma 1 mas a los intentos que ya posee
								$liIntento = intval($laArreglo["usu_intento_inicio"] + 1);
								
								if($liIntento > 2)
								{// Si ya posee tres intentos o mas se procede a inactivar
									$lsEstatus = ", usu_estatus='I'";
									$lbEnc="bloqueado";
									$this->asMensaje = "Se bloqueó al usuario ".$this->asNombre." por agotar los intentos de inicio de sesión";
								}
							}
							$lsSql="UPDATE usuario 
									SET usu_intento_inicio='".$liIntento."', 
									usu_fecha_intento_inicio ='NOW()'
									".$lsEstatus."
									WHERE usu_nombre ='$this->asNombre'";
							$lbHecho=parent::fbEjecutar($lsSql);
							if($lbHecho === true)
							{
								parent::fpRegistro_Evento($this->asArchivo,"SESION",$this->asMensaje,$this->asNombre);
							}
							
						}
					}
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbIniciar_Sesion()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbCambiar_Clave_Nuevo(), permite cambiar la contraseña, las preguntas y 
			respuesta de seguridad a un usuario del sistema cuando este inicia sesión por primera vez o 
			despues de haber restablecido su contraseña
		****************************************************************************************************/
		public function fbCambiar_Clave_Nuevo()
		{
			$lbHecho=false;
			$lsSql="SELECT usu_clave
					FROM usuario
					WHERE UPPER(usu_nombre) = UPPER('".$_SESSION["sogem_u_nusuario"]."')";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->asClave_Anterior	= sha1(md5($this->asClave_Anterior));
				if($this->asClave_Anterior == $laArreglo["usu_clave"])
				{
					$this->asClave		= sha1(md5($this->asClave));
					$this->asRespuesta1	= sha1(md5($this->asRespuesta1));
					$this->asRespuesta2	= sha1(md5($this->asRespuesta2));
					$lsSql="UPDATE usuario 
							SET usu_pegunta_1='$this->asPregunta1', usu_pegunta_2='$this->asPregunta2',
							usu_respuesta_1='$this->asRespuesta1', usu_respuesta_2='$this->asRespuesta2',
							usu_clave='$this->asClave'
							WHERE usu_nombre ='".$_SESSION["sogem_u_nusuario"]."'";
					$lbHecho=parent::fbEjecutar($lsSql);
					if($lbHecho == true)
					{
						$this->asMensaje = "El usuario ".$_SESSION["sogem_u_nusuario"]." cambió su clave por primera vez";
						parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje,$_SESSION["sogem_u_nusuario"]);
					}
					else
					{
						$this->asMensaje = "Error al cambiar la información de seguridad del usuario ".$_SESSION["sogem_u_nusuario"];
						parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje,$_SESSION["sogem_u_nusuario"]);
					}
				}
				else
				{
					$this->asMensaje = "Error la clave anterior ingresada por el usuario ".$_SESSION["sogem_u_nusuario"]." no coincide con su clave actual";
					parent::fpRegistro_Evento($this->asArchivo,"BUSCAR",$this->asMensaje,$_SESSION["sogem_u_nusuario"]);
				}
			}
			else
			{
				$this->asMensaje = "Error el usuario ".$_SESSION["sogem_u_nusuario"]." no está registrado";
				parent::fpRegistro_Evento($this->asArchivo,"BUSCAR",$this->asMensaje,$_SESSION["sogem_u_nusuario"]);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbCambiar_Clave_Nuevo()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbCambiar_Clave_olvido(), permite cambiar la contraseña a un usuario cuando la
			anterior ha sido olvidada
		****************************************************************************************************/
		public function fbCambiar_Clave_olvido()
		{
			$lbHecho			= false;
			$this->asClave		= sha1(md5($this->asClave));
			$lsSql="UPDATE usuario
					SET usu_clave='$this->asClave'
					WHERE usu_nombre ='".$_SESSION["sogem_u_nusuario"]."'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho == true)
			{
				$this->asMensaje = "El usuario ".$_SESSION["sogem_u_nusuario"]." cambió su clave por haberla olvidado";
				parent::fpRegistro_Evento($this->asArchivo,"MODIFICAR",$this->asMensaje,$_SESSION["sogem_u_nusuario"]);
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbCambiar_Clave_Nuevo()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbVerificar_Usuario(), permite verificar si un usuario se encuentra registrado 
			en la base de datos; esta verificación se lleva a cabo cuando el usuario intenta recuperar la 
			contrasela debido a que la ha olvidado
		****************************************************************************************************/
		public function fbVerificar_Usuario()
		{
			$lbEnc=false;
			$lsSql="SELECT usu_codigo, usu_nombre, usu_estatus, 
					usu_nombre_persona, usu_pegunta_1, usu_pegunta_2
					FROM usuario 
					WHERE UPPER(usu_nombre) = UPPER('$this->asNombre_Recuperar')
					AND usu_estatus = 'A'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["u_codigo"]		=$laArreglo["usu_codigo"];
				$this->aaVariables["u_nusuario"]	=$laArreglo["usu_nombre"];
				$this->aaVariables["u_nombre"]		=$laArreglo["usu_nombre_persona"];
				$this->aaVariables["u_pegunta_1"]	=$laArreglo["usu_pegunta_1"];
				$this->aaVariables["u_pegunta_2"]	=$laArreglo["usu_pegunta_2"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbCambiar_Clave_Nuevo()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbVerificar_Respuestas(), permite verifica que las respuesta de seguridad 
			proporcionada por el usuario sean verdaderas 
		****************************************************************************************************/
		public function fbVerificar_Respuestas($psUsuario)
		{
			$lbEnc=false;
			$this->asRespuesta1	= sha1(md5($this->asRespuesta1));
			$this->asRespuesta2	= sha1(md5($this->asRespuesta2));
			print $lsSql="SELECT usu_codigo, usu_nombre, usu_estatus, 
					usu_nombre_persona
					FROM usuario 
					WHERE UPPER(usu_nombre) = UPPER('".$psUsuario."')
					AND usu_respuesta_1='$this->asRespuesta1' 
					AND usu_respuesta_2='$this->asRespuesta2'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$this->aaVariables["u_codigo"]	=$laArreglo["usu_codigo"];
				$this->aaVariables["u_nusuario"]=$laArreglo["usu_nombre"];
				$this->aaVariables["u_nombre"]	=$laArreglo["usu_nombre_persona"];
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbCambiar_Clave_Nuevo()
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
