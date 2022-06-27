<?php
echo "*";
$codSalida=$_GET['codVenta'];
require "conexionmysqli.inc";
echo "*";
require_once "siat_folder/funciones_siat.php";
echo "*";
$facturaImpuestos=generarXMLFacturaVentaImpuestos($codSalida);
echo "*";
// $sqlDatosVenta="select s.siat_cuf
//         from `salida_almacenes` s
//         where s.`cod_salida_almacenes`='$codSalida'";
// $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
// $cuf="";
// while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
//     $cuf=$datDatosVenta['siat_cuf'];

// }
// $nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.xml";
// unlink($nombreFile);	
// $archivo = fopen($nombreFile,'a');    
// fputs($archivo,$facturaImpuestos);
// fclose($archivo);


// if(!isset($_GET["email"])){
// 	header("Content-Type: application/force-download");
// 	header("Content-Disposition: attachment; filename=\"$cuf.xml\"");
// 	readfile($nombreFile);	
// 	unlink($nombreFile);
// }else{
// 	echo $cuf.".xml";
// }



