<?php
	/*
	*	clsTabla.php
	*	
	*   Copyright 2021 Hernández^3 
	*
	*	Este programa es software libre, puede redistribuirlo y / o modificar
	*	Bajo los términos de la GNU Licensia Pública General(GPL) publicada por
	*	La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
	*      
	*	Este programa se distribuye con la esperanza de que sea útil,
	*	Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
	*	COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
	*/
	if(file_exists("../../modelos/clsFunciones.php"))
	{
		require_once("../../modelos/clsFunciones.php");
	}
	elseif(file_exists("../modelos/clsFunciones.php"))
	{
		require_once("../modelos/clsFunciones.php");
	}
	elseif(file_exists("modelos/clsFunciones.php"))
	{
		require_once("modelos/clsFunciones.php");
	}
	
	class clsTabla extends clsFunciones
	{
		private $aaPorcentaje;
		private $aaAlineacion;
		private $aaTitulos;
		private $aaTitles;
		private $asOnclick;
		private $asId;
		private $asId_Columna;
		private $abPie_Tabla;
		private $aaBotones;
		private $asFondo_Encabezado;
		private $aaVariables;
		
		function __construct()
		{
			$this->aaPorcentaje			=array();
			$this->aaPorcentaje[0]		='20%';
			$this->aaPorcentaje[1]		='60%';
			$this->aaPorcentaje[2]		='10%';
			
			$this->aaAlineacion			=array();
			$this->aaAlineacion[0]		='left';
			$this->aaAlineacion[1]		='left';
			$this->aaAlineacion[2]		='left';
			
			$this->aaTitulos			=array();
			$this->aaTitulos[0]			='Código';
			$this->aaTitulos[1]			='Nombre';
			$this->aaTitulos[2]			='Estatus';
			
			$this->aaTitles				=array();
			$this->aaTitles[0]			='Código';
			$this->aaTitles[1]			='Nombre';
			$this->aaTitles[2]			='Estatus';
			
			$this->asOnclick			='fpVerificar_Codigo';
			$this->asId					='tabListado';
			$this->asId_Columna			='a';
			$this->abPie_Tabla			=false;
			$this->aaBotones			='';
			$this->asFondo_Encabezado	='';
			$this->aaVariables			=array();
		}
		
		function __destruct()
		{
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetPorcentaje(), permite recibir los porcentajes para la tabla de los 
			listados en una cadena de texto divididos por coma (,) para ser transformada a un arreglo
		****************************************************************************************************/
		public function fpSetPorcentaje($psPorcentaje)
		{
			if(trim($psPorcentaje) != "")
			{
				$this->aaPorcentaje=explode(',',$psPorcentaje);
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetAlineacion(), permite recibir las alineaciones para la tabla de los 
			listados en una cadena de texto divididos por coma (,) para ser transformada a un arreglo
		****************************************************************************************************/
		public function fpSetAlineacion($psAlineacion)
		{
			if(trim($psAlineacion) != "")
			{
				$this->aaAlineacion=explode(',',$psAlineacion);
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetTitulos(), permite recibir las alineaciones para la tabla de los 
			listados en una cadena de texto divididos por coma (,) para ser transformada a un arreglo
		****************************************************************************************************/
		public function fpSetTitulos($psTitulos)
		{
			if(trim($psTitulos) != "")
			{
				$this->aaTitulos=explode(',',$psTitulos);
			}
		}
	
		/****************************************************************************************************
			Inicio del metodo fpSetTitles(), permite recibir los titles (nombres completos) de las columnas 
			la tabla de los listados en una cadena de texto divididos por coma (,) para ser transformada a 
			un arreglo
		****************************************************************************************************/
		public function fpSetTitles($psTitles)
		{
			if(trim($psTitles) != "")
			{
				$this->aaTitles=explode(',',$psTitles);
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetOnclick(), permite recibir el nombre de la función que se ejecutará al 
			momento de hacer click en las diferentes columnas de la tabla de los listados
		****************************************************************************************************/
		public function fpSetOnclick($psOnclick)
		{
			if(trim($psOnclick) != "")
			{
				$this->asOnclick=$psOnclick;
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetId(), permite recibir el id y nombre de la tabla de los listados
		****************************************************************************************************/
		public function fpSetId($psId)
		{
			if(trim($psId) != "")
			{
				$this->asId=$psId;
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetId_Columna(), permite recibir el id de las columnas de la tabla de los 
			listados
		****************************************************************************************************/
		public function fpSetId_Columna($psId_Columna)
		{
			if(trim($psId_Columna) != "")
			{
				$this->asId_Columna=$psId_Columna;
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetPie_Tabla(), permite recibir el parametro que indica si se imprime o no 
			un pie de página en la tabla
		****************************************************************************************************/
		public function fpSetPie_Tabla($pbPie_Tabla=false)
		{
			$this->abPie_Tabla=$pbPie_Tabla;
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetBotones(), permite recibir un arreglo con datos para la impresion de 
			objetos tales como botones, checkbox, radio button, etc, que permitiren hacer acciones con los 
			registros
		****************************************************************************************************/
		public function fpSetBotones($paBotones='')
		{
			if($paBotones != "")
			{
				$this->aaBotones=$paBotones;
			}
		}
		
		/****************************************************************************************************
			Inicio del metodo fpSetFondo_Encabezado(), permite recibir el nombre de la clase que se aplicará
			en el encabezado de la tabla
		****************************************************************************************************/
		public function fpSetFondo_Encabezado($psFondo_Encabezado)
		{
			if(trim($psFondo_Encabezado) != "")
			{
				$this->asFondo_Encabezado=$psFondo_Encabezado;
			}
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
			Inicio del metodo fpPintar(), permite imprimir una tabla dinamica 
		****************************************************************************************************/
		public function fpPintar($paDatos)
		{
			$lbHecho=true;
			$liN	=count($paDatos);			//cantidad filas de la tabla
			$liCol	=count($this->aaTitulos);	//cantidad filas de la columna 
			$liBot	=count($this->aaBotones);	//cantidad Botones 
			$lsTabla.='
					<table width="100%" class="table table-bordered table-striped table-condensed table-responsive table-hover" id="'.$this->asId.'" name="'.$this->asId.'">
						<thead>
							<tr class="'.$this->asFondo_Encabezado.'">';
				for ($liJ=0;$liJ<$liCol;$liJ++)
				{
					$lsTabla.='<th width="'.$this->aaPorcentaje[$liJ].'" class="titulo_listado" title="'.$this->aaTitles[$liJ].'">';
					$lsTabla.= $this->aaTitulos[$liJ];
					$lsTabla.='</th>';
				}
				$lsTabla.='
							</tr>
						</thead>
						<tbody>';
				for ($liI=0;$liI<$liN;$liI++)
				{
					$lsTabla.='
							<tr>';
					for ($liJ=0;$liJ<$liCol;$liJ++)
					{
						// $title = strpos($paDatos[$liI][$liJ],'>') !== false ? ' ' : 'title="'.$paDatos[$liI][$liJ].'"';
						$lsTabla.='
								<td align="'.$this->aaAlineacion[$liJ].'">';
						if($liJ != ($liCol-1))
						{//Espacio para los datos
							$lsTabla.= $paDatos[$liI][$liJ];
						}
						else
						{//Espacio para botones
							for($liB = 0 ; $liB < $liBot ; $liB++)
							{
								$onClick = '';
								if($this->aaBotones[$liB]['onClick'] != ''){
									$lsParametro = $this->aaBotones[$liB]['parametro'] != '' ? $this->aaBotones[$liB]['parametro'] : '';
									$onClick = ' onClick="'.$this->aaBotones[$liB]['onClick'].'('.$paDatos[$liI][$this->aaBotones[$liB]['posicion_valor']].$lsParametro.')"';
								}
								
								if($this->aaBotones[$liB]['tipo'] == 'boton')
								{
									$lbPintar = true;
									if($this->aaBotones[$liB]['valor_condicion'] != '' )
									{
										if($paDatos[$liI][$this->aaBotones[$liB]['posicion_condicion']] == $this->aaBotones[$liB]['valor_condicion'])
										{
											$lbPintar=true;
										}
										else
										{
											$lbPintar=false;
										}
									}

									if($this->aaBotones[$liB]['valor_condicion_multiple'] != '' )
									{
										if(strpos($this->aaBotones[$liB]['valor_condicion_multiple'], $paDatos[$liI][$this->aaBotones[$liB]['posicion_condicion']]) !== false)
										{
											$lbPintar=true;
										}
										else
										{
											$lbPintar=false;
										}
									}
									
									if($lbPintar == true){
										$lsTabla.='
											<button class="btn btn-sm margenes_botones '.$this->aaBotones[$liB]['color'].'" type="button" name="'.$this->aaBotones[$liB]['nombre'].'_'.$liI.'_'.$liB.'" id="'.$this->aaBotones[$liB]['nombre'].'_'.$liI.'_'.$liB.'" '.$onClick.'>
												<span class="'.$this->aaBotones[$liB]['icono'].'"></span> '.$this->aaBotones[$liB]['texto'].'
											</button>';
									}
								}
								
								if($this->aaBotones[$liB]['tipo'] == 'check')
								{
									$lsTabla.='
										<div class="">
											<input type="checkbox" name="'.$this->aaBotones[$liB]['nombre'].$liI.'" id="'.$this->aaBotones[$liB]['nombre'].$liI.'" value="'.$paDatos[$liI][$this->aaBotones[$liB]['posicion_valor']].'" title="'.$this->aaBotones[$liB]['title'].'" '.$onClick.'>';
										if($this->aaBotones[$liB]['texto'] != ''){
											$lsTabla.='
											<label for="'.$this->aaBotones[$liB]['nombre'].$liI.'">'.
												mb_convert_case($this->aaBotones[$liB]['texto'], MB_CASE_TITLE, "UTF-8").
											'</label>';
										}
									$lsTabla.='
										</div>';
								}
							}
						}
						$lsTabla.='
								</td>';
					}
					$lsTabla.='
							</tr>';
				}
				$lsTabla.='
						</tbody>
						<tfoot>
							<tr>';
				if($this->abPie_Tabla == true)
				{
					$lsTabla.='
								<td colspan="'.$liCol.'">'.
									$liN.' Filas
									<input type="hidden" name="txtNumero_Filas" id="txtNumero_Filas" value="'.$liN.'">
								</td>';
				}
				$lsTabla.='
							</tr>
						</tfoot>';
				
			$lsTabla.='
					</table>';
			$this->aaVariables['lsTabla_Principal'] = $lsTabla;
			return $lbHecho;
		}
		
		public function fpPintar_Modulos_Servicios($piCodigo = '')
		{
			parent::fpConectar();
			if($piCodigo != ''){
				$liI = 1;
				$lsSql="SELECT sro_cod_rol, sro_cod_servicio, sro_codigo, sro_estatus
						FROM servicio_rol 
						WHERE sro_cod_rol = '".$piCodigo."'
						ORDER BY sro_estatus
						";
				$lrTb=parent::frFiltro($lsSql);
				while($laArreglo=parent::faProximo($lrTb))
				{
					$paServicios[$liI]["Codigo"]			= $laArreglo["sro_codigo"];
					$paServicios[$liI]["Codigo_Servicio"]	= $laArreglo["sro_cod_servicio"];
					$paServicios[$liI]["Estatus"]			= $laArreglo["sro_estatus"];
					
					$liI++;
				}
				parent::fpCierraFiltro($lrTb);
			}
			
			$lbHecho = false;
			$lsResultado = '';
			$lsSql = "SELECT mod_codigo, mod_nombre FROM modulo ORDER BY mod_nombre";
			$lrTb = parent::frFiltro($lsSql);
			$liM = 1; //Contador de Modulos
			$liS = 1; //Contador de Servicios
			$liO = $liM+100;
			while($laRow = parent::faProximo($lrTb))
			{
				$lbHecho = true;
				/*--------- Encabezado del modulo, este cuadro tendra los servicios correspondientes al modulo 
				onclick="fpMostrar_Servicios('.$liM.')" ---------*/
				$lsResultado .= '
				
				<div class="col-12 col-sm-12 col-md-6">
					<div class="card">
						<div id="Capa_Modulo'.$liM.'" class="card-header">
							<h3 class="card-title">'.mb_convert_case($laRow["mod_nombre"], MB_CASE_TITLE, "UTF-8").'</h3>
							<a class="btn btn-primary float-right" data-toggle="collapse" href="#Capa_Servicio'.$liM.'" role="button" aria-expanded="false" aria-controls="collapseExample">
								<i class="fa fa-plus"></i>
							</a>
						</div>
						<div id="Capa_Servicio'.$liM.'" class="collapse">
							<div class="card-body row">
								
							
				';
						
				/*--------- Consulta para obtener los servicios correspondientes al modulo que genere la consulta anterior ---------*/	
				$lsSql2 = "SELECT ser_codigo, ser_nombre, ser_url
							FROM servicio 
							WHERE ser_cod_modulo ='".$laRow["mod_codigo"]."'
							ORDER BY ser_nombre";
				$lrTb2 = parent::frFiltro($lsSql2);
				while($laRow2 = parent::faProximo($lrTb2))
				{
					$lsAsignado	= "N";//Indica si el servicio esta asignado o no al rol
					$lsCheck	= "";
					$liI		= 1;
					$liE		= 0; //Guarda la posicion donde se encontro el codigo servicio rol para imprimirlo en el input oculto
					while($paServicios[$liI]["Codigo_Servicio"] != "")
					{
						if($laRow2["ser_codigo"] == $paServicios[$liI]["Codigo_Servicio"])
						{
							if($paServicios[$liI]["Estatus"] == 'A')
							{
								$lsCheck="checked";
								$lsAsignado	= "S";
							}
							$liE = $liI;
						}
						$liI++;
					}
					
					$lsResultado .= '
							<div class="col-12 col-sm-6">
								<input type="hidden" name="txtNombre_Servicio'.$liS.'" id="txtNombre_Servicio'.$liS.'" value="'.mb_convert_case($laRow2["ser_nombre"], MB_CASE_TITLE, "UTF-8").'">
								<input type="hidden" name="txtCod_Servicio_Rol'.$liS.'" id="txtCod_Servicio_Rol'.$liS.'" value="'.$paServicios[$liE]["Codigo"].'">
								<input type="hidden" name="txtAsignado'.$liS.'" id="txtAsignado'.$liS.'" value="'.$lsAsignado.'">
								
								<div class="form-group clearfix">
									<div class="icheck-primary d-inline">
										<input type="checkbox" name="chkServicio'.$liS.'" id="chkServicio'.$liS.'" value="'.$laRow2["ser_codigo"].'" title="'.$laRow2["ser_url"].'" '.$lsCheck.'>
										<label for="chkServicio'.$liS.'">'. mb_convert_case($laRow2["ser_nombre"], MB_CASE_TITLE, "UTF-8").'</label>
									</div>
								</div>
							</div>';
					$liO++;
					$liS++;
				}
				$lsResultado .= '
							</div>
						</div>
					</div>
				</div>';
				$liM++;
			}
			$lsResultado .= '
			<input type="hidden" name="txtCantidad_Modulos" id="txtCantidad_Modulos" value="'.$liM.'">
			<input type="hidden" name="txtCantidad_Servicios" id="txtCantidad_Servicios" value="'.$liS.'">';
			
			$this->aaVariables['lsTabla_Servicio'] = $lsResultado;
			return $lbHecho;
		}
		
		/****************************************************************************************************
			Inicio del metodo fsBuscar_Modulo(paPermisos, psServicio), permite buscar el modulo al que 
			pertenece un servicio.
			Parametros: 
				paPermisos	= Arreglo con los modulos y servicios que tiene asignado el rol del usuario.
				psArchivo	= Archivo (vista) que se esta mostrando al usuario.
		****************************************************************************************************/
		private function fsBuscar_Modulo($paPermisos, $psArchivo)
		{
			$lsModulo	= "";
			$lbEntrar	= true;
			$liI		= 1;
			while($paPermisos[$liI]['lsModulo'] != "" and $lbEntrar === true)
			{
				$liO=1;
				while($paPermisos[$liI][$liO]['lsServicio'] != "")
				{
					if($paPermisos[$liI][$liO]['lsUrl'] == $psArchivo)
					{
						$lbEntrar = false;
						$lsModulo = $paPermisos[$liI]['lsModulo'];
					}
					$liO++;
				}
				$liI++;
			}
			return $lsModulo;
		}
		
		/****************************************************************************************************
			Inicio del metodo fpMenu(paPermisos, psServicio, pbInicio), permite imprimir el menú del usuario,
			es decir, los módulos y servicios a los que tiene permisos.
			Parametros: 
				paPermisos	= Arreglo con los modulos y servicios que tiene asignado el rol del usuario.
				psServicio	= Servicio (vista) que se esta mostrando al usuario.
				pbInicio	= Indica si se imprime o no la opción de inicio, ya que por seguridad algunas 
							  paginas no debe mostrar esta opción. este parametro es opcional
		****************************************************************************************************/
		public function fpMenu($paPermisos, $psArchivo, $pbInicio = true)
		{
			// Función que busca el modulo de un servicio, para marcarlo como activo
			// y se desplegue el menú
			$lsModulo = $this->fsBuscar_Modulo($paPermisos, $psArchivo);
			$lsActivo = "";
			if($pbInicio === true)
			{
				if($psArchivo == "visInicio.php")
				{
					$lsActivo	= "menu-open";
					$lsActivo2= "active";
				}
				
				echo '
					<li class="nav-item '.$lsActivo.'">
						<a href="visInicio.php" class="nav-link '.$lsActivo2.'">
							<i class="nav-icon fas fa-home"></i>
							<p>Inicio</p>
						</a>
					</li>';
			}
			$liI=1;
			while($paPermisos[$liI]['lsModulo'] != "")
			{
				$lsActivo	= "";
				$lsActivo2= "";
				if($paPermisos[$liI]['lsModulo'] == $lsModulo)
				{
					$lsActivo	= "menu-open";
					$lsActivo2= "active";
				}
					
				echo '
					<li class="nav-item '.$lsActivo.'">
						<a href="#" class="nav-link '.$lsActivo2.'">
							<i class="nav-icon '.$paPermisos[$liI]['lsIcono'].'"></i>
							<p>'.
								mb_convert_case($paPermisos[$liI]['lsModulo'], MB_CASE_TITLE, "UTF-8").'
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">';
						
					$liO=1;
					while($paPermisos[$liI][$liO]['lsUrl'] != "")
					{
						$lsActivo = "";
						$lsTarget = "";
						if($paPermisos[$liI][$liO]['lsAbrir_Especial'] == 'S')
						{
							$lsTarget="target='_new'";
						}
						
						if($paPermisos[$liI][$liO]['lsUrl'] == $psArchivo)
						{
							$lsActivo = 'active';
						}
							
						echo '
							<li class="nav-item">
								<a href="'.$paPermisos[$liI][$liO]['lsUrl'].'" class="nav-link '.$lsActivo.'" '.$lsTarget.'>
									<i class="nav-icon '.$paPermisos[$liI][$liO]['lsIcono'].'"></i>
									<p>'.mb_convert_case($paPermisos[$liI][$liO]['lsServicio'], MB_CASE_TITLE, "UTF-8").'</p>
								</a>
							</li>';
						$liO++;
					}
					echo '
						</ul>
					</li>';
				$liI++;
			}
			
			echo '
				<li class="nav-item">
					<a href="visSalir.php" class="nav-link">
						<i class="nav-icon fas fa-sign-out-alt"></i>
						<p>Cerrar Sesión</p>
					</a>
				</li>';
		}
		
		/****************************************************************************************************
			Inicio del metodo fpModulo_Vista(paPermisos, psServicio), permite imprimir el módulo y la vista 
			actual.
			Parametros: 
				paPermisos	= Arreglo con los modulos y servicios que tiene asignado el rol del usuario.
				psServicio	= Servicio (vista) que se esta mostrando al usuario.
		****************************************************************************************************/
		public function fpModulo_Vista($paPermisos, $psArchivo)
		{
			$liI = 1;
			$liM = 0;
			$liS = 0;
			$lbEntrar = true;
			while($paPermisos[$liI]['lsModulo'] != "" and $lbEntrar === true)
			{
				$liO = 1;
				while($paPermisos[$liI][$liO]['lsUrl'] != "" and $lbEntrar === true)
				{
					if($paPermisos[$liI][$liO]['lsUrl'] == $psArchivo)
					{
						$liM = $liI;
						$liS = $liO;
						$lbEntrar = false;
					}
					$liO++;
				}
				$liI++;
			}
			
			$laRespuesta['lsModulo']			= mb_convert_case($paPermisos[$liM]['lsModulo'], MB_CASE_TITLE, "UTF-8");
			$laRespuesta['lsServicio_Largo']	= mb_convert_case($paPermisos[$liM][$liS]['lsServicio_Largo'], MB_CASE_TITLE, "UTF-8");
			$laRespuesta['lsServicio_Corto']	= mb_convert_case($paPermisos[$liM][$liS]['lsServicio'], MB_CASE_TITLE, "UTF-8");
			return $laRespuesta;
		}
		
		/****************************************************************************************************
			Inicio del metodo fiPintar_Listado_Roles(), Permite pintar todos los roles del sistema
		****************************************************************************************************/
		public function fiPintar_Listado_Roles()
		{
			$lbHecho = false;
			$lsResultado = '';
			parent::fpConectar();
			$lsSql = "SELECT rol_codigo, rol_nombre FROM rol WHERE rol_estatus = 'A' ORDER BY rol_nombre";
			$lrTb = parent::frFiltro($lsSql);
			$liI = 1; //Contador del rol
			if(parent::fiNum_Registros($lrTb) > 0)
			{
				$lbHecho=true;
				while($laArreglo = parent::faProximo($lrTb))
				{
					
					$lsResultado .= '
							<div class="col-12 col-sm-4">
								<div class="form-group clearfix">
									<div class="icheck-primary d-inline">
										<input type="checkbox" name="chkRol'.$liI.'" id="chkRol'.$liI.'" value="'.$laArreglo["rol_codigo"].'">
										<label for="chkRol'.$liI.'">'. mb_convert_case($laArreglo["rol_nombre"], MB_CASE_TITLE, "UTF-8").'</label>
									</div>
								</div>
							</div>';
					$liI++;
				}
					
				// $lsResultado .= '
						// <div class="col-12">
							// <div class="form-group clearfix">
								// <div class="icheck-primary d-inline">
									// <input type="checkbox" name="chkSeleccionar_Todo" id="chkSeleccionar_Todo" value="" onchange="fpSeleccionar_Todo(this)">
									// <label for="chkSeleccionar_Todo">Seleccionar Todo</label>
								// </div>
							// </div>
						// </div>';
			}
			
			$lsResultado .= '<input type="hidden" name="txtCantidad_Roles" id="txtCantidad_Roles" value="'.$liI.'">';
			
			$this->aaVariables['lsTabla_Roles'] = $lsResultado;
			return $lbHecho;
		}
		
		/****************************************************************************************************
			Inicio del metodo fpPaginar(), Permite realizar la paginación de un listado
		****************************************************************************************************/
		public function fpPaginar($piCantidad_Paginas, $piPagina_Actual, $psOnclick_Resultados = 'fpCambiar_Pagina')
		{
			$lbHecho		= false;
			$lsTabla		= '';
			$lbBoton_Inicio	= false;
			$lbBoton_Final	= false;
			if ($piCantidad_Paginas > 1)
			{
				//Si el total de paginas es mayor a 5, se verifica otros factores
				if($piCantidad_Paginas > 5){
					if($piPagina_Actual >= 4){
						$liInicio = $piPagina_Actual - 2;
						if(intval($piPagina_Actual + 3) > $piCantidad_Paginas){
							$liFin = $piPagina_Actual + ($piCantidad_Paginas - $piPagina_Actual);
						}else{
							$liFin = $piPagina_Actual + 2;
						}
						$lbBoton_Inicio= true;
					}else{
						$liInicio = 1;
						$liFin = 5;
					}
					if(intval($piPagina_Actual + 2) < $piCantidad_Paginas){
						$lbBoton_Final=true;
					}
				}else{
					//Si el total de paginas es menor a 5, se pinta solo la cantidad correspondiente
					$liInicio = 1;
					$liFin = $piCantidad_Paginas;
				}

				$lbHecho=true;
				$lsTabla.='
				<div class="row form-group">
					<div class="btn-group col-3 col-sm-2">';
				if($lbBoton_Inicio == true)
				{
					$lsTabla.='<button type="button" name="btnPosicion_Inicio" id="btnPosicion_Inicio" class="btn btn-info" onclick="'.$psOnclick_Resultados.'(1)"> Primero </button>';
				}
				$lsTabla.='
					</div>
					
					<div class="btn-group col-6 col-sm-8 justify-content-center">
						<div class="">';
					for($liI = $liInicio; $liI <= $liFin ; $liI++)
					{
						if ($piPagina_Actual == $liI)
						{
							$lsTabla.='<button type="button" name="btnPosicion'.$liI.'" id="btnPosicion'.$liI.'" class="btn btn-outline-info active"> '.$liI.' </button>';
						}
						else
						{
							$lsTabla.='<button type="button" name="btnPosicion'.$liI.'" id="btnPosicion'.$liI.'" class="btn btn-outline-info" onclick="'.$psOnclick_Resultados.'('.$liI.')"> '.$liI.' </button>';
						}
					}
					$lsTabla.='
						</div>
					</div>';
				
				$lsTabla.='
					<div class="btn-group col-3 col-sm-2">';
					if($lbBoton_Final == true)
					{
						$lsTabla.='<button type="button" name="btnPosicion_Final" id="btnPosicion_Final" class="btn btn-info" onclick="'.$psOnclick_Resultados.'('.$piCantidad_Paginas.')"> Ultmo </button>';
					}
					$lsTabla.='
					</div>';
			}
			$this->aaVariables['lsTabla_Paginacion'] = $lsTabla;
			return $lbHecho;
		}
		
	}
?>
