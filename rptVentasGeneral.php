<?php


require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
$global_agencia=$_COOKIE['global_agencia'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];
$rptMarca=$_GET["rpt_marca"];
$rptTipoPago=$_GET["rpt_tipoPago"];

$cadenaTipoPagos="TODOS";	
if($rptTipoPago=="-1"){
	$cadenaTipoPagos="TODOS";
	$rptTipoPago=""; $swTipoPago=0;	 
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	$codTipopago=$datTipoPago[0];
		if($swTipoPago==0){
			$rptTipoPago=$datTipoPago[0];
			$swTipoPago=1;
		}else{
			$rptTipoPago=$rptTipoPago.",";
			$rptTipoPago=$rptTipoPago.$datTipoPago[0];
		}
	}
	echo "rptTipoPago".$rptTipoPago."<br>";;
}else{
	$swCadenaTipoPago=0;	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1 and cod_tipopago in(".$rptTipoPago.")	order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago)){	
		if($swCadenaTipoPago==0){
			$cadenaTipoPagos=$datTipoPago[1];
			$swCadenaTipoPago=1;
		}else{
			$cadenaTipoPagos=$cadenaTipoPagos.";";
			$cadenaTipoPagos=$cadenaTipoPagos.$datTipoPago[1];
		}
		
	}

	
}


$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon,$rpt_territorio);
echo "<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'  >REPORTE DE VENTAS X DOCUMENTO Y PRODUCTO</th></tr>
	<tr ><th>Territorio:</th><td> $nombre_territorio </td> </tr>
	<tr><th>De:</th> <td> $fecha_ini A:$fecha_fin</td></tr>
	<tr><th>Tipos de Pago: </th><td>$cadenaTipoPagos</td></tr>
	<tr><th>Fecha Reporte:</th> <td>$fecha_reporte</td></tr>	
	</table>";

$sql="select concat(s.`fecha`,' ',s.hora_salida)as fecha,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),s.`nro_correlativo`, s.`monto_final`, s.cod_salida_almacenes,
	(select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago) as nombreTipoPago,cod_chofer
	from `salida_almacenes` s 
	where s.`cod_tiposalida`=1001 
	and s.salida_anulada=0 and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";	
	$sql=$sql." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
	
if(!empty($rptTipoPago)){
	$sql=$sql." and s.cod_tipopago  in( $rptTipoPago) ";
	}
$sql.=" order by s.fecha, s.hora_salida, s.nro_correlativo";
// echo $sql;

$resp=mysqli_query($enlaceCon,$sql);
?>
<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Tipo Pago</th>
<th>Monto</th>
<th>Responsable</th>
<th>
	<table width='100%'>
	<tr>
		<th width='50%'>Codigo</th>
		<th width='50%'>Producto</th>
		<th width='50%'>Color/Talla</th>
		<th width='25%'>Cantidad</th>
		<th width='25%'>Monto</th>
	</tr>
	</table>
</th>
</tr>
<?php
$totalVenta=0;

while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$codSalida=$datos[7];
	$nombreTipoPago=$datos[8];
	$cod_funcionario=$datos[9];
	$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
$datResponsable=mysqli_fetch_array($respResponsable);
$nombreFuncionario=$datResponsable[0];
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	
	$sqlX="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla, 
	mar.nombre,m.cod_marca,m.codigo2
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m, marcas mar
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 
	and sd.`cod_material`=m.`codigo_material` 
	and m.cod_marca=mar.codigo
	and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') 
	and	s.cod_salida_almacenes='$codSalida' ";

	$sqlX=$sqlX." group by m.`codigo_material` order by 2 desc;";
	//echo $sqlX;
	
	$respX=mysqli_query($enlaceCon,$sqlX);

	$tablaDetalle="<table width='100%'>";
	
	$totalVentaX=0;
	
	while($datosX=mysqli_fetch_array($respX)){	
		$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVentaProd=$datosX[2];
		$cantidad=$datosX[3];
		
		$descuentoVenta=$datosX[4];
		$montoNota=$datosX[5];
		
		$colorItem=$datosX[6];
		$tallaItem=$datosX[7];
		$nombreMarca=$datosX[8];
		$codMarca=$datosX[9];
		$codigo2=$datosX[10];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVentaProd/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVentaProd=$montoVentaProd-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVentaProd,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
		$totalVentaX=$totalVentaX+$montoVentaProd;
				
		$tablaDetalle.="<tr>
		<td>$codItem $codigo2</td>
		<td>$nombreMarca $nombreItem</td>
		<td>$colorItem/$tallaItem</td>
		<td>$cantidadFormat</td>
		<td align='right'>$montoPtr</td>		
		</tr>";
	}
	$totalPtr=number_format($totalVentaX,2,".",",");
	if(($montoVenta-$totalVentaX)>0 || ($montoVenta-$totalVentaX)<0){
		$colorObs="#ff0000";
	}else{
		$colorObs="#ffffff";
	}
	$tablaDetalle.="<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Total:</strong></td>
		<td bgcolor='$colorObs' align='right'><strong>$totalPtr</strong></td>
	<tr></table>";

	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$datosDoc</td>
	<td>$nombreTipoPago</td>	
	<td align='right'>$montoVentaFormat</td>
		<td>$nombreFuncionario</td>
	
	<td>$tablaDetalle</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");

