<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

//echo $_GET['codigoPreingreso'];



$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];


$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];

$hora_sistema = date("H:i:s");

$nota_entrega=0;

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha_real=date("Y-m-d");

	$sqlAux=" select IFNULL(codigo_ingreso,0) from  preingreso_almacenes  where cod_ingreso_almacen=".$_GET['codigoPreingreso'];
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	if($datAux[0]==0){

$consulta="insert into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date) 
select $codigo,$global_almacen,cod_tipoingreso,'$fecha_real','$hora_sistema',observaciones,
cod_salida_almacen,nota_entrega,$nro_correlativo,ingreso_anulado,0,0,nro_factura_proveedor,0,0,cod_proveedor,'$createdBy','0','$createdDate',''
from preingreso_almacenes where  cod_ingreso_almacen=".$_GET['codigoPreingreso'];


$resp_inserta = mysqli_query($enlaceCon,$consulta);


if($resp_inserta==1){
	
	$sql1=" update  preingreso_almacenes set codigo_ingreso=$codigo where cod_ingreso_almacen=".$_GET['codigoPreingreso'];
	mysqli_query($enlaceCon,$sql1);
	
	 
		$sql2="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, 
lote, fecha_vencimiento,precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) 
select 1,cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento,precio_bruto, costo_almacen, costo_actualizado,
 costo_actualizado_final, costo_promedio, precio_neto from preingreso_detalle_almacenes where cod_ingreso_almacen=".$_GET['codigoPreingreso'];
mysqli_query($enlaceCon,$sql2);
    
		echo "<script language='Javascript'>
			Swal.fire('Los datos fueron insertados correctamente.')
		    .then(() => {
				location.href='navegador_ingresomateriales.php';
		    });
		</script>";
	  /*echo "<script language='Javascript'>
		alert('Los datos fueron insertados correctamente.');
		location.href='navegador_ingresomateriales.php';
		</script>";	*/

    	
}else{
			echo "<script language='Javascript'>
			Swal.fire('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.')
		    .then(() => {
				location.href='navegador_preingreso.php';
		    });
		</script>";
		
	/*echo "<script language='Javascript'>
		alert('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.');
		location.href='navegador_preingreso.php';
		</script>";	*/
}
}else{
		echo "<script language='Javascript'>
			Swal.fire('YA SE GENERO EL INGRESO.')
		    .then(() => {
				location.href='navegador_preingreso.php';
		    });
		</script>";
		
		/*echo "<script language='Javascript'>
		alert('YA SE GENERO EL INGRESO.');
		location.href='navegador_preingreso.php';
		</script>";	*/
}
?>