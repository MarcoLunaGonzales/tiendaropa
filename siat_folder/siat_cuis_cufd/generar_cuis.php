<?php
require "../funciones_siat.php";

require "../../conexionmysqli.php";

$ciudad=$_GET['cod_ciudad'];
$sql="select c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta from ciudades c where c.cod_ciudad='$ciudad'";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];
$codigoPuntoVenta=$dat[1];

generarCuis($ciudad,$cod_impuestos,$codigoPuntoVenta);
?>
<script type="text/javascript">window.location.href='index.php'</script>
