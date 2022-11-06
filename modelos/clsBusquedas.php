<?php
	/*
	*	clsBusquedas.php
	*	
	*   Copyright 2018 Hernández^2
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
	class clsBusquedas extends clsFunciones
	{
		private $aaVariables;
		
		public function __construct()
		{
			parent::fpConectar();
			$this->aaVariables = array();
		}
		
		public function __destruct()
		{
		}
		
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
			Inicio del metodo fbBuscar_Disponibilidad_Usuario(), permite verificar si el nombre de usuario 
			está disponible para ser usado.
		****************************************************************************************************/
		public function fbBuscar_Disponibilidad_Usuario($psNombre, $piCodigo = NULL)
		{
			$lsCripterio = "";
			if($piCodigo != NULL)
			{
				$lsCripterio = " AND usu_codigo != '$piCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT usu_nombre
					FROM usuario
					WHERE usu_nombre = '$psNombre'
					$lsCripterio";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Inmueble()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Conductor(), permite verificar si el D.N.I. de una persona esta 
			registrado en la tabla de conductores.
		****************************************************************************************************/
		public function fbBuscar_Conductor($piDni, $piCodigo = NULL)
		{
			$lsCripterio = "";
			if($piCodigo != NULL)
			{
				$lsCripterio = " AND con_codigo != '$piCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT con_codigo, con_nombre
					FROM conductor
					WHERE con_dni = '$piDni'
					$lsCripterio";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$lbEnc=true;
				$this->aaVariables["txtCodigo"]	=trim($laArreglo["con_codigo"]);
				$this->aaVariables["txtNombre"]	=trim($laArreglo["con_nombre"]);
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Inmueble()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Persona(), permite verificar si el D.N.I. de una persona esta 
			registrado en la tabla de Personas.
		****************************************************************************************************/
		public function fbBuscar_Persona($piDni, $piCodigo = NULL)
		{
			$lsCripterio = "";
			if($piCodigo != NULL)
			{
				$lsCripterio = " AND per_codigo != '$piCodigo'";
			}
			$lbEnc=false;
			$lsSql="SELECT per_nombre
					FROM persona
					WHERE per_dni = '$piDni'
					$lsCripterio";
			$lrTb=parent::frFiltro($lsSql);
			if($laArreglo=parent::faProximo($lrTb))
			{
				$lbEnc=true;
			}
			parent::fpCierraFiltro($lrTb);
			return $lbEnc;
		}
		/****************************************************************************************************
			Fin del metodo fbBuscar_Inmueble()
		****************************************************************************************************/
		
		/****************************************************************************************************
			Inicio del metodo fbBuscar_Producto(), permite listar los productos registrados en la base de datos 
			por medio de unos cripterios de busqueda
		****************************************************************************************************/
		public function fbBuscar_Producto($piCodigo_Producto, $piCodigo_Vendedor)
		{
			$lbHecho		= false;
			$lsCripterio	= "";
			$liExistencia	= 0;
			$lsSql="SELECT pro_codigo, pro_nombre, pro_existencia, pro_precio_venta,
					lin_nombre, prv_cantidad_asignada, prv_existencia, prv_codigo,
					TO_CHAR(prv_fecha_desde, 'DD-MM-YYYY') AS prv_fecha_desde,
					TO_CHAR(prv_fecha_hasta, 'DD-MM-YYYY') AS prv_fecha_hasta
					
					FROM producto 
					JOIN linea					ON lin_codigo = pro_cod_linea
					LEFT JOIN producto_vendedor	ON prv_cod_producto = pro_codigo AND prv_cod_vendedor = '$piCodigo_Vendedor'
					WHERE pro_codigo != '0'
					AND pro_codigo = '$piCodigo_Producto'
					ORDER BY pro_codigo";
			$liI=0;
			$lrTb=parent::frFiltro($lsSql);
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho = true;
				while($laArreglo=parent::faProximo($lrTb))
				{
					if($laArreglo["prv_fecha_desde"] != "")
					{
						if(strtotime(date("d-m-Y")) >= strtotime(date($laArreglo["prv_fecha_desde"])) and strtotime(date("d-m-Y")) <= strtotime(date($laArreglo["prv_fecha_hasta"])) and intval($laArreglo["prv_existencia"]) < intval($laArreglo["pro_existencia"]))
						{
							$liExistencia = $laArreglo["prv_existencia"];
						}
						else
						{
							$liExistencia = $laArreglo["pro_existencia"];
						}
					}
					else
					{
						$liExistencia = $laArreglo["pro_existencia"];
					}
					
					/*$this->aaVariables[$liI][0]=$laArreglo["pro_codigo"];
					$this->aaVariables[$liI][1]=mb_convert_case($laArreglo[""], MB_CASE_TITLE, "UTF-8");
					$this->aaVariables[$liI][2]=;
					$this->aaVariables[$liI][3]=;
					//$this->aaVariables[$liI][3]=$laArreglo["pro_precio_venta"];
					$this->aaVariables[$liI][4]=mb_convert_case($laArreglo["lin_nombre"], MB_CASE_TITLE, "UTF-8");
					*/
					$this->aaVariables["txtNombre"]				=trim($laArreglo["pro_nombre"]);
					$this->aaVariables["txtPrecio_Sin_Formato"]	=trim($laArreglo["pro_precio_venta"]);
					$this->aaVariables["txtPrecio_Con_Formato"]	=trim(number_format($laArreglo["pro_precio_venta"],2,',','.'));
					$this->aaVariables["txtExistencia"]			=$liExistencia;
					$this->aaVariables["txtProducto_Vendedor"]	=trim($laArreglo["prv_codigo"]);
					$liI++;
				}
			}
			parent::fpCierraFiltro($lrTb);
			return $lbHecho;
		}
		/****************************************************************************************************
			Fin del metodo fbListar()
		****************************************************************************************************/
		
	}
?>
