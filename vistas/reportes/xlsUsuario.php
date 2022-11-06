<?php
	/*
	*	xlsUsuario.php
	*
	*	Copyright 2020 Hernández^3
	*
	*	Este programa es software libre, puede redistribuirlo y / o modificar
	*	Bajo los términos de la GNU LicenciaPública General(GPL) publicada por
	*	La Fundación de Software Libre(FSF), bien de la versión 2 o cualquier versión posterior.
	*
	*	Este programa se distribuye con la esperanza de que sea útil,
	*	Pero SIN NINGUNA GARANTÍA, incluso sin la garantía implícita de
	*	COMERCIALIZACIÓN o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
	*/
    /*error_reporting(E_ALL);
	ini_set('display_errors','1');*/
	setlocale(LC_ALL,"es_VE.UTF8");
	set_time_limit(0);
	ini_set('memory_limit', '512M');

	if(array_key_exists('txtFiltro_Operacion',$_POST) === false or $_POST["txtFiltro_Operacion"] == NULL)
	{
		echo "<script languaje='javascript' type='text/javascript'> alert('Disculpe, este reporte necesita datos que no fueron suministrados, por lo tanto se va a cerrar esta ventana');window.close();</script>";
		exit();
	}
	else
	{
	}
	
	require_once("../../modelos/clsUsuario.php");
	require_once("../../modelos/lib/PHPExcel.php");

	$loXls		= new PHPExcel();
	$loUsuario	= new clsUsuario();
	if($loUsuario->fbListar_Excel($_POST) === true)
	{
		$laMatriz = $loUsuario->faGetVariables();
	}
	else
	{
		echo "<script languaje='javascript' type='text/javascript'> alert('No hay nada que reportar');window.close();</script>";
		exit();
	}
	
	$loXls->getProperties()
			->setCreator("Omer Hernandez")
			->setLastModifiedBy("Omer Hernandez")
			->setTitle("Listado de Usuarios")
			->setSubject("Listado de Usuarios")
			->setDescription("Documento generado con PHPExcel")
			->setKeywords("Listado de Usuarios")
			->setCategory("Reportes");
	
	$liA = 0;
		
	//Unión de celdas
	//$loXls->setActiveSheetIndex($liA)->mergeCells('A1:F1');
	
	//Se alÍnea al centro
	$loXls->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	//Se coloca en negritas
	$loXls->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
	
	//Bordes
	$loXls->getActiveSheet()->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
	//Color de Fondo
	$loXls->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$loXls->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setRGB('E0E0E0');
	
	
	// Se agregan los titulos del reporte
	$loXls->setActiveSheetIndex($liA)
					->setCellValue('A1', "CÓDIGO")
					->setCellValue('B1', "NOMBRE")
					->setCellValue('C1', "NOMBRE DE PERSONA")
					->setCellValue('D1', "ROL")
					->setCellValue('E1', "ESTADO")
					->setCellValue('F1', "PUNTO DE VENTA ASIGNADO")
					;
	$liLinea_Actual = 2; //Indica la ultima linea del encabezado
	$liE = 0;
	while($laMatriz[$liE]['liCodigo'] != "")
	{
		$loXls->setActiveSheetIndex($liA)->setCellValue('A'.$liLinea_Actual, $laMatriz[$liE]['liCodigo']);
		$loXls->setActiveSheetIndex($liA)->setCellValue('B'.$liLinea_Actual, $laMatriz[$liE]['lsNombre']);
		$loXls->setActiveSheetIndex($liA)->setCellValue('C'.$liLinea_Actual, $laMatriz[$liE]['lsNombre_Persona']);
		$loXls->setActiveSheetIndex($liA)->setCellValue('D'.$liLinea_Actual, $laMatriz[$liE]['lsRol']);
		$loXls->setActiveSheetIndex($liA)->setCellValue('E'.$liLinea_Actual, $laMatriz[$liE]['lsEstatus']);
		$loXls->setActiveSheetIndex($liA)->setCellValue('F'.$liLinea_Actual, $laMatriz[$liE]['lsPunto_Venta']);
		
		//Bordes
		$loXls->getActiveSheet()->getStyle('A'.$liLinea_Actual.':E'.$liLinea_Actual)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
		$liE++;
		$liLinea_Actual++;
	}
	
	// Se asigna el nombre a la hoja
	$lsTitulo_Hoja = 'Listado de Usuarios';
	$loXls->getActiveSheet()->setTitle($lsTitulo_Hoja);
	 
	$liA++;
	
	//Iniciando los tamaños de las columnas
	$loXls->getActiveSheet()->getColumnDimension('A')->setWidth(14);
	$loXls->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$loXls->getActiveSheet()->getColumnDimension('C')->setWidth(36);
	$loXls->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$loXls->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	$loXls->getActiveSheet()->getColumnDimension('F')->setWidth(40);
	
	
	// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
	$loXls->setActiveSheetIndex(0);
	
	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Listado-de-usuarios.xls"');
	header('Cache-Control: max-age=0');

	$loWriter = PHPExcel_IOFactory::createWriter($loXls, 'Excel5');
	$loWriter->setPreCalculateFormulas(true);
	$loWriter->save('php://output');
	exit;
?>
