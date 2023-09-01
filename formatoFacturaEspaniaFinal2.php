<?php

require('fpdf186/fpdf.php');
require('conexionmysqli2.inc');
require('funciones.php');
require('NumeroALetras.php');

mysqli_query($enlaceCon,"SET NAMES utf8");

error_reporting(E_ALL);
ini_set('display_errors', '1');

class PDF extends FPDF{ 
	public $titulo = 'Otro Titulo';
	function Header()
	{
		//$incremento=3;
		global $nombreEmpresa;

		global $ciudadEmpresa;

		global $direccionEmpresa;

		global $telefonoTxt;

		global $ciudadTxt;

		global $txt1;

		global $txt2;

		global $txt3;

		global $nitEmpresa;

		// Datos de factura

		global $nitCliente;
		global $razonSocialCliente;
		global $fechaFactura;

		global $fechaVenta;
		global $nombreTipoDoc;
		global $nombreCliente;
		global $nroDocVenta;
		global $descuentoVenta;
		global $horaFactura;
		global $montoTotal2;
		global $montoFinal2;

		global $montoEfectivo2;
		global $montoCambio2;

		global $descuentoCabecera;
		global $cod_funcionario;
		global $codTipoPago;
		global $nombretipoPago;
		global $tipoDoc;
		global $codTipoDoc;
		global $direccionCliente;

		global $logoFactura;
		//
		

		
		$this->Image("imagenes/".$logoFactura,10,10,-700);	
		$y=30;
		$this->SetY($y);
		$this->SetXY(10,$y);		
		$this->SetFont('Times','',10);	
		$this->Cell(0,0,$nombreEmpresa,0,0,"L");		
		$this->SetXY(10,$y+4);		
		$this->Cell(0,0,"NIF: ".$nitEmpresa,0,0,"L");
		$this->SetXY(10,$y+8);		
		$this->Cell(0,0,$direccionEmpresa,0,0,"L");
		$this->SetXY(10,$y+12);		
		$this->Cell(0,0,utf8_decode("CP.46003"),0,0,"L");
		$this->SetXY(10,$y+16);		
		$this->Cell(0,0,utf8_decode("VALENCIA-ESPAÑA"),0,0,"L");
		$this->SetFont('Times','B',25);
		$this->SetXY(0,$y);		
		$this->Cell(200,0,strtoupper($nombreTipoDoc),0,0,"R");		
		$this->SetFont('Times','B',10);
		$this->SetXY(0,$y+8);		
		$this->Cell(200,0,"Nro $nombreTipoDoc:$nroDocVenta",0,0,"R");
		$this->SetXY(0,$y+12);	
		$this->Cell(200,0,"Facturar a:",0,0,"R");
		$this->SetXY(0,$y+16);	
		$this->SetFont('Times','',10);
		$this->Cell(200,0,$razonSocialCliente,0,0,"R");
		$this->SetXY(0,$y+20);	
		$this->Cell(200,0,$direccionCliente,0,0,"R");	
		$this->SetXY(10,51);
		$this->SetFont('Times','B',10);
		$this->Cell(200,0,"DATOS CLIENTE",0,0,"L");	
		$this->SetXY(10,55);
	$this->SetFont('Times','B',9);
		$this->Cell(20,0,"ID Fiscal: ",0,0,"L");	
		
		$this->SetFont('Times','',10);
		$this->SetXY(25,55);
		$this->Cell(0,0,$nitCliente,0,0,"L");	
		$this->SetFont('Times','B',9);
		$this->SetXY(10,59);
		$this->Cell(95,0,"FECHA:",0,0,"L");	
		$this->SetFont('Times','',10);
		$this->SetXY(25,59);
		$this->Cell(0,0,$fechaVenta,0,0,"L");
		$this->SetFont('Times','B',9);
		$this->SetXY(100,59);
		$this->Cell(30,0,"FORMA DE PAGO:",0,0,"L");	
		$this->SetXY(130,59);
		$this->SetFont('Times','',9);
		$this->Cell(0,0,$nombretipoPago,0,0,"L");	
		$this->SetXY(10,63);
		$this->Line(10,63,200,63);

		$this->Line(10,73,200,73);
			$this->SetFont('Times','B',10);

	
		$this->SetXY(10,65);		$this->Cell(80,8,"CONCEPTO",'C', True);
		$this->SetXY(125,65);		$this->Cell(20,8,"CANT.",'C', True);
		$this->SetXY(152,65);		$this->Cell(20,8,"PRECIO",'C', True);
		$this->SetXY(179,65);		$this->Cell(20,8,"IMPORTE",'C', True);
	

	}	

