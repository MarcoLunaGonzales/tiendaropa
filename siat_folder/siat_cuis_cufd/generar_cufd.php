<?php
require "../funciones_siat.php";

require "../../conexionmysqli.inc";

$ciudad=$_GET['cod_ciudad'];
$sql="select c.cod_impuestos,(SELECT codigoPuntoVenta from siat_PuntoVenta where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta from ciudades c where c.cod_ciudad='$ciudad'";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];
$codigoPuntoVenta=$dat[1];

generarCufd($ciudad,$cod_impuestos,$codigoPuntoVenta);
if(isset($_GET['l'])){
	?><script type="text/javascript">window.location.href='../../form_ventas.php';</script><?php	
}else{
	?><script type="text/javascript">window.location.href='index.php'</script><?php
}
