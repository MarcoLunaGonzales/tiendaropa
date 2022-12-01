<html>
<body>

<?php
require('conexionmysqli.inc');
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');

require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];
$rptMarca=$_GET["rpt_marca"];
$rptTipoPago=$_GET["rpt_tipoPago"];
//echo "marcaAA".$rptMarca;
//echo "tipoPago".$rptTipoPago."<br>";
$cadenaMarcas="TODOS";	
if($rptMarca=="-1"){
	 $rptMarca=""; $swMarca=0;	 
	$sqlMarca="select codigo from marcas where estado= 1";
		if($globalTipoFuncionario==2){
		if($cantFuncProv>0){
			$sqlMarca= $sqlMarca." and codigo in( select codigo from proveedores_marcas where cod_proveedor in
			( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario))";
		}
	}
	//echo $sqlMarca ;
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
	while($datMarca=mysqli_fetch_array($respMarca))
	{
		if($swMarca==0){
			$rptMarca=$datMarca[0];
			$swMarca=1;
		}else{
			$rptMarca=$rptMarca.",";
			$rptMarca=$rptMarca.$datMarca[0];
		}
	}
	//echo "marcaAA".$rptMarca."<br>";
}else{
	$swCadenaMarca=0;	
	$sqlCadenaMarca="select codigo, nombre from marcas where estado=1 and codigo in(".$rptMarca.")	order by nombre asc";
	$respCadenaMarca=mysqli_query($enlaceCon,$sqlCadenaMarca);
	while($datCadenaMarca=mysqli_fetch_array($respCadenaMarca)){	
		if($swCadenaMarca==0){
			$cadenaMarcas=$datCadenaMarca[1];
			$swCadenaMarca=1;
		}else{
			$cadenaMarcas=$cadenaMarcas.";";
			$cadenaMarcas=$cadenaMarcas.$datCadenaMarca[1];
		}
		
	}
	
}
$cadenaTipoPagos="TODOS";	
if($rptTipoPago=="-1"){
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
	echo "rptTipoPago".$rptTipoPago."<br>";
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
//echo "marcaAA".$rptMarca."<br>";
	//echo "rptTipoPago".$rptTipoPago."<br>";

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon,$rpt_territorio);
?>
<table align="center"  >
<tr class="textotit" align='center' ><th  colspan="2"  >Reporte Ventas x Marcas Detallado</th></tr>
	<tr ><th>Territorio:</th><td><?php echo $nombre_territorio;?></td> </tr>
	<tr><th>De: </th> <td><?php  echo $fecha_ini;?> A: <?php  echo $fecha_fin;?></td></tr>
	<tr><th>Marcas: </th><td><?php  echo $cadenaMarcas;?></td></tr>
	<tr><th>Tipos de Pago: </th><td><?php  echo $cadenaTipoPagos;?></td></tr>
	<tr><th>Fecha Reporte:</th> <td><?php  echo$fecha_reporte;?></td></tr>	
	</table>
<?php

$sql=" select mar.nombre, concat(s.fecha,' ',s.hora_salida)as fecha,c.nombre_cliente,
s.razon_social, s.observaciones,t.abreviatura,
s.nro_correlativo, tp.nombre_tipopago, s.descuento,s.monto_final, s.cod_salida_almacenes,s.cod_chofer,
m.codigo_barras, m.descripcion_material, 
sd.monto_unitario,sd.descuento_unitario, 
sd.cantidad_unitaria, m.color, m.talla, m.cod_marca,m.codigo2 
from salida_detalle_almacenes sd
left join salida_almacenes s on (sd.cod_salida_almacen=s.cod_salida_almacenes) 
left join material_apoyo m on (sd.cod_material=m.codigo_material)
left join marcas  mar on (m.cod_marca=mar.codigo)
left join clientes c on(s.cod_cliente=c.cod_cliente)
left join tipos_docs t on (t.codigo=s.cod_tipo_doc)
left join tipos_pago tp on (tp.cod_tipopago=s.cod_tipopago)
where  s.salida_anulada=0 " ;
$sql.=" and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
if(!empty($rptTipoPago)){
	$sql.=" and s.cod_tipopago  in( $rptTipoPago) ";
	}
if(!empty($rptMarca)){
	$sql.=" and m.cod_marca in( $rptMarca)";
}

	$sql.=" order by mar.nombre asc , s.fecha asc ";


//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);

?>

<table align='center'  width='85%' border="1">
<tr>
<th>Marca</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Tipo Pago</th>
<th>Monto Factura</th>
<th>Descuento Factura</th>
<th>Cajero</th>
<th >Cod Producto</th>
<th>Producto</th>
<th >Color/Talla</th>
<th>Cantidad</th>
<th>Precio Producto</th>
<th>Descuento</th>
<th>Precio Venta Producto</th>

</th>
</tr>
<?php

$totalVentaProd=0;
$totalVentaDesc=0;
$totalVentaCobrado=0;