	function Footer()
	{
			global $montoTotal;
			global $descuentoVenta;
			global $montoTotal2;
			global $montoFinal2;
			global $porcentajeImpuesto;
			global $incrementoImpuesto;

			$euro=" €";
			//$this->Line(10,210,200,210);
			$this->SetXY(172,210);	
			$this->SetFont('Times','B',9);
			$this->Cell(10,6,"SUBTOTAL:",0,0,'R',false);
			$this->SetXY(187,210);	
			$this->Cell(13,6,(number_format($montoTotal2,2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,'R',false);

			$this->SetXY(172,215);	
			$this->SetFont('Times','B',9);
			$this->Cell(10,6,"DESCUENTO:",0,0,'R',false);
			$this->SetXY(187,215);	
			$this->Cell(13,6,(number_format($descuentoVenta,2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,'R',false);

			$this->SetXY(172,220);	
			$this->SetFont('Times','B',10);
			$this->Cell(10,6,"TOTAL:",0,0,'R',false);
			$this->SetXY(187,220);	
			$this->Cell(13,6,(number_format(($montoTotal2-$descuentoVenta),2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,'R',false);


			$this->SetXY(172,225);	
			$this->SetFont('Times','B',9);
			//$this->Cell(10,6,"IVA(".$porcentajeImpuesto."%):",0,0,'R',false);
			//$this->SetXY(187,225);	
			//$this->Cell(13,6,(number_format($incrementoImpuesto,2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,'R',false);
		
			$this->Line(10,236,200,236);
			$this->SetXY(10,238);
			$this->SetFont('Times','B',12);
			$this->Cell(194,6,"MONTO A PAGAR: ".(number_format($montoFinal2,2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,'C',false);
			$this->Line(10,246,200,246);
	}	
}


////////////////////////////

$codigoVenta=$_GET["codVenta"];

//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysqli_query($enlaceCon,$sqlNro);
$nroItems=0;
if($datNro=mysqli_fetch_array($respNro)){
	$nroItems=$datNro[0];
}

$tamanoLargo=180+($nroItems*3)-3;


$incremento=3;

//desde aca
$nombreEmpresa="";
$sqlConf="select id, valor from configuracion_facturas where id=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nombreEmpresa=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$ciudadEmpresa=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$direccionEmpresa=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$telefonoTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$ciudadTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt1=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=7";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt2=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=8";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt3=mysqli_result($respConf,0,1);


$sqlConf="select id, valor from configuracion_facturas where id=9";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nitEmpresa=mysqli_result($respConf,0,1);

$sqlLogoFactura="select valor_configuracion from configuraciones where id_configuracion=15";	
	$respLogoFactura=mysqli_query($enlaceCon,$sqlLogoFactura);
	$datLogoFactura=mysqli_fetch_array($respLogoFactura);
	$logoFactura=$datLogoFactura[0];

$sqlDatosFactura="select  f.nit, f.razon_social, DATE_FORMAT(f.siat_fechaemision, '%d/%m/%Y'),f.direccion_cliente
from salida_almacenes f where f.cod_salida_almacenes=$codigoVenta";	
$respDatosFactura=mysqli_query($enlaceCon,$sqlDatosFactura);
$datDatosFactura=mysqli_fetch_array($respDatosFactura);
$nitCliente=$datDatosFactura[0];
$razonSocialCliente=$datDatosFactura[1];
$razonSocialCliente=strtoupper($razonSocialCliente);
$fechaFactura=$datDatosFactura[2];
$direccionCliente=$datDatosFactura[3];
$direccionCliente=strtoupper($direccionCliente);
//////////////////
$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre` as nombreTipoDoc,
 c.`nombre_cliente`, s.`nro_correlativo`, 
s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,
s.cod_tipopago,tp.nombre_tipopago,s.cod_tipo_doc,s.fecha,
al.cod_ciudad,s.cod_cliente,s.porcentaje_impuesto,s.incremento_impuesto
from `salida_almacenes` s
left join almacenes al on (s.cod_almacen=al.cod_almacen)
left join`tipos_docs` t on (s.`cod_tipo_doc`=t.`codigo`)
left join  `clientes` c on (s.`cod_cliente`=c.`cod_cliente`)
left join  `tipos_pago` tp on (s.cod_tipopago=tp.cod_tipopago)
where s.`cod_salida_almacenes`='$codigoVenta'";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);

while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta['nombreTipoDoc'];
	$nombreCliente=$datDatosVenta['nombre_cliente'];
	$nroDocVenta=$datDatosVenta['nro_correlativo'];
	$descuentoVenta=$datDatosVenta['descuento'];
	$descuentoVenta=redondear2($descuentoVenta);
	$horaFactura=$datDatosVenta['hora_salida'];
	$montoTotal2=$datDatosVenta['monto_total'];
	$montoFinal2=$datDatosVenta['monto_final'];
	$montoEfectivo2=$datDatosVenta['monto_efectivo'];
	$montoCambio2=$datDatosVenta['monto_cambio'];

	$montoTotal2=redondear2($montoTotal2);
	$montoFinal2=redondear2($montoFinal2);

	$montoEfectivo2=redondear2($montoEfectivo2);
	$montoCambio2=redondear2($montoCambio2);

	$descuentoCabecera=$datDatosVenta['descuento'];
	$cod_funcionario=$datDatosVenta['cod_chofer'];
	$codTipoPago=$datDatosVenta['cod_tipopago'];
	$nombretipoPago=$datDatosVenta['nombre_tipopago'];
	
	$codTipoDoc=$datDatosVenta['cod_tipo_doc'];

	$fecha_salida=$datDatosVenta['fecha'];
	$hora_salida=$datDatosVenta['hora_salida'];
	$cod_ciudad_salida=$datDatosVenta['cod_ciudad'];
	$cod_cliente=$datDatosVenta['cod_cliente'];
	$porcentajeImpuesto=$datDatosVenta['porcentaje_impuesto'];
	$incrementoImpuesto=$datDatosVenta['incremento_impuesto'];

		$porcentajeImpuesto=redondear2($porcentajeImpuesto);
	$incrementoImpuesto=redondear2($incrementoImpuesto);
	

}
 // responsable de Facturacion
	$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
	$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	$datResponsable=mysqli_fetch_array($respResponsable);
	$nombreFuncionario=$datResponsable[0];

///////////////////////
/******** Iniciando FPDF ******/
	$pdf=new PDF('P','mm',array(214,279));
	$pdf->SetAutoPageBreak(true,65);
	$pdf->AliasNbPages();
	$pdf->AddPage();




$sqlDatosVenta="select concat(s.fecha,' ',s.hora_salida) as fecha, t.`nombre`, 
(select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente) as nombreCliente, 
s.`nro_correlativo`, s.razon_social, s.observaciones, s.descuento, (select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago) 
		from `salida_almacenes` s, `tipos_docs` t
		where s.`cod_salida_almacenes`='$codigoVenta'  and
		s.`cod_tipo_doc`=t.`codigo`";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta[1];
	$nombreCliente=$datDatosVenta[2];
	$nroDocVenta=$datDatosVenta[3];
	$razonSocial=$datDatosVenta[4];
	$obsVenta=$datDatosVenta[5];
	$descuentoVenta=$datDatosVenta[6];
	$descuentoVenta=redondear2($descuentoVenta);
	$tipoPago=$datDatosVenta[7];
}



$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
		sum(s.`descuento_unitario`), sum(s.`monto_unitario`) from `salida_detalle_almacenes` s, `material_apoyo` m where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta 
		group by s.cod_material
		order by s.orden_detalle";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=75;

$montoTotal=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2];
	$nombreMat=substr($nombreMat,0,45);
	$precioUnit=$datDetalle[3];
	$precioUnit=redondear2($precioUnit);
	$descUnit=$datDetalle[4];
	$montoUnit=$datDetalle[5];
	$montoUnit=$montoUnit-$descUnit;
	$montoUnit=redondear2($montoUnit);
	
	$pdf->SetFont('Times','',9);

	$euro=" €";
	
	$pdf->SetXY(10,$yyy);		$pdf->Cell(80,8,utf8_decode($nombreMat),0,0,"L");
	$pdf->SetXY(125,$yyy);		$pdf->Cell(15,8,$cantUnit,0,0,"R");
	$pdf->SetXY(152,$yyy);		$pdf->Cell(15,8,(number_format($montoUnit,2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,"R");
	//$pdf->SetXY(129,$yyy);		$pdf->Cell(20,8,($cantUnit*$montoUnit).iconv('UTF-8', 'windows-1252', $euro),0,0,"R");
	//$pdf->SetXY(152,$yyy);		$pdf->Cell(20,8,((($cantUnit*$montoUnit)*21)/100).iconv('UTF-8', 'windows-1252', $euro),0,0,"R");
	
	//$pdf->SetXY(175,$yyy);		$pdf->Cell(20,8,$montoUnit+((($cantUnit*$montoUnit)*21)/100).iconv('UTF-8', 'windows-1252', $euro),0,0,"R");

	$pdf->SetXY(179,$yyy);		$pdf->Cell(20,8,(number_format($cantUnit*$montoUnit,2,'.','')).iconv('UTF-8', 'windows-1252', $euro),0,0,"R");

	$montoTotal=$montoTotal+$montoUnit;
		
	$yyy=$yyy+8;
}

$yyy=$yyy+5;


$montoFinal=$montoTotal-$descuentoVenta;

/*$pdf->SetXY(25,$y+$yyy);		$pdf->Cell(25,5,"Total Venta:",0,0,"R");
$pdf->SetXY(45,$y+$yyy);		$pdf->Cell(20,5,$montoTotal,0,0,"R");

$pdf->SetXY(25,$y+$yyy+4);		$pdf->Cell(25,5,"Descuento:",0,0,"R");
$pdf->SetXY(45,$y+$yyy+4);		$pdf->Cell(20,5,$descuentoVenta,0,0,"R");

$pdf->SetXY(25,$y+$yyy+8);		$pdf->Cell(25,5,"Total Final:",0,0,"R");
$pdf->SetXY(45,$y+$yyy+8);		$pdf->Cell(20,5,$montoFinal,0,0,"R");

$montoFinalX=formatonumeroDec($montoFinal);
list($montoEntero, $montoDecimal) = explode('.', $montoFinalX);
if($montoDecimal==""){
	$montoDecimal="00";
}

$pdf->SetFont('Arial','',7);

$txtMonto=NumeroALetras::convertir($montoEntero);
$pdf->SetXY(5,$y+$yyy+15);		$pdf->MultiCell(0,3,"Son:  $txtMonto"." ".$montoDecimal."/100 Bolivianos",0,"L");
$pdf->SetXY(0,$y+$yyy+21);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
*/
$pdf->Output();



?>