$sql2="select cod_tipopago, sum(s.`monto_final`)
	from `salida_almacenes` s 
	where s.`cod_tiposalida`=1001 
	and s.salida_anulada=0 and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";	
	$sql2=$sql2." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
	
if(!empty($rptTipoPago)){
	$sql2=$sql2." and s.cod_tipopago  in( $rptTipoPago) ";
	}
$sql2.=" group by cod_tipopago order by cod_tipopago asc";
// echo $sql2;

$resp2=mysqli_query($enlaceCon,$sql2);
while($datos2=mysqli_fetch_array($resp2)){	
	$tipoPago=$datos2[0];
	$sqlTipoPago2="select cod_tipopago, nombre_tipopago from tipos_pago where cod_tipopago=".$tipoPago;
	$respTipoPago2=mysqli_query($enlaceCon,$sqlTipoPago2);
	while($datTipoPago2=mysqli_fetch_array($respTipoPago2)){	
		$nombreTipoPago2=$datTipoPago2[1];
	}
	$montoTipoPago=$datos2[1];
	$montoTipoPagoFormat=number_format($montoTipoPago,2,".",",");

	echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>	
	<td>-</td>
	<td>-</td>
	<td><strong>$nombreTipoPago2</strong></td>
	<td align='right'><strong>$montoTipoPagoFormat</strong></td>
</tr>";
}
echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>

	<td><strong>Total Monto Venta(s)</strong></td>
	<td align='right'><strong>$totalVentaFormat</strong></td>
</tr>";
echo "</table></br>";
?>
<br><table align='center' class='textomediano' width='70%'>
<tr><th colspan='8'>DETALLES RECIBO</th></tr>
<tr>
<th>Tipo Recibo</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Observaciones</th>
<th>Proveedor</th>
<th>FormaPago</th>
<th>Documento</th>
<th>Monto [Bs]</th>
</tr>
<?php

$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor, r.cod_salida_almacen,
r.cod_estadorecibo, er.nombre_estado
from recibos r 
inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
left  join estados_recibo er on (r.cod_estadorecibo=er.cod_estado)
where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0 ";
$consulta = $consulta." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$consulta=$consulta." and r.cod_tipopago  in( $rptTipoPago) ";
	}
$consulta=$consulta." order by r.id_recibo asc,r.cod_ciudad desc ";

//echo $consulta;

$resp = mysqli_query($enlaceCon,$consulta);
$totalRecibo=0;
while ($dat = mysqli_fetch_array($resp)) {
	
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tiporecibo= $dat['cod_tiporecibo'];
	$nombre_tiporecibo= $dat['nombre_tiporecibo'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_salida_almacen= $dat['cod_salida_almacen'];
	$cod_estadorecibo= $dat['cod_estadorecibo'];
	$nombre_estadorecibo= $dat['nombre_estado'];

	$created_date_mostrar="";
	$totalRecibo=$totalRecibo+$monto_recibo;
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora

	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	?>
	<tr>
	<td><?=$nombre_tiporecibo;?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	
	
	<td><?=$nombre_recibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$nombre_proveedor;?></td>
	
	<td><?=$nombre_tipopago;?></td>
	<td>REC-<?=$id_recibo;?></td>
	
	
	<td align='right'><?=$monto_recibo;?></td>
	</tr>
<?php
}

$totMontoTipopago=0;
$sql2="select r.cod_tipopago,sum(r.monto_recibo)  from recibos r where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0";
$sql2 = $sql2." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$sql2=$sql2." and r.cod_tipopago  in( $rptTipoPago) ";
	}
$sql2 = $sql2."group by r.cod_tipopago order by r.cod_tipopago asc";

$resp2 = mysqli_query($enlaceCon,$sql2);
while ($dat2 = mysqli_fetch_array($resp2)) {
	$tipopago= $dat2[0];
	$totMontoTipopago= $dat2[1];
	$sql3=" select nombre_tipopago from tipos_pago where cod_tipopago=".$tipopago;
	
	$resp3 = mysqli_query($enlaceCon,$sql3);	
	while ($dat3 = mysqli_fetch_array($resp3)) {
		$descTipopago=$dat3[0];
	}

	$totMontoTipopagoF=number_format($totMontoTipopago,2,".",",");
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
	<td>&nbsp;</td>
	<td align="right"><strong><?=$descTipopago;?></strong></td>
	<td align="right"><strong><?=$totMontoTipopagoF;?></strong></td>
<tr>

<?php
}
$totalReciboF=number_format($totalRecibo,2,".",",");
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
	<td align="right"><strong>Total Recibos:</strong></td>
	<td align="right"><strong><?=$totalReciboF;?></strong></td>
