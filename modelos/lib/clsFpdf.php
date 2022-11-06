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
	session_start();
	class clsFpdf extends FPDF
	{
		private $aiAngulo;
		
		//Cabecera de página
		public function Header()
		{
			if($_SESSION["sogem_laConfiguracion"]["txtTipo_Encabezado"] == 'I')
			{
				$this->Image('../formularios/imagenes/logo_reporte.jpg',10,8,260,18);
			}
			else
			{
				$this->SetFont("Arial","B",14);
				$this->MultiCell(0,5,utf8_decode(str_replace('\n\r', '', $_SESSION["sogem_laConfiguracion"]["txtTexto_Encabezado"])), 0, $_SESSION["sogem_laConfiguracion"]["txtAlineacion_Encabezado"]);
			}
			$this->SetY(27);
		}

		//Pie de página
		public function Footer()
		{
			//Posición: a 2 cm del final
			$this->SetY(-15);
			//Arial italic 6
			$this->SetFont("Arial","I",6);
			//Dirección
			$this->Cell(0,4,utf8_decode(mb_convert_case($_SESSION["sogem_laConfiguracion"]["txtDireccion"], MB_CASE_TITLE, "UTF-8")." R.I.F.: ".$_SESSION["sogem_laConfiguracion"]["txtRif"]),0,1,"C");
			$this->Cell(0,4,utf8_decode("Teléfono ".$_SESSION["sogem_laConfiguracion"]["txtTelefono"]),0,1,"C");
			date_default_timezone_set('America/Caracas');
			//Fecha y Número de Página
			$lcFecha=date("d/m/Y  h:i a", time());
			$this->Cell(0,3,utf8_decode($lcFecha),0,0,"C");
		}
		
		public function fbSalto_Pagina($piAltura)
		{
			//If the height h would cause an overflow, add a new page immediately
			//Si la altura h provocaría un desbordamiento, añadir una nueva página de inmediato
			$lbHecho = false;
			if($this->GetY()+$piAltura > $this->PageBreakTrigger)
			{
				$this->AddPage("P","Letter");
				$lbHecho = true;
			}
			return $lbHecho;
		}
		
		function fpRotar($piAngulo,$piX=-1,$piY=-1)
		{
			if($piX==-1)
			{
				$piX=$this->x;
			}
			
			if($piY==-1)
			{
				$piY=$this->y;
			}
			if($this->aiAngulo!=0)
			{
				$this->_out('Q');
			}
			$this->aiAngulo=$piAngulo;
			if($piAngulo!=0)
			{
				$piAngulo*=M_PI/180;
				$c=cos($piAngulo);
				$s=sin($piAngulo);
				$cx=$piX*$this->k;
				$cy=($this->h-$piY)*$this->k;
				$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
			}
		}
		
		function _endpage()
		{
			if($this->aiAngulo!=0)
			{
				$this->aiAngulo=0;
				$this->_out('Q');
			}
			parent::_endpage();
		}
		
		function fpRotar_Texto($piX,$piY,$psTexto,$piAngulo)
		{
			//Texto girado en torno a su origen
			$this->fpRotar($piAngulo,$piX,$piY);
			$this->Text($piX,$piY,$psTexto);
			$this->fpRotar(0);
		}
		
		function fpRotar_Imagen($psArchivo,$piX,$piY,$w,$h,$piAngulo)
		{
			//Imagen gira alrededor de su esquina superior izquierda
			$this->fpRotar($piAngulo,$piX,$piY);
			$this->Image($psArchivo,$piX,$piY,$w,$h);
			$this->fpRotar(0);
		}
	}
?>