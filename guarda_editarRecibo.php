<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];
$idReciboEditar=$_POST["idReciboEditar"];


$monto=$_POST['monto'];
$tipoPago=$_POST['tipoPago'];
$tipoRecibo=$_POST['tipoRecibo'];
$nombre=$_POST['nombre'];
$nro_contacto=$_POST['nro_contacto'];
$desc_recibo=$_POST['desc_recibo'];
$proveedor=$_POST['proveedor'];
$restarVentaProv=$_POST['restarVentaProv'];
$grupoRecibo=$_POST['grupoRecibo'];
//echo "proveedor=".$proveedor;
if(empty($proveedor)){
	$proveedor=NULL;
	//echo "entro if proveedor=".$proveedor;
}

$modifiedBy=$_COOKIE['global_usuario'];
$modifiedDate=date("Y-m-d H:i:s");





$consulta="update recibos  set ";
$consulta.=" nombre_recibo='".$nombre."',";
$consulta.=" desc_recibo='".$desc_recibo."',";
$consulta.=" monto_recibo='".$monto."',";
$consulta.=" cel_recibo='".$nro_contacto."',";
$consulta.=" cod_tipopago='".$tipoPago."',";
$consulta.=" cod_tiporecibo='".$tipoRecibo."',";
$consulta.=" cod_gruporecibo='".$grupoRecibo."',";
$consulta.=" cod_proveedor='".$proveedor."',";
$consulta.=" modified_by='".$modifiedBy."',";
$consulta.=" modified_date='".$modifiedDate."', ";
$consulta.=" resta_ventas_proveedor='".$restarVentaProv."' ";
$consulta.=" where id_recibo='".$idReciboEditar."' and cod_ciudad='".$global_agencia."'";

//echo $consulta;
mysqli_query($enlaceCon,$consulta);

?>


	<script language='Javascript'>

		location.href="listaRecibos.php";
	</script>	
    

    	