<tr>
</table>
<br><center><table class='textomediano'>
<tr><th colspan='8'>Detalle de Gastos</th></tr>
<tr>
<th>Tipo</th>
<th>Nro</th>
<th>Fecha</th>
<th>Proveedor</th>
<th>Grupo</th>
<th>Detalle</th>
<th>Forma Pago</th>
<th>Monto [Bs]</th>
</tr>
	
<?php



$sqlGasto="select g.cod_gasto,g.descripcion_gasto,g.cod_tipogasto,tg.nombre_tipogasto,g.fecha_gasto,g.monto,g.cod_ciudad,
g.created_by,g.modified_by,g.created_date,g.modified_date,g.gasto_anulado,g.cod_proveedor, p.nombre_proveedor,g.cod_grupogasto, gg.nombre_grupogasto,
g.cod_tipopago, tp.nombre_tipopago
from gastos g
inner join tipos_gasto tg on (g.cod_tipogasto=tg.cod_tipogasto)
inner join grupos_gasto gg on (g.cod_grupogasto=gg.cod_grupogasto)
inner join tipos_pago tp on (g.cod_tipopago=tp.cod_tipopago)
left  join proveedores p on (g.cod_proveedor=p.cod_proveedor)
where    g.cod_ciudad=".$global_agencia." and g.gasto_anulado=0
and g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$sqlGasto=$sqlGasto." and g.cod_tipopago  in( $rptTipoPago) ";
	}
$sqlGasto=$sqlGasto." order by g.cod_gasto asc ";

//echo $sqlGasto;
$totalGastos=0;	
$respGasto= mysqli_query($enlaceCon,$sqlGasto);

while ($datGasto = mysqli_fetch_array($respGasto)) {
	
	$cod_gasto= $datGasto['cod_gasto'];
	$descripcion_gasto= $datGasto['descripcion_gasto'];
	$cod_tipogasto= $datGasto['cod_tipogasto'];
	$nombre_tipogasto= $datGasto['nombre_tipogasto'];
	$fecha_gasto= $datGasto['fecha_gasto'];	
	$vector_fecha_gasto=explode("-",$fecha_gasto);
	$fecha_gasto_mostrar=$vector_fecha_gasto[2]."/".$vector_fecha_gasto[1]."/".$vector_fecha_gasto[0];
	$monto= $datGasto['monto'];
	$cod_ciudad= $datGasto['cod_ciudad'];
	$created_by= $datGasto['created_by'];
	$modified_by= $datGasto['modified_by'];
	$created_date= $datGasto['created_date'];
	$modified_date= $datGasto['modified_date'];
	$gasto_anulado= $datGasto['gasto_anulado'];
	$cod_proveedor= $datGasto['cod_proveedor'];
	$nombre_proveedor= $datGasto['nombre_proveedor'];
	$cod_grupogasto= $datGasto['cod_grupogasto'];
	$nombre_grupogasto= $datGasto['nombre_grupogasto'];
	$cod_tipopago= $datGasto['cod_tipopago'];
	$nombre_tipopago= $datGasto['nombre_tipopago'];

	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora

	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
	$usuMod ="";
	 if(!empty($modified_by)){
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	}
	
	$totalGastos=$totalGastos+$monto;

	$monto=redondear2($monto);

?>

	<tr>
	<td align='center'><?=$nombre_tipogasto;?></td>
	<td align='center'><?=$cod_gasto;?></td>
	<td align='center'><?=$fecha_gasto_mostrar;?></td>
	<td align='right'><?=$nombre_proveedor;?></td>
	<td align='right'><?=$nombre_grupogasto;?></td>
	<td align='right'><?=$descripcion_gasto;?></td>
	<td align='right'><?=$nombre_tipopago;?></td>
	<td align='right'><?=$monto;?></td>
	</tr>
<?php

}
$totalGastos=redondear2($totalGastos);
$totMontoGastoTipopago=0;

$sql2="select g.cod_tipopago,sum(g.monto)  from gastos g where g.cod_ciudad=".$global_agencia." and g.gasto_anulado=0 ";
$sql2 = $sql2." AND g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$sql2=$sql2." and g.cod_tipopago  in( $rptTipoPago) ";
}
$sql2 = $sql2."group by g.cod_tipopago order by g.cod_tipopago asc";

