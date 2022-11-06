<?php
	/*
	*	clsSeguridad.php
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
	
	class clsSeguridad
	{
		
		function __construct()
		{
		}
		
		function __destruct()
		{
		}
		
		public function fbPermiso($paPermisos, $psVista)
		{
			$lbPermiso = false;
			$liI=1;
			while($paPermisos[$liI]['lsModulo'] != "")
			{
				$liO=1;
				while($paPermisos[$liI][$liO]['lsUrl'] != "")
				{
					if($paPermisos[$liI][$liO]['lsUrl'] == $psVista)
					{
						$lbPermiso = true;
					}
					$liO++;
				}
				$liI++;
			}
			return $lbPermiso;
		}
	}
?>