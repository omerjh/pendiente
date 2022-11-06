<?php
   /*
	*	clsFpdf.php
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
	require_once("fpdf/fpdf.php");
	require_once("clsTabla.php");
	class clsFpdf extends FPDF
	{
		//Cabecera de página
		public function Header()
		{
			$loTabla	= new clsTabla();
			$laMatriz	= $loTabla->faBuscar_Configuracion();
			//Logo
			$this->Image('../formularios/imagenes/'.$laMatriz["txtLogo"],10,8,260,25);
			$this->SetY(35);			
		}

		//Pie de página
		public function Footer()
		{
			$loTabla	= new clsTabla();
			$laMatriz	= $loTabla->faBuscar_Configuracion();
			//Posición: a 2 cm del final
			$this->SetY(-20);
			//Arial italic 6
			$this->SetFont("Arial","I",6);
			//Dirección
			$this->Cell(0,4,utf8_decode($laMatriz["txtNombre"]." - ".$laMatriz["txtDireccion"].". R.I.F.: ".$laMatriz["txtRif"]),0,1,"C");
			//Dirección Pagina Web
			$this->Cell(0,4,utf8_decode("Zona postal 3301 . Teléfono: ".$laMatriz["txtTelefono"]),0,1,"C");
			date_default_timezone_set('America/Caracas');
			//Fecha y Número de Página
			$lcFecha=date("d/m/Y  h:i a", time());
			$this->Cell(0,3,utf8_decode("Página ").$this->PageNo()."/{nb}  -  ".$lcFecha,0,0,"C");
		}
		
		public function fbSalto_Pagina($piAltura)
		{
			//If the height h would cause an overflow, add a new page immediately
			//Si la altura h provocaría un desbordamiento, añadir una nueva página de inmediato
			$lbHecho = false;
			if($this->GetY()+$piAltura > $this->PageBreakTrigger)
			{
				$this->AddPage("L","Letter");
				$lbHecho = true;
			}
			return $lbHecho;
		}
	}
/*
$liY= $loPdf->GetY();
$liX= $loPdf->GetX();

$loPdf->MultiCell(30,6,utf8_decode("PROGRAMA: ".$laMatriz["lsPrograma"]),1,"L");
//$liY2= $loPdf->GetY();

$loPdf->SetY($liY);
$loPdf->SetX($liX+30);
*/
?>
