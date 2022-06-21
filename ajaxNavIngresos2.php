<?php
require("conexionmysqli.php");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$notaIngreso=$_GET['notaIngreso'];
$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];

$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);

echo "<br/><center><table class='texto' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th><th>&nbsp;</th><th>Nro PreIngreso</th></tr>";
	

$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor,i.nro_factura_proveedor
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso";
		if($globalTipoFuncionario==2){
		if($cantFuncProv>0){
			$consulta= $consulta." and i.cod_proveedor in( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario)";
		}
	}
    $consulta= $consulta." AND i.cod_almacen='$global_almacen'";

if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta." AND '$fechaIniBusqueda'<=i.fecha AND i.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=0){
	$consulta=$consulta." and cod_proveedor='$provBusqueda' ";
}   
$consulta = $consulta." ORDER BY i.nro_correlativo DESC";

$resp = mysqli_query($enlaceCon,$consulta);
	
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
	$proveedor=$dat[8];
	$nroFacturaProveedor=$dat[9];
		$sqlAux=" select IFNULL(codigo_ingreso,0),nro_correlativo from  preingreso_almacenes  where codigo_ingreso=$codigo";
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);

    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
                where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
        if ($num_filas_movimiento > 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 and $anulado == 0) {
        $color_fondo = "";
        $chkbox = "";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td>&nbsp;$obs_ingreso</td><td align='center'>
	<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'>
	<img src='imagenes/detalles.png' border='0' title='Ver Detalles del Ingreso' width='40'></a></td>";
	if($datAux[0]>0){
		echo"<td align='center'>$datAux[1]</td>";
	}else{
		echo"<td align='center'></td>";
	}
	echo"</tr>";
}

echo "</table></center><br>";


?>
