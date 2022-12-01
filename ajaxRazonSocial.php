<?php
/*require("conexionmysqli.php");
$nitCliente=$_GET['nitCliente'];

$sql="select f.razon_social from facturas_venta f 
	where f.nit='$nitCliente' order by f.fecha desc limit 0,1";
$resp=mysqli_query($enlaceCon,$sql);

$nombre="";
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
}
echo "<input type='text' value='$nombre' name='razonSocial' id='razonSocial' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>";
*/
$estilosVenta=1;
require("conexionmysqli.php");
$nitCliente=$_GET['nitCliente'];

$sql="select f.razon_social from facturas_venta f 
	where f.nit='$nitCliente' and (f.razon_social<>'SN' or f.razon_social<>'S/N' or f.razon_social<>'S-N') order by f.fecha desc limit 0,1";
$resp=mysqli_query($enlaceCon,$sql);

$nombre="";
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
}

$sql="select f.nombre_factura from clientes f 
	where f.nit_cliente='$nitCliente' order by f.cod_cliente desc limit 0,1";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
}

if($nitCliente=="123"){
	$nombre="SN";
}
if($nitCliente=="99001"){
	$nombre="-";
}
if($nitCliente=="99002"){
	$nombre="CONTROL TRIBUTARIO";
}
if($nitCliente=="99003"){
	$nombre="VENTAS MENORES DEL DIA";
}
echo "<input type='text' value='$nombre' class='form-control' name='razonSocial' id='razonSocial' required style='text-transform:uppercase;'  onchange='ajaxNitCliente(this.form);' onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='Ingrese la razon social' pattern='[A-Za-z0-9Ññ.& ]+'>";

?>
