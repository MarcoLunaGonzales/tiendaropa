<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];

$sql = "select IFNULL(MAX(id_recibo)+1,1) from recibos where cod_ciudad='".$global_agencia."' order by id_recibo desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$id_recibo=$dat[0];
//$nro_correlativo=mysql_result($resp,0,0);



$monto=$_POST['monto'];
$tipoPago=$_POST['tipoPago'];
$nombre=$_POST['nombre'];
$nro_contacto=$_POST['nro_contacto'];
$desc_recibo=$_POST['desc_recibo'];

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha=date("Y-m-d");



$consulta="insert into recibos (id_recibo,fecha_recibo,cod_ciudad,nombre_recibo,desc_recibo,
monto_recibo,created_by,created_date,cel_recibo,recibo_anulado,cod_tipopago) 
values(".$id_recibo.",'".$fecha."',".$global_agencia.",'".$nombre."','".$desc_recibo."',".$monto.",".$createdBy.",'".$createdDate."','".$nro_contacto."',0,".$tipoPago.")";
//echo $consulta;
mysqli_query($enlaceCon,$consulta);

?>


	<script language='Javascript'>

		location.href="listaRecibos.php";
	</script>	
    

    	

