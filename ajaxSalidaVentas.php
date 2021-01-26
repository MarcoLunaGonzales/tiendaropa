<?php
require("conexion.inc");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$nroCorrelativoBusqueda=$_GET['nroCorrelativoBusqueda'];
$verBusqueda=$_GET['verBusqueda'];
$global_almacen=$_GET['global_almacen'];
$clienteBusqueda=$_GET['clienteBusqueda'];
$vendedorBusqueda=$_GET['vendedorBusqueda'];
$tipoVentaBusqueda=$_GET['tipoVentaBusqueda'];

$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);

echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Doc</th><th>Fecha/hora<br>Registro Salida</th><th>Tipo de Salida</th>
	<th>TipoPago</th><th>Razon Social</th><th>NIT</th><th>Observaciones</th><th>FP</th><th>FG</th><th>Cambio</th><th>Convertir</th></tr>";	

$consulta = "
	SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
	(select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
	s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
	(select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,
	(select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=s.cod_tipopago)as tipopago 
	FROM salida_almacenes s, tipos_salida ts 
	WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 ";

if($nroCorrelativoBusqueda!="")
   {$consulta = $consulta."AND s.nro_correlativo='$nroCorrelativoBusqueda' ";
   }
if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=s.fecha AND s.fecha<='$fechaFinBusqueda' ";
   }
if($clienteBusqueda!=0){
	$consulta=$consulta." and cod_cliente='$clienteBusqueda' ";
}

if($vendedorBusqueda!=0){
	$consulta=$consulta." and cod_chofer='$vendedorBusqueda' ";
}
if($tipoVentaBusqueda!=0){
	$consulta=$consulta." and cod_tipopago='$tipoVentaBusqueda' ";
}   
if($verBusqueda==1){
	$consulta=$consulta." AND estado_salida=4 ";
}
if($verBusqueda==2){
    $consulta=$consulta." AND salida_anulada=1 ";
}
$consulta = $consulta."ORDER BY s.fecha desc, s.nro_correlativo DESC";

//
$resp = mysql_query($consulta);
	
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
	$nombreCliente=$dat[10];
	$codTipoDoc=$dat[11];
	$nombreTipoDoc=nombreTipoDoc($codTipoDoc);
	$razonSocial=$dat[12];
	$razonSocial=strtoupper($razonSocial);
	$nitCli=$dat[13];
	$tipoPago=$dat[14];
	
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
	
	$sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
	$respEstadoColor=mysql_query($sqlEstadoColor);
	$numFilasEstado=mysql_num_rows($respEstadoColor);
	if($numFilasEstado>0){
		$color_fondo=mysql_result($respEstadoColor,0,0);
	}else{
		$color_fondo="#ffffff";
	}
	$chk = "<input type='checkbox' name='codigo' value='$codigo'>";

	
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'>$nombreTipoDoc-$nro_correlativo</td>";
    echo "<td align='center'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td>$nombre_tiposalida</td>";
    echo "<td>$tipoPago</td><td>&nbsp;$razonSocial</td><td>&nbsp;$nitCli</td><td>&nbsp;$obs_salida</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
	
	$urlConversionFactura="convertNRToFactura.php?codVenta=$codigo";    
    
	$NRparaMostrar=$nombreTipoDoc."-".$nro_correlativo;
	$fechaParaMostrar=fecha_salida_mostrar;
	
	/*echo "<td bgcolor='$color_fondo'><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Detalle'></a></td>";
	*/
	if($codTipoDoc==1){
		echo "<td  bgcolor='$color_fondo'><a href='formatoFactura2.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
		echo "<td  bgcolor='$color_fondo'><a href='notaSalida.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/detalle.png' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
	}
	else{
		echo "<td  bgcolor='$color_fondo'><a href='formatoNotaRemision2.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
		echo "<td  bgcolor='$color_fondo'><a href='notaSalida.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/detalle.png' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
	}
	
	$codigoVentaCambio=0;

    $sqlCambio="select c.cod_cambio from salida_almacenes c where c.cod_cambio=$codigo";
    $respCambio=mysql_query($sqlCambio);
    while($datCambio=mysql_fetch_array($respCambio)){
        $codigoVentaCambio=$datCambio[0];        
    }
    if($codigoVentaCambio==0){
      echo "<td  bgcolor='$color_fondo'><a href='cambiarProductoVenta.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/change.png' width='30' border='0' title='Cambio de Producto'></a></td>";
    }else{
        echo "<td  bgcolor='$color_fondo'><a href='notaSalidaCambio.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/icon_detail.png' width='30' border='0' title='Ver Detalle del Cambio'></a></td>";
    }
	if($codTipoDoc==2){
		echo "<td bgcolor='$color_fondo'>
		<a href='#' onClick='ShowFacturar($codigo,$nro_correlativo);'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Convertir en Factura'></a></td>";	
	}elseif($codTipoDoc==1){
		echo "<td align='center'>
		<a href='#' onClick='convertirNR($codigo);'>
		<img src='imagenes/restaurar2.png' width='20' border='0' title='Convertir en NR y Anular Factura'></a>
		</td>";
	}
	
	/*echo "<td  bgcolor='$color_fondo'><a href='notaSalida.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Grande'></a></td>";*/
	echo "</tr>";
}
echo "</table></center><br>";


?>