$resp2 = mysqli_query($enlaceCon,$sql2);
while ($dat2 = mysqli_fetch_array($resp2)) {
	$tipopago= $dat2[0];
	$totMontoTipopago= $dat2[1];
	$sql3=" select nombre_tipopago from tipos_pago where cod_tipopago=".$tipopago;
	
	$resp3 = mysqli_query($enlaceCon,$sql3);	
	while ($dat3 = mysqli_fetch_array($resp3)) {
		$descTipopago=$dat3[0];
	}

	$totMontoTipopagoF=number_format($totMontoTipopago,2,".",",");
?>
<tr>
	<td colspan="6">&nbsp;</td>
	<td align="right"><strong><?=$descTipopago;?></strong></td>
	<td align="right"><strong><?=$totMontoTipopagoF;?></strong></td>
</tr>
<?php
}
?>
<tr>
<td colspan="6">&nbsp;</td>
<td align="right"><strong>TOTAL GASTOS</strong></td>
<td align="right"><strong><?=$totalGastos;?></strong></td>
</tr>
</table></center><br>
<center>
<table class='textomediano'>
<tr><th colspan='5'>TOTALES POR TIPO DE PAGO</th></tr>
<tr>
<th>&nbsp;</th>
<th>Ventas </th>
<th>Recibos </th>
<th>Gastos </th>
<th>Saldo </th>
</tr>
<?php
$totVTA=0;
$totREC=0;
$totGTO=0;

	$sqlTP="select cod_tipopago, nombre_tipopago  from tipos_pago  where estado=1 order by cod_tipopago asc";
	$respTP = mysqli_query($enlaceCon,$sqlTP);
	while ($datTP = mysqli_fetch_array($respTP)) {
		$codTP=$datTP['cod_tipopago'];
		$nombreTP=$datTP['nombre_tipopago'];
		/////////////////VENTAS/////////
		$totalVTATP=0;
		$sqlVTA="select cod_tipopago, sum(s.`monto_final`)
		from `salida_almacenes` s 
		where s.`cod_tiposalida`=1001 
		and s.salida_anulada=0 and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";	
		$sqlVTA=$sqlVTA." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
		$sqlVTA=$sqlVTA." and s.cod_tipopago  in( ".$codTP.") ";
		$sqlVTA.=" group by cod_tipopago ";		
		$respVTA=mysqli_query($enlaceCon,$sqlVTA);		
		while($datosVTA=mysqli_fetch_array($respVTA)){	
			$totalVTATP=$datosVTA[1];
		}
		////////////////////////////////////////
		////////////RECIBOS//////////
		$totalRECTP=0;
		$sqlREC="select r.cod_tipopago,sum(r.monto_recibo)  from recibos r where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0";
		$sqlREC =$sqlREC." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
		$sqlREC=$sqlREC." and r.cod_tipopago  in(".$codTP.") ";	
		$sqlREC = $sqlREC."group by r.cod_tipopago ";

		$respREC = mysqli_query($enlaceCon,$sqlREC);
		while ($datREC = mysqli_fetch_array($respREC)) {	
			$totalRECTP= $datREC[1];
		}
		//////////////////////////
		//////////////GASTOS/////////////
		$totalGTOTP=0;
		$sqlGTO=" select g.cod_tipopago,sum(g.monto)  from gastos g where g.cod_ciudad=".$global_agencia." and g.gasto_anulado=0 ";
		$sqlGTO = $sqlGTO." AND g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
		$sqlGTO = $sqlGTO." and g.cod_tipopago  in(".$codTP.") ";	
		$sqlGTO = $sqlGTO."group by g.cod_tipopago order by g.cod_tipopago asc";		
		$respGTO = mysqli_query($enlaceCon,$sqlGTO);
		while ($datGTO = mysqli_fetch_array($respGTO)){		
			$totalGTOTP= $datGTO[1];
		}
		/////////////////////////////////
		if($codTP<>4){
		$totVTA=$totVTA+$totalVTATP;
		$totREC=$totREC+$totalRECTP;
		$totGTO=$totGTO+$totalGTOTP;
		}
?>
<tr>
<td align="right"><?=$nombreTP;?></td>
<td align="right"><?=$totalVTATP;?></td>
<td align="right"><?=$totalRECTP;?></td>
<td align="right"><?=$totalGTOTP;?></td>
<td align="right"><?=($totalVTATP+$totalRECTP-$totalGTOTP);?></td>
</tr>
<?php
}
?>
<tr>
<td align="right">&nbsp;</td>
<td align="right"><strong><?=$totVTA;?></strong></td>
<td align="right"><strong><?=$totREC;?></strong></td>
<td align="right"><strong><?=$$totGTO;?></strong></td>
<td align="right"><strong><?=($totVTA+$totREC-$totGTO);?></strong></td>
</tr>

</table>
</center>
<?php 


//include("imprimirInc.php");
?>