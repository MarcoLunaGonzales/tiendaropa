<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];

$idGastoEditar=$_POST["idGastoEditar"];

$monto=$_POST['monto'];
$tipoPago=$_POST['tipoPago'];
$tipoGasto=$_POST['tipoGasto'];
$grupoGasto=$_POST['grupoGasto'];
$proveedor=$_POST['proveedor'];
//echo "proveedor=".$proveedor;
if(empty($proveedor)){
	$proveedor=NULL;
	//echo "entro if proveedor=".$proveedor;
}
$descripcion_gasto=$_POST['descripcion_gasto'];

$fecha=$_POST['fecha'];
$vector_fecha_gasto=explode("/",$fecha);
$fecha_gasto=$vector_fecha_gasto[2]."-".$vector_fecha_gasto[1]."-".$vector_fecha_gasto[0];

$modifiedBy=$_COOKIE['global_usuario'];
$modifiedDate=date("Y-m-d H:i:s");


$consulta="update gastos  set ";
$consulta.=" descripcion_gasto='".$descripcion_gasto."',";
$consulta.=" monto='".$monto."',";
$consulta.=" fecha_gasto='".$fecha_gasto."',";
$consulta.=" cod_tipopago='".$tipoPago."',";
$consulta.=" cod_tipogasto='".$tipoGasto."',";
$consulta.=" cod_grupogasto='".$grupoGasto."',";
$consulta.=" cod_proveedor='".$proveedor."',";
$consulta.=" modified_by='".$modifiedBy."',";
$consulta.=" modified_date='".$modifiedDate."' ";
$consulta.=" where cod_gasto='".$idGastoEditar."' and cod_ciudad='".$global_agencia."'";
//echo $consulta;
mysqli_query($enlaceCon,$consulta);

?>


	<script language='Javascript'>
		location.href="listaGastos.php";
	</script>	
    

    	