$swTotalMarca=0;
$codMarcaAnteriorPivote=0;
$codMarcaPivote=0;
$descMarcaAnteriorPivote="";
$descMarcaPivote="";
$totalMarcaProd=0;
$totalMarcaDescProd=0;
$totalMarcaVentaProd=0;
while($datos=mysqli_fetch_array($resp)){	

	
	$nombreMarca=$datos['nombre'];
	$fechaVenta=$datos['fecha'];
	$nombreCliente=$datos['nombre_cliente'];
	$razonSocial=$datos['razon_social'];
	$obsVenta=$datos['observaciones'];
	$datosDoc= $datos['abreviatura']."-".$datos['nro_correlativo'];
	$nombreTipopago= $datos['nombre_tipopago'];
	$descuentoVenta= $datos['descuento'];
	$montoVenta= $datos['monto_final'];
	$codSalida=$datos['cod_salida_almacenes'];
	$cod_funcionario=$datos['cod_chofer'];
	$codigoBarras=$datos['codigo_barras'];
	$descripcionMaterial=$datos['descripcion_material'];
	$montoUnitario=$datos['monto_unitario'];
	$descuentoUnitario=$datos['descuento_unitario'];
	$cantidadUnitaria=$datos['cantidad_unitaria'];
	$colorProducto=$datos['color'];
	$tallaProducto=$datos['talla'];
	$codMarca=$datos['cod_marca'];
	$codigo2=$datos['codigo2'];
	$montoUnitarioProdVenta=0;
	$montoUnitarioProdVentaFormato=0;
	$montoUnitarioDesc=0;
	$montoUnitarioDescFormato=0;

	
	// Porcentaje de descuento
	$porcentajeDescVenta=0;
	if($descuentoVenta>0){
		$porcentajeDescVenta=($descuentoVenta*100)/($montoVenta+$descuentoVenta);
		$montoUnitarioDesc=($porcentajeDescVenta*$montoUnitario)/100;		
		$montoUnitarioProdVenta=$montoUnitario-$montoUnitarioDesc;			
	}else{
		$montoUnitarioProdVenta=$montoUnitario;
	}
	
	$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
	$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	$datResponsable=mysqli_fetch_array($respResponsable);
	$nombreResponsable=$datResponsable[0];
		
	$totalVentaProd=$totalVentaProd+$montoUnitario;
	$totalVentaDesc=$totalVentaDesc+$montoUnitarioDesc;
	$totalVentaCobrado=$totalVentaCobrado+$montoUnitarioProdVenta;
	
	if($swTotalMarca==0){
		$swTotalMarca=1;
		$codMarcaAnteriorPivote=$datos['cod_marca'];
		$codMarcaPivote=$datos['cod_marca'];
		$descMarcaAnteriorPivote=$datos['nombre'];
		$descMarcaPivote=$datos['nombre'];

		$totalMarcaProd=$totalMarcaProd+$montoUnitario;
		$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
		$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;

		
	}else{
		$codMarcaAnteriorPivote=$codMarcaPivote;
		$codMarcaPivote=$datos['cod_marca'];
		$descMarcaAnteriorPivote=$descMarcaPivote;
		$descMarcaPivote=$datos['nombre'];
		
		if($codMarcaAnteriorPivote==$codMarcaPivote){
			
			$totalMarcaProd=$totalMarcaProd+$montoUnitario;
			$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
			$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;
			
		}else{
?>	

		<tr>
		<td align="right" colspan="13"><strong>TOTAL  VENTA <?php echo $descMarcaAnteriorPivote;?></strong></td>
		<td align="right"><strong><?php echo $totalMarcaProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaDescProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaVentaProd;?></strong></td>			
		</tr>
		


<?php 			
			$totalMarcaProd=0;
			$totalMarcaDescProd=0;
			$totalMarcaVentaProd=0;
			
			$totalMarcaProd=$totalMarcaProd+$montoUnitario;
			$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
			$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;

		}
		
	}


	$montoVentaFormat=number_format($montoVenta,2,".",",");
	$montoUnitarioFormato=number_format($montoUnitario,2,".",",");
	$cantidadFormat=number_format($cantidadUnitaria,0,".",",");
	$montoUnitarioDescFormato=number_format($montoUnitarioDesc,2,".",",");
	$montoUnitarioProdVentaFormato=number_format($montoUnitarioProdVenta,2,".",",");

?>	

		<tr>
		<td><?php echo $nombreMarca;?></td>
		<td><?php echo $fechaVenta;?></td>
		<td><?php echo $nombreCliente;?></td>
		<td><?php echo $razonSocial;?></td>
		<td><?php echo $datosDoc;?></td>
		<td><?php echo $nombreTipopago;?></td>
		<td align="right"><?php echo $montoVentaFormat;?></td>
		<td align="right"><?php echo number_format($descuentoVenta,2,".",",");?></td>	
		<td><?php echo $nombreResponsable;?></td>
		<td><?php echo $codigoBarras." ".$codigo2;?></td>
		<td><?php echo $descripcionMaterial;?></td>
		<td><?php echo $colorProducto."/".$tallaProducto;?></td>
		<td><?php echo $cantidadFormat;?></td>
		<td align="right"><?php echo $montoUnitarioFormato;?></td>		
		<td align="right"><?php echo $montoUnitarioDescFormato;?></td>		
		<td align="right"><?php echo $montoUnitarioProdVentaFormato;?></td>		
		</tr>
		
	

<?php 

}
 
$totalVentaProdFormato=number_format($totalVentaProd,2,".",",");
$totalVentaDescFormato=number_format($totalVentaDesc,2,".",",");
$totalVentaCobradoFormato=number_format($totalVentaCobrado,2,".",",");
 ?>
<tr>
		<td align="right" colspan="13"><strong>TOTAL VENTA <?php echo $descMarcaPivote;?></strong></td>
		<td align="right"><strong><?php echo $totalMarcaProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaDescProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaVentaProd;?></strong></td>		
		</tr>
<tr>
	
		<td colspan="13" align="right"><strong>TOTALES</strong></td>
		<td align="right"><strong><?php echo $totalVentaProdFormato;?></strong></td>		
		<td align="right"><strong><?php echo $totalVentaDescFormato;?></strong></td>		
		<td align="right"><strong><?php echo $totalVentaCobradoFormato;?></strong></td>		
		</tr>		
</body>
</html>


