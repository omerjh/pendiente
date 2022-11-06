<?php
require('clsFpdf.php');
class PDF_MC_Table extends clsFpdf
{
var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data, $piAltura = 6, $piInterlineado = 5, $piMargen_Superior = NULL, $pbBorder = true)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h= $piAltura * $nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h, $piMargen_Superior);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		
		if($pbBorder === true)
		{
			//Draw the border
			$this->Rect($x,$y,$w,$h);
		}
		//Print the text
		$this->MultiCell($w,$piInterlineado,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h, $psPagina = "Letter" , $piMargen_Superior = NULL)
{
	//If the height h would cause an overflow, add a new page immediately
	//Si la altura h provocaría un desbordamiento, añadir una nueva página de inmediato
	$lbHecho = false;
	if($this->GetY()+$h>$this->PageBreakTrigger)
	{
		$this->AddPage($this->CurOrientation, $psPagina);
		if($piMargen_Superior != NULL )
		{
			$this->SetY($piMargen_Superior);
		}
		$lbHecho = true;
	}
	return $lbHecho;
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

}
?>
