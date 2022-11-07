<?php
	/*
    *      clsFunciones.php
    *      
  	*      Copyright 2018 Hernández^2
    *      
    *      Este programa es software libre, puede redistribuirlo y / o modificar
    *      Bajo los términos de la GNU LicenciaPública General(GPL) publicada por
    *      La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
    *      
    *      Este programa se distribuye con la esperanza de que sea útil,
    *      Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
    *      COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
    */
	require_once("clsDatos.php");
	class clsFunciones extends clsDatos
	{
		private function __construct()
		{
		}
		
		private function __destruct()
		{
		}
		
		/****************************************************************************************************
			Inicio del metodo fpValidar_Espaciado_Texto(texto), permite borrar espacios en blanco adicionales
			(en medio) de una caja de texto, dejando solo un espacio entre cada palabra.
		****************************************************************************************************/
		protected function fsValidar_Espaciado_Texto($psCadena)
		{
			$psCadena = stripslashes($psCadena);
			$psCadena = trim($psCadena);
			//Cadena a formar
			$lsCadena_Nueva	= "";
			for($liI=0 ; $liI < strlen($psCadena) ; $liI++)
			{
				if($psCadena[$liI] == " ")
				{
					// Si en la posicion de la cadena es un espacio en blanco y la 
					// siguiente posicion tambien es un espacio en blanco se omite (borra) 
					// el espacio en blanco para que solo quede un espacio entre las palabras
					if($psCadena[$liI] == $psCadena[$liI+1])
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $psCadena[$liI];
					}
				}
				else
				{
					$lsCadena_Nueva .= $psCadena[$liI];
				}
			}
			return $lsCadena_Nueva;
		}
		/****************************************************************************************************
			Fin del metodo fpValidar_Espaciado_Texto()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiVerificar_Numeros_Enteros(numero), permite eliminar los caracteres que no estan en la 
			variable lsAceptados (contiene los caracteres permitidos), retornando una cadena limpia.
		****************************************************************************************************/
		protected function fiVerificar_Numeros_Enteros($piNumero)
		{
			//Cadena recibida
			$liNumero		= $this->fsValidar_Espaciado_Texto($piNumero);
			$liNumero_Nuevo	= "";
			if($liNumero != "")
			{
				//Caracteres admitidos
				$lsAceptados	= "1234567890";
				
				//Tamaño de la cadena
				$liTamano		= strlen($liNumero);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $liNumero[$liI]) === false)
					{
						continue;
					}
					else
					{
						$liNumero_Nuevo .= $liNumero[$liI];
					}
				}
			}
			return $liNumero_Nuevo;
		}
		/****************************************************************************************************
			Fin del metodo fiVerificar_Numeros_Enteros()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiVerificar_Numeros_Guion(numero), permite eliminar los caracteres que no estan
			en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena limpia.
		****************************************************************************************************/
		protected function fiVerificar_Numeros_Guion($piNumero)
		{
			//Cadena recibida
			$liNumero		= $this->fsValidar_Espaciado_Texto($piNumero);
			$liNumero_Nuevo	= "";
			if($liNumero != "")
			{
				//Caracteres admitidos
				$lsAceptados	= "-/1234567890";
				
				//Tamaño de la cadena
				$liTamano		= strlen($liNumero);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $liNumero[$liI]) === false)
					{
						continue;
					}
					else
					{
						$liNumero_Nuevo .= $liNumero[$liI];
					}
				}
			}
			return $liNumero_Nuevo;
		}
		/****************************************************************************************************
			Fin del metodo fiVerificar_Numeros_Guion()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiVerificar_Numeros_Punto(numero), permite eliminar los caracteres que no estan
			en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena limpia.
		****************************************************************************************************/
		protected function fiVerificar_Numeros_Punto($piNumero)
		{
			//Cadena recibida
			$liNumero		= $this->fsValidar_Espaciado_Texto($piNumero);
			$liNumero_Nuevo	= "";
			if($liNumero != "")
			{
				//Caracteres admitidos
				$lsAceptados	= ".1234567890";
				
				//Tamaño de la cadena
				$liTamano		= strlen($liNumero);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $liNumero[$liI]) === false)
					{
						continue;
					}
					else
					{
						$liNumero_Nuevo .= $liNumero[$liI];
					}
				}
			}
			return $liNumero_Nuevo;
		}
		/****************************************************************************************************
			Fin del metodo fiVerificar_Numeros_Punto()
		****************************************************************************************************/
		/****************************************************************************************************
			Inicio del metodo fiVerificar_Numeros_Punto(numero), permite eliminar los caracteres que no estan
			en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena limpia.
		****************************************************************************************************/
		protected function fiVerificar_Numeros_Coma($piNumero)
		{
			//Cadena recibida
			$liNumero		= $this->fsValidar_Espaciado_Texto($piNumero);
			$liNumero_Nuevo	= "";
			if($liNumero != "")
			{
				//Caracteres admitidos
				$lsAceptados	= ",1234567890";
				
				//Tamaño de la cadena
				$liTamano		= strlen($liNumero);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $liNumero[$liI]) === false)
					{
						continue;
					}
					else
					{
						$liNumero_Nuevo .= $liNumero[$liI];
					}
				}
			}
			return $liNumero_Nuevo;
		}
		/****************************************************************************************************
			Fin del metodo fiVerificar_Numeros_Coma()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fiVerificar_Numeros_Formato(numero), permite eliminar los caracteres que no
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena
			limpia.
		****************************************************************************************************/
		protected function fiVerificar_Numeros_Formato($piNumero, $psCaracteres_Formato)
		{
			//Cadena recibida
			$liNumero		= $this->fsValidar_Espaciado_Texto($piNumero);
			$liNumero_Nuevo	= "";
			if($liNumero != "")
			{
				//Caracteres admitidos
				$lsAceptados	= "1234567890".$psCaracteres_Formato;
				
				//Tamaño de la cadena
				$liTamano		= strlen($liNumero);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $liNumero[$liI]) === false)
					{
						continue;
					}
					else
					{
						$liNumero_Nuevo .= $liNumero[$liI];
					}
				}
			}
			return $liNumero_Nuevo;
		}
		/****************************************************************************************************
			Fin del metodo fiVerificar_Numeros_Punto()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Texto_Numeros_Simbolos(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Texto_Numeros_Simbolos($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= " .,;:-_+*¡!#%&/()=?¿ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890abcdefghijklmnñopqrstuvwxyzáéíóúöü";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $psCadena[$liI];
					}
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Texto_Numeros_Simbolos()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Texto_Numeros_Simbolos_2(cadena), permite eliminar los caracteres 
			que no estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una 
			cadena limpia. Asi mismo, permite los saltos de linea, cambiando los caracteres \n o \r que 
			llegan de la vista, por <br> (salto de linea en html)
		****************************************************************************************************/
		protected function fsVerificar_Texto_Numeros_Simbolos_2($psCadena)
		{
			//Cadena recibida
			$psCadena		= nl2br($psCadena);
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= " .,;:-_+*¡!#%&/()=?¿ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890abcdefghijklmnñopqrstuvwxyzáéíóúöü";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $psCadena[$liI];
					}
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Texto_Numeros_Simbolos_2()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Clave(cadena), permite eliminar los caracteres que no estan en la
			variable lsAceptados (contiene los caracteres permitidos), retornando una cadena limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Clave($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= ".,;:-_*¡!#%&/()¿?+ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $psCadena[$liI];
					}
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Clave()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Texto(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Texto($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= " ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzáéíóúöü";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
				
			}
			return $lsCadena_Nueva;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Texto()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Nombres(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Nombres($psCadena)
		{
			//Cadena recibida
			$lsCadena		= str_replace("'","+-",$psCadena);
			$lsCadena		= $this->fsValidar_Espaciado_Texto($lsCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= " +-ÁÉÍÓÚÜABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzáéíóúöü";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Nombres()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Enlace(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Enlace($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= "&?=.-_/abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Enlace()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Telefono(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Telefono($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= "1234567890";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
			}
			/*if(strlen($lsCadena) != 12 or strpos($lsCadena, '-') != 4)
			{
				$lsCadena = "";
			}*/
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Telefono()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Opciones(cadena,opciones), permite verificar si la cadena 
			corresponde a una de las opciones que se pasan en el segundo parametros, de no ser asi la cadena
			se limpia para evitar que pase las siguientes validaciones.
		****************************************************************************************************/
		protected function fsVerificar_Opciones($psCadena, $psOpciones)
		{
			//Cadena recibida
			$lsCadena 	= "";
			$lsCadena	= $this->fsValidar_Espaciado_Texto($psCadena);
			if($lsCadena != "")
			{
				if(strpos($psOpciones, $lsCadena) === false)
				{
					$lsCadena	= "";
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Opciones()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Email(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Email($psCadena)
		{
			//Cadena recibida
			//Se cambia el apostrofe por un +, ya que en el caso de haber este caracter sera reemplazado al momento de limpiar la cadena
			$lsCadena		= str_replace("'","+",$psCadena); 
			$lsCadena		= $this->fsValidar_Espaciado_Texto($lsCadena);
			$lsCadena_Nueva	= "";
			if(strlen($lsCadena) > 9)
			{
				//Caracteres admitidos
				$lsAceptados	= "@.,-_ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
				if(strrpos($lsCadena, '.') < strpos($lsCadena,'@'))
				{
					$lsCadena = "";
				}
			}
			else
			{
				$lsCadena = "";
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Email()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbFecha_Valida(), permite verificar si una cadena es una fecha valida, se debe 
			pasar en un formato de 'DD-MM-YYY'
		****************************************************************************************************/
		protected function fbFecha_Valida($psFecha)
		{
			$lbValido = false;
			if(strlen($psFecha)==10)
			{//checkdate 
				if((substr($psFecha,2,1) == '-' or substr($psFecha,2,1) == '/') and (substr($psFecha,5,1) == '-' or substr($psFecha,5,1) == '/'))
				{
					$laFecha = explode('-',$psFecha);
					if(checkdate($laFecha[1],$laFecha[0],$laFecha[2]) === true)
					{
						$lbValido = true;
					}
					else
					{
						$lbValido = false;
					}
				}
				else
				{
					$lbValido = false;
				}
			}
			else
			{
				$lbValido = false;
			}
			return $lbValido;
		}
		/****************************************************************************************************
			Fin del metodo fbFecha_Valida()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Fecha(), permite verificar si una cadena es una fecha valida, se
			debe pasar en un formato de 'MM-DD-YYYY' para que la funcion checkdate la valide, y si no es una
			fecha valida se devuelve blanco
		****************************************************************************************************/
		protected function fsVerificar_Fecha($psFecha)
		{
			$lsFecha = "";
			$psFecha = str_replace("/","-",$psFecha);
			if(strlen($psFecha)==10)
			{//checkdate 
				if(substr($psFecha,2,1) == '-' and substr($psFecha,5,1) == '-')
				{
					$laFecha = explode('-',$psFecha);
					if(checkdate($laFecha[1],$laFecha[0],$laFecha[2]) === true)
					{
						$lsFecha = $psFecha;
					}
				}
			}
			return $lsFecha;
		}
		/****************************************************************************************************
			Fin del metodo fsFecha_Valida()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Rif(), permite verificar si una cadena es un rif valido
		****************************************************************************************************/
		protected function fsVerificar_Rif($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= "JVGEPC-1234567890";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Rif()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsFecha_N(fecha), permite cambiar el formato a una fecha de tipo YYYY-MM-DD a 
			DD-MM-YYYY para que pueda ser visualizada por el usuario. 
		****************************************************************************************************/
		protected function fsFecha_N($psFecha)
		{
			$lsHoy=date("d-m-Y");
			if(strlen($psFecha)==10)
			{
				$lsDia=substr($psFecha,8,2);
				$lsMes=substr($psFecha,5,2);
				$lsAno=substr($psFecha,0,4);
				$lsHoy=$lsDia."-".$lsMes."-".$lsAno;
			}
			return $lsHoy;
		}
		/****************************************************************************************************
			Fin del metodo fsFecha_N()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsFecha_B(fecha), permite cambiar el formato a una fecha de tipo DD-MM-YYYY a 
			YYYY-MM-DD para que pueda ser manejada por la base de datos. 
		****************************************************************************************************/
		protected function fsFecha_B($psFecha,$psFecha_Defecto = "Y-m-d")
		{
			$lsHoy=date($psFecha_Defecto);
			if(strlen($psFecha)==10)
			{
				$lsDia=substr($psFecha,0,2);
				$lsMes=substr($psFecha,3,2);
				$lsAno=substr($psFecha,6,4);
				$lsHoy=$lsAno."-".$lsMes."-".$lsDia;
			}
			return $lsHoy;
		}
		/****************************************************************************************************
			Fin del metodo fsFecha_B()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fsVerificar_Hora(cadena), permite eliminar los caracteres que no 
			estan en la variable lsAceptados (contiene los caracteres permitidos), retornando una cadena 
			limpia. 
		****************************************************************************************************/
		protected function fsVerificar_Hora($psCadena)
		{
			//Cadena recibida
			$lsCadena		= $this->fsValidar_Espaciado_Texto($psCadena);
			$lsCadena_Nueva	= "";
			if($lsCadena != "")
			{
				//Caracteres admitidos
				$lsAceptados	= ":1234567890";
				//Tamaño de la cadena
				$liTamano		= strlen($lsCadena);
				
				for($liI = 0 ; $liI < $liTamano ; $liI++)
				{
					if(strpos($lsAceptados, $lsCadena[$liI]) === false)
					{
						continue;
					}
					else
					{
						$lsCadena_Nueva .= $lsCadena[$liI];
					}
				}
			}
			print strpos($lsCadena, ':');
			
			if(strlen($lsCadena) != 5 or strpos($lsCadena, ':') != 2)
			{
				$lsCadena = "";
			}
			return $lsCadena;
		}
		/****************************************************************************************************
			Fin del metodo fsVerificar_Telefono()
		****************************************************************************************************/
		
		protected function fsNumeros_A_Letras($pfMonto)
		{
			$lsCantidad = "";
			$lfMonto = $this->fsColocar_Caracter($pfMonto,10,'I','0'); //Se define el tamano del monto a 10 caracteres -XXXXXXX.XX-
			
			$lsCantidad .= $this->fsBuscar_Unidad(substr($lfMonto,0,1),"MILLÓN", "MILLONES", NULL, substr($lfMonto,1,1)); //Unidad de Millón
			$lsCantidad .= $this->fsBuscar_Centena(substr($lfMonto,1,1),"CIENTO", "CIENTOS", NULL, NULL); // Centena de Mil
			$lsCantidad .= $this->fsBuscar_Decena(substr($lfMonto,2,1),substr($lfMonto,3,1)); // Decena de Mil
			$lsCantidad .= $this->fsBuscar_Unidad(substr($lfMonto,3,1),"MIL", "MIL",substr($lfMonto,2,1), substr($lfMonto,4,1)); //Unidad de Mil
			$lsCantidad .= $this->fsBuscar_Centena(substr($lfMonto,4,1),"CIENTO", "CIENTOS", NULL, NULL); // Centena
			$lsCantidad .= $this->fsBuscar_Decena(substr($lfMonto,5,1),substr($lfMonto,6,1)); // Decena
			$lsCantidad .= $this->fsBuscar_Unidad(substr($lfMonto,6,1),"", "", substr($lfMonto,5,1), NULL); //Unidad
	
			if(substr($lfMonto,8,2) == "00" or substr($lfMonto,8,2) == "")
			{
				$lsCantidad .= " EXACTOS";
			}
			else
			{
				$lsCantidad .= " CON ";
				$lsCantidad .= $this->fsBuscar_Decena(substr($lfMonto,8,1),substr($lfMonto,9,1)); // Decena
				$lsCantidad .= $this->fsBuscar_Unidad(substr($lfMonto,9,1),"", "", substr($lfMonto,8,1), NULL); //Unidad
				$lsCantidad .= " CÉNTIMOS ";
			}
			
			$lsCantidad = str_replace('  ',' ',$lsCantidad);
			return $lsCantidad;
		}
		
		protected function fsBuscar_Centena($piNumero, $psPrefijo_Singular, $psPrefijo_Plural, $piNumero_Anterior = NULL, $piNumero_Siguiente = NULL)
		{
			$lsCantidad = "";
			switch($piNumero)
			{
				case "1":
					$lsCantidad = " ".$psPrefijo_Singular." ";
					break;
				
				case "2":
					$lsCantidad = " DOS".$psPrefijo_Plural;
					break;
					
				case "3":
					$lsCantidad = " TRES".$psPrefijo_Plural;
					break;
					
				case "4":
					$lsCantidad = " CUATRO".$psPrefijo_Plural;
					break;
					
				case "5":
					$lsCantidad = " QUINIENTOS";
					break;
					
				case "6":
					$lsCantidad = " SEIS".$psPrefijo_Plural;
					break;
					
				case "7":
					$lsCantidad = " SETE".$psPrefijo_Plural;
					break;
					
				case "8":
					$lsCantidad = " OCHO".$psPrefijo_Plural;
					break;
					
				case "9":
					$lsCantidad = " NOVE".$psPrefijo_Plural;
					break;
					
				case "0":
					$lsCantidad = "";
					break;
			}
			return $lsCantidad;
		}
		
		protected function fsBuscar_Decena($piNumero, $piNumero_Siguiente = NULL)
		{
			$lsCantidad = "";
			switch($piNumero)
			{
				case "1":
					switch($piNumero_Siguiente)
					{
						case "0":
							$lsCantidad = " DIEZ ";
							break;
					}
					break;
				
				case "2":
					if($piNumero_Siguiente == "0")
					{
						$lsCantidad = " VEINTE ";
					}
					else
					{
						$lsCantidad = " VEINTE Y ";
					}
					break;
					
				case "3":
					$lsCantidad = " TREINTA ";
					break;
					
				case "4":
					$lsCantidad = " CUARENTA ";
					break;
					
				case "5":
					$lsCantidad = " CINCUENTA ";
					break;
					
				case "6":
					$lsCantidad = " SESENTA ";
					break;
					
				case "7":
					$lsCantidad = " SETENTA ";
					break;
					
				case "8":
					$lsCantidad = " OCHENTA ";
					break;
					
				case "9":
					$lsCantidad = " NOVENTA ";
					break;
					
				case "0":
					$lsCantidad = "";
					break;
			}
			
			if($piNumero_Siguiente != 0 and $piNumero != 0 and $piNumero != 1 and $piNumero != 2)
			{
				$lsCantidad .= " Y ";
			}
			
			return $lsCantidad;
		}
		
		protected function fsBuscar_Unidad($piNumero, $psPrefijo_Singular, $psPrefijo_Plural, $piNumero_Anterior = NULL, $piNumero_Siguiente = NULL)
		{
			$lsCantidad = "";
			if($piNumero_Anterior == 1)
			{
				switch($piNumero)
				{
					case "1":
						$lsCantidad = " ONCE ";
						break;
					case "2":
						$lsCantidad = " DOCE ";
						break;
					case "3":
						$lsCantidad = " TRECE ";
						break;
					case "4":
						$lsCantidad = " CATORCE ";
						break;
					case "5":
						$lsCantidad = " QUINCE ";
						break;
					case "6":
						$lsCantidad = " DIECISÉIS ";
						break;
					case "7":
						$lsCantidad = " DIECISIETE ";
						break;
					case "8":
						$lsCantidad = " DIECIOCHO ";
						break;
					case "9":
						$lsCantidad = " DIECINUEVE ";
						break;
				}
				
				if($psPrefijo_Singular == "MILLÓN")
				{
					$psPrefijo_Singular == "MILLONES";
				}
				$lsCantidad .= $psPrefijo_Singular;
			}
			else
			{
				switch($piNumero)
				{
					case "1":
						if($piNumero_Siguiente != NULL)
						{
							$lsCantidad = "UN ".$psPrefijo_Singular;
						}
						else
						{
							$lsCantidad = "UNO";
						}
						break;
					case "2":
						$lsCantidad = " DOS ".$psPrefijo_Plural;
						break;
					case "3":
						$lsCantidad = " TRES ".$psPrefijo_Plural;
						break;
					case "4":
						$lsCantidad = " CUATRO ".$psPrefijo_Plural;
						break;
					case "5":
						$lsCantidad = " CINCO ".$psPrefijo_Plural;
						break;
					case "6":
						$lsCantidad = " SEIS ".$psPrefijo_Plural;
						break;
					case "7":
						$lsCantidad = " SIETE ".$psPrefijo_Plural;
						break;
					case "8":
						$lsCantidad = " OCHO ".$psPrefijo_Plural;
						break;
					case "9":
						$lsCantidad = " NUEVE ".$psPrefijo_Plural;
						break;
					case "0":
						if($psPrefijo_Singular == "MIL")
						{
							$psPrefijo_Singular = "MIL";
						}
						else
						{
							$psPrefijo_Singular = "";
						}
						$lsCantidad = " ".$psPrefijo_Singular;
						break;
				}
			}
			return $lsCantidad;
		}
		
		/****************************************************************************************************
			Inicio del metodo fsFormatear_Montos(), permite dar formato a un monto
		****************************************************************************************************/
		protected function fsFormatear_Montos($pfMonto, $piMostrar_Cero = 0)
		{
			$pfMonto = number_format($pfMonto,2,',','');
			if($pfMonto != "0,00")
			{
				return $pfMonto;
			}
			elseif($piMostrar_Cero != 0)
			{
				return $pfMonto;
			}
			else
			{
				return "";
			}
		}
		/****************************************************************************************************
			Fin del metodo fsFormatear_Montos()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fpGuardar_Imagen(), permite guardar una imagen enviada desde un formulario
		****************************************************************************************************/
		protected function fpGuardar_Imagen($psNombre, $psTipo, $psFoto, $piAncho = 150, $piAlto = 200, $psDestino = "../vistas/formularios/imagenes/fotos/")
		{
			$lbHecho=false;
				
			$laSep=explode('image/',$psTipo); // Separamos image/
			$lsTipo=$laSep[1]; // Obtenemos el tipo de imagen que es;
			// Si el tipo de imagen a subir no es el mismo de los permitidos, retorna un mensaje de error.
			if($lsTipo == "gif" or $lsTipo == "pjpeg" or $lsTipo == "jpeg" or $lsTipo == "bmp" or $lsTipo == "png")
			{
				$psDestino .=  $psNombre.".jpeg";
				if(copy($psFoto,$psDestino) === false)
				{
					$lbHecho=false;
				}
				else
				{
					$loImagenRedimensionada = $this->fpRedimensionar_Imagen($psDestino, $piAncho, $piAlto, $lsTipo);
					if(unlink($psDestino))
					{
						imagejpeg($loImagenRedimensionada,$psDestino,100);
					}
					$lbHecho = true;
				}
			}
			else
			{
				$lbHecho="Formato";
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fpGuardar_Imagen()
		****************************************************************************************************/
		
		protected function fpRedimensionar_Imagen($psImagen_Original, $piAncho = 150, $piAlto = 200, $psTipo)
		{
			/* Esta función redimensiona un archivo JPG manteniendo
			* su radio de aspecto original dentro de los límites
			* $piAncho y $piAlto.
			* Parámetros:
			* $psImagen_Original: Nombre del archivo en formato JPG
			* a redimensionar.
			* $piAncho: Ancho máximo de la imágen redimensionada.
			* $piAlto: Alto máximo de la imágen redimensionada.
			* Devuelve una imágen en memoria con las proporciones
			* correctas.
			*/
			
			list($lfAncho, $lfAlto) = getimagesize($psImagen_Original);
			if($lfAncho < $piAncho and $lfAlto < $piAlto)
			{
				$piAncho = $lfAncho;
				$piAlto= $lfAlto;
			}
			// Obtenemos la relación de tamaño respecto
			// al ancho y alto máximo.
			$lfXEscale=$lfAncho/$piAncho;
			$lfYEscale=$lfAlto/$piAlto;
			
			// Cuando la escala en y es mayor que la escala en x
			// implica que debemos redimensionar en base al nuevo
			// alto.
			if ($lfYEscale>$lfXEscale)
			{
				$lfNuevo_Ancho = round($lfAncho * (1/$lfYEscale));
				$lfNuevo_Alto = round($lfAlto * (1/$lfYEscale));
			
			// Por el contrario si la escala en x es mayor o igual
			// debemos de redimensionar en base al nuevo ancho.
			}
			else
			{
				$lfNuevo_Ancho = round($lfAncho * (1/$lfXEscale));
				$lfNuevo_Alto = round($lfAlto * (1/$lfXEscale));
			}
			
			// Reservamos espacio en memoria para la nueva imágen
			$lsImagenNuevoTamano = imagecreatetruecolor($lfNuevo_Ancho, $lfNuevo_Alto);
			if($psTipo == "gif")
			{
				$lsImagenTemporal	= imagecreatefromgif($psImagen_Original);
			}
			elseif($psTipo == "pjpeg")
			{
				$lsImagenTemporal	= imagecreatefromjpeg($psImagen_Original);
			}
			elseif($psTipo == "jpeg")
			{
				$lsImagenTemporal	= imagecreatefromjpeg($psImagen_Original);
			}
			elseif($psTipo == "png")
			{
				$lsImagenTemporal	= imagecreatefrompng($psImagen_Original);
			}
			
			// Cargamos la imágen original y redimensionamos
			imagecopyresampled($lsImagenNuevoTamano, $lsImagenTemporal,0, 0, 0, 0, $lfNuevo_Ancho, $lfNuevo_Alto, $lfAncho, $lfAlto);
			
			// Devolvemos la nueva imágen redimensionada.
			return $lsImagenNuevoTamano;
		}
		
		protected function fsObtener_Equipo()
		{
			if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown'))
			{
				$lsIp = getenv('HTTP_CLIENT_IP');
			}	
			else if (getenv('HTTP_X_FORWARDED_FOR ') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR '), 'unknown'))
			{
				$lsIp = getenv('HTTP_X_FORWARDED_FOR ');
			}
			else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
			{
				$lsIp = getenv('REMOTE_ADDR');
			}
			else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
			{
				$lsIp = $_SERVER['REMOTE_ADDR'];
			}	
			else
			{
			   $lsIp = 'unknown';
			}
			return $lsIp;
		}
		
		protected function fpRegistro_Evento($psFormulario,$psTipo_Evento,$psObervacion,$psUsuario = NULL)
		{
			if($psUsuario != NULL)
			{
				$lsUsuario = $psUsuario;
			}
			else
			{
				$lsUsuario = trim($_SESSION["sogem_u_nusuario2"]);
			}
			$lsSql= "INSERT INTO evento
					(eve_usu_nombre,eve_formulario,eve_tipo_evento,eve_fecha,eve_ip,eve_observacion)
					VALUES
					('".$lsUsuario."','".$psFormulario."','".$psTipo_Evento."',NOW(),'".$this->fsObtener_Equipo()."','".$psObervacion."')";
			parent::fbEjecutar($lsSql);
		}
		
		/****************************************************************************************************
			Inicio del metodo fbConsultar_Stock(), permite consultar el stock de un producto y devolver en un
			arreglo el resultado (true o false) y la cantidad del mismo, los parametros solicitados son:
				piAlmacen:		Almacén donde debe estar el producto
				piPresentacion:	Presentacion base del producto
		****************************************************************************************************/
		protected function fbConsultar_Stock($piAlmacen, $piPresentacion)
		{
			$lsSql="SELECT sto_codigo, sto_cantidad
					FROM stock
					WHERE sto_cod_almacen = '".$piAlmacen."'
					AND sto_cod_presentacion = '".$piPresentacion."'
					AND sto_estatus = 'A'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$laRespuesta['lbResultado']	= true;
				$laRespuesta['liStock']		= $laArreglo['sto_codigo'];
				$laRespuesta['lfCantidad']	= $laArreglo['sto_cantidad'];
			}
			else
			{
				$laRespuesta['lbResultado']	= false;
			}
			parent::fpCierraFiltro($lrTb);
			return $laRespuesta;
		}
		/****************************************************************************************************
			Fin del metodo fbConsultar_Stock()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbRegistrar_Stock(), permite regisrar el stock de un producto en un almacen
		****************************************************************************************************/
		protected function fbRegistrar_Stock($pfCantidad, $piAlmacen, $piPresentacion)
		{
			$lbHecho = false;
			$lsSql= "INSERT INTO stock (sto_fecha_modificado, sto_cantidad, sto_cod_almacen,
					sto_cod_presentacion)VALUES(
					NOW(), '$pfCantidad', '$piAlmacen', '$piPresentacion') RETURNING sto_codigo";
			$lbHecho=parent::fbEjecutar($lsSql);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbRegistrar_Stock()
		****************************************************************************************************/
	
		/****************************************************************************************************
			Inicio del metodo fbModificar_Stock(), permite modificar el stock de un producto en un almacen
		****************************************************************************************************/
		protected function fbModificar_Stock($pfCantidad, $piStock)
		{
			$lbHecho = false;
			$lsSql= "UPDATE stock SET 
					sto_fecha_modificado = NOW(),
					sto_cantidad = '$pfCantidad'
					WHERE sto_codigo = '$piStock'";
			$lbHecho=parent::fbEjecutar($lsSql);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbModificar_Stock()
		****************************************************************************************************/
	
		/****************************************************************************************************
			Inicio del metodo fpRegistro_Kardex(), permite regisrar el kardex de una operación
				-psFecha:			Fecha de la operación
				-psDescripcion:		Breve observación de la operación
				-piCodigo_Sistema:	Código que corresponde a la tabla de sistema
				-piCodigo_Operacion:Código de la operación realizada
				-piAlmacen:			Almacen afectado
				-piUsuario:			Usuario que hace la operación
				-piEmpresa:			Código de la Empresa
		****************************************************************************************************/
		protected function fbRegistrar_Kardex($psFecha, $psDescripcion, $piCodigo_Sistema, 
							$piCodigo_Operacion, $piAlmacen, $piUsuario, $piEmpresa)
		{
			$lbHecho = false;
			$lsSql= "INSERT INTO kardex (kar_fecha_registro, kar_fecha, kar_descripcion, 
					kar_cod_sistema, kar_cod_sistema_codigo, kar_cod_almacen,
					kar_cod_usuario, kar_cod_empresa)VALUES(
					NOW(), '$psFecha', '$psDescripcion', 
					'$piCodigo_Sistema', '$piCodigo_Operacion', '$piAlmacen',
					'$piUsuario', '$piEmpresa') RETURNING kar_codigo";
			$lbHecho=parent::fbEjecutar($lsSql);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbRegistrar_Kardex()
		****************************************************************************************************/
	
		/****************************************************************************************************
			Inicio del metodo fbRegistrar_Kardex_Detalle(), permite regisrar el detalle de kardex de una 
			operación
				piTipo			: 1 Entrada | 2 Salida
				pfCantidad		: Cantidad de movimiento
				pfPrecio_Compra	: Precio de compra actual
				pfPrecio_Venta	: Precio de venta actual
				piPresentacion	: Presentacion base del producto
				piCodigo_Detalle: Codigo del detalle de la operacion que esta realizando (para hacer las 
								  busquedas mas rapida y facil)
		****************************************************************************************************/
		protected function fbRegistrar_Kardex_Detalle($piTipo, $pfCantidad, $pfPrecio_Compra, 
							$pfPrecio_Venta, $piPresentacion, $piKardex, $piCodigo_Detalle)
		{
			$lbHecho	= false;
			$lsSql= "INSERT INTO kardex_detalle (kde_tipo, kde_cantidad, kde_precio_compra, 
					kde_precio_venta, kde_cod_presentacion, kde_cod_kardex,
					kde_cod_sistema_codigo_detalle)VALUES(
					'$piTipo', '$pfCantidad', '$pfPrecio_Compra', 
					'$pfPrecio_Venta', '$piPresentacion', $piKardex,
					'$piCodigo_Detalle') RETURNING kde_codigo";
			$lbHecho=parent::fbEjecutar($lsSql);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbRegistrar_Kardex()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbConsultar_Unidad_Base_Producto(), permite consultar la presentacion base de 
			un producto
		****************************************************************************************************/
		protected function fbConsultar_Unidad_Base_Producto($piProducto)
		{
			$lsSql="SELECT pre_codigo, pre_precio_compra, pre_precio_venta 
					FROM presentacion
					WHERE pre_unidad_basica = 'S'
					AND pre_estatus = 'A'
					AND pre_cod_producto = '$piProducto'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$laRespuesta['lbResultado']		= true;
				$laRespuesta['liPresentacion']	= $laArreglo['pre_codigo'];
				$laRespuesta['lfPrecio_Compra']	= $laArreglo['pre_precio_compra'];
				$laRespuesta['lfPrecio_Venta']	= $laArreglo['pre_precio_venta'];
			}
			else
			{
				$laRespuesta['lbResultado']	= false;
			}
			parent::fpCierraFiltro($lrTb);
			return $laRespuesta;
		}
		/****************************************************************************************************
			Fin del metodo fbConsultar_Unidad_Base_Producto()
		****************************************************************************************************/
		
		/****************************************************************************************************
			ESTE MÉTODO ESTARÁ DISPONIBLE, PERO TRATARÉ DE NO USARLO A MENOS QUE SEA SUMAMENTE NECESARIO
			TRATARÉ DE ENVIAR TODOS LOS DATOS DESDE LA VISTA, AUNQUE SE BUSCA QUE SE ENVÍEN LOS MENOS 
			DATOS POSIBLES
			
			Inicio del metodo fbConsultar_Presentacion(), permite consultar la presentacion de un producto
		****************************************************************************************************/
		protected function fbConsultar_Presentacion($piPresentacion)
		{
			$lsSql="SELECT	pre_codigo, pre_factor, pre_precio_compra, pre_precio_venta, pre_unidad_basica,
							pro_codigo, pro_nombre,
							tpr_stock
					FROM presentacion
					JOIN producto		ON pro_codigo = pre_cod_producto
					JOIN tipo_producto	ON tpr_codigo = pro_cod_tipo_producto
					WHERE pre_codigo = '$piPresentacion'
					AND pre_estatus = 'A'";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$laRespuesta['lbResultado']			= true;
				$laRespuesta['liCodigo']			= $laArreglo['pre_codigo'];
				$laRespuesta['lfFactor']			= $laArreglo['pre_factor'];
				$laRespuesta['lfPrecio_Compra']		= $laArreglo['pre_precio_compra'];
				$laRespuesta['lfPrecio_Venta']		= $laArreglo['pre_precio_venta'];
				$laRespuesta['lsUnidad_Basica']		= $laArreglo['pre_unidad_basica'];
				$laRespuesta['lsProducto_Codigo']	= $laArreglo['pro_codigo'];
				$laRespuesta['lsProducto_Nombre']	= $laArreglo['pro_nombre'];
				$laRespuesta['lsManeja_Stock']		= $laArreglo['tpr_stock'];
			}
			else
			{
				$laRespuesta['lbResultado']	= false;
			}
			parent::fpCierraFiltro($lrTb);
			return $laRespuesta;
		}
		/****************************************************************************************************
			Fin del metodo fbConsultar_Presentacion()
		****************************************************************************************************/
		
		
		/****************************************************************************************************
			Inicio del metodo fbAnular_Kardex_Stock(), permite anular el kardex de una operación, primero 
			buscando el kardex que se va anular para tomar almacen y luego actualizar el stock de todos los 
			productos del detalle
		****************************************************************************************************/
		protected function fbAnular_Kardex_Stock($piCodigo_Sistema, $piCodigo_Operacion)
		{
			$lbHecho = false;
			$lsSql="SELECT	kde_codigo, kde_tipo, kde_cantidad, kde_cod_presentacion,
							kar_codigo, kar_cod_almacen
					FROM kardex_detalle
					JOIN kardex ON kar_codigo = kde_cod_kardex
					WHERE kar_cod_sistema = '$piCodigo_Sistema'
					AND kar_cod_sistema_codigo = '$piCodigo_Operacion'
					AND kde_estatus = 'A'
					AND kar_estatus = 'A'
					ORDER BY kde_codigo";
			$lrTb=parent::frFiltro($lsSql);
			while($laArreglo=parent::faProximo($lrTb))
			{
				$lsSql="UPDATE kardex_detalle SET
						kde_estatus = 'I'
						WHERE kde_codigo = '".$laArreglo['kde_codigo']."'";
				$lbHecho=parent::fbEjecutar($lsSql);
				if($lbHecho === true)
				{
					$liActualizacion_Detalle++;
				}
				else
				{
					$liError++;
				}
				
				$lsSql="UPDATE stock SET
						sto_cantidad = COALESCE((SELECT SUM(CASE kde_tipo 
													WHEN '1' THEN kde_cantidad
													ELSE (kde_cantidad * -1)
													END)
										FROM kardex_detalle 
										JOIN kardex	ON kar_codigo = kde_cod_kardex
										WHERE kar_cod_almacen = '".$laArreglo['kar_cod_almacen']."'
										AND kde_cod_presentacion = '".$laArreglo['kde_cod_presentacion']."'
										AND kde_estatus = 'A'),0)
										
						WHERE sto_cod_almacen = '".$laArreglo['kar_cod_almacen']."'
						AND sto_cod_presentacion = '".$laArreglo['kde_cod_presentacion']."'
						AND sto_estatus = 'A'";
				$lbHecho=parent::fbEjecutar($lsSql);
				if($lbHecho === false)
				{
					$liError++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			
			$lsSql="UPDATE kardex SET
					kar_estatus = 'I'
					WHERE kar_cod_sistema = '$piCodigo_Sistema'
					AND kar_cod_sistema_codigo = '$piCodigo_Operacion'";
			$lbHecho=parent::fbEjecutar($lsSql);
			if($lbHecho === false)
			{
				$liError++;
			}
			
			
			if($liError > 0)
			{
				$lbHecho = false;
			}
			else
			{
				$lbHecho = true;
			}
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbAnular_Kardex_Stock()
		****************************************************************************************************/
	
	}
?>
