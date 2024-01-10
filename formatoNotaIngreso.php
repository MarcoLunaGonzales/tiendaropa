<?php
require('fpdf.php');
require('conexionmysqlipdf.inc');
require('funciones.php');
require('NumeroALetras.php');


$codigoIngreso=$_GET["codigo_ingreso"];

//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `ingreso_detalle_almacenes` s where s.`cod_ingreso_almacen`=$codigoIngreso";
$respNro=mysqli_query($enlaceCon,$sqlNro);
$nroItems=mysqli_result($respNro,0,0);

$tamanoLargo=250+($nroItems*3)-3;

$pdf=new FPDF('P','mm',array(76,$tamanoLargo));
//$pdf=new FPDF('P','mm',array(100,$tamanoLargo));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',10);

$y=3;
$incremento=3;
	
$sqlDatosIngreso="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo,
(select s.nro_correlativo from salida_almacenes s where s.cod_salida_almacenes=i.cod_salida_almacen) as salida,
(select a.nombre_almacen from salida_almacenes s, almacenes a where a.cod_almacen=s.cod_almacen and s.cod_salida_almacenes=i.cod_salida_almacen) as almacenorigen,
(select a.nombre_almacen from almacenes a where a.cod_almacen=i.cod_almacen)as almacen
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen='$codigoIngreso'";
$respDatosIngreso=mysqli_query($enlaceCon,$sqlDatosIngreso);
while($datDatosIngreso=mysqli_fetch_array($respDatosIngreso)){
	$fecha=$datDatosIngreso[1];
	$nombreTipoDoc=$datDatosIngreso[2];
	$nroDocIngreso=$datDatosIngreso[4];
	$obsIngreso=$datDatosIngreso[3];
	$nroSalidaTraspaso=$datDatosIngreso[5];
	$almacenOrigen=$datDatosIngreso[6];
	$almacenX=$datDatosIngreso[7];
}

$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,"NOTA DE INGRESO",0,0,"C");
$pdf->SetXY(0,$y+7);		$pdf->Cell(0,0,"$nombreTipoDoc Nro. $nroDocIngreso", 0,0,"C");
$pdf->SetXY(0,$y+11);		$pdf->Cell(0,0,"Almacen: $almacenX", 0,0,"C");
$pdf->SetXY(0,$y+13);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");


$pdf->SetXY(0,$y+16);		$pdf->Cell(0,0,"FECHA: $fecha",0,0,"C");

if($almacenOrigen!=""){
	$pdf->SetXY(0,$y+19);		$pdf->Cell(0,0,"Almacen Origen: $almacenOrigen",0,0,"C");
	$pdf->SetXY(0,$y+23);		$pdf->Cell(0,0,"Nro. Salida Origen: $nroSalidaTraspaso",0,0,"C");
	$y=$y-18;
}else{
	$y=$y-25;
}


$pdf->SetXY(0,$y+45);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(10,$y+48);		$pdf->Cell(0,0,"Producto");
$pdf->SetXY(30,$y+48);		$pdf->Cell(0,0,"Codigo");
$pdf->SetXY(50,$y+48);		$pdf->Cell(0,0,"Cantidad");
$pdf->SetXY(0,$y+52);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");

$sqlDetalle="select m.codigo_barras, i.cantidad_unitaria, m.descripcion_material, m.codigo_material,
	(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor)from proveedores p, proveedores_lineas pl 
	where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
	m.cod_marca,m.talla,m.color
	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigoIngreso' and m.codigo_material=i.cod_material order by m.descripcion_material";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=55;

$montoTotal=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2]." ".$datDetalle[4]." ".$datDetalle[5]." ".$datDetalle[6]." ".$datDetalle[7];
	
	if(strlen($nombreMat)>50){
		$nombreMat=substr($nombreMat,0,50)."..";
	}
	
	$montoUnit=0;
	$pdf->SetFont('Arial','',7);
	//$pdf->SetXY(5,$y+$yyy);		$pdf->MultiCell(60,3,"$nombreMat",1,"C");
	$pdf->SetXY(2,$y+$yyy);		$pdf->Cell(80,3,utf8_decode($nombreMat),0,0,"L");
	
	$pdf->SetFont('Arial','',9);
	
	$pdf->SetXY(30,$y+$yyy+2);		$pdf->Cell(10,5,"$codInterno",0,0,"R");
	$pdf->SetXY(45,$y+$yyy+2);		$pdf->Cell(20,5,"$cantUnit",0,0,"R");		
	$yyy=$yyy+8;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;

$pdf->SetFont('Arial','',7);
$pdf->SetXY(0,$y+$yyy+15);		$pdf->Cell(0,0,"_ _ _ _ _ _ _ _ _ _ _ _ _ _         _ _ _ _ _ _ _ _ _ _ _ _ _ _",0,0,"C");
$pdf->SetXY(9,$y+$yyy+20);		$pdf->Cell(0,0,"Recibi Conforme",0,0,"L");
$pdf->SetXY(45,$y+$yyy+20);		$pdf->Cell(0,0,"Entregue Conforme",0,0,"L");


$pdf->Output();
?>