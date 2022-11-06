<?php
   /*
   *      clsCombo.php
   *      
   *      Copyright 2021 Hernández^3
   *      
   *      Este programa es software libre, puede redistribuirlo y / o modificar
   *      Bajo los términos de la GNU LicenciaPública General(GPL) publicada por
   *      La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
   *      
   *      Este programa se distribuye con la esperanza de que sea útil,
   *      Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
   *      COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
   */
	require_once("clsFunciones.php");
	
	class clsCombo extends clsFunciones
	{
		private $asSql;
		private $asCampo1;
		private $asCampo2;
		private $aaData;
		
		public function __construct()
		{
		}
		
		public function __destruct()
		{
		}
		
		public function fpSetSql($psSql)
		{
			$this->asSql=$psSql;
		}
		
		public function fpSetCampo1($psCampo1)
		{
			$this->asCampo1=$psCampo1;
		}
		
		public function fpSetCampo2($psCampo2)
		{
			$this->asCampo2=$psCampo2;
		}
		
		public function fpSetData($paData)
		{
			$this->aaData=$paData;
		}
		
		public function fbGenerar($psSeleccionado = NULL)
		{
			$lbResultado=false;
			$this->fpConectar();
			$lrTb=$this->frFiltro($this->asSql);
			while ($laArreglo=$this->faProximo($lrTb))
			{
				$lbResultado=true;
				$lsCampo1=trim($laArreglo[$this->asCampo1]);
				$lsCampo2=$laArreglo[$this->asCampo2];
				
				$liI = 1;
				$lsData = '';
				while($this->aaData[$liI]['lsNombre'] != ''){
					$lsData = ' data-'.$this->aaData[$liI]['lsNombre'].'="'.$laArreglo[$this->aaData[$liI]['lsCampo']].'"';
					$liI++;
				}
				
				if(trim($lsCampo1) == trim($psSeleccionado))
				{
					print "<option value='$lsCampo1' $lsData selected='selected' >$lsCampo2</option>";
				}
				else
				{
					print "<option value='$lsCampo1' $lsData >$lsCampo2</option>";
				}
			}
			$this->fpCierraFiltro($lrTb);
			$this->fpDesconectar();
			return $lbResultado;
		}
   }
?>
