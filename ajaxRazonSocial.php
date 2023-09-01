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

$sql="select f.razon_social,f.direccion from facturas_venta f 
	where f.nit='$nitCliente' and (f.razon_social<>'SN' or f.razon_social<>'S/N' or f.razon_social<>'S-N') 
	order by f.fecha desc,f.cod_venta desc limit 0,1";
$resp=mysqli_query($enlaceCon,$sql);

$nombre="";
$direccion="";
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
	$direccion=$dat[1];
}

$sql="select f.nombre_factura,f.dir_cliente from clientes f 
	where f.nit_cliente='$nitCliente' order by f.cod_cliente desc limit 0,1";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
	$direccion=$dat[1];
}

if($nitCliente=="123"){
	$nombre="SN";
	$direccion="";
}
if($nitCliente=="99001"){
	$nombre="-";
	$direccion="";
}
if($nitCliente=="99002"){
	$nombre="CONTROL TRIBUTARIO";
	$direccion="";
}
if($nitCliente=="99003"){
	$nombre="VENTAS MENORES DEL DIA";
	$direccion="";
}
echo "<input type='text' value='$nombre' class='form-control' name='razonSocial' id='razonSocial' required style='text-transform:uppercase;'  onchange='ajaxNitCliente(this.form);' onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='Ingrese la razon social' >
<br/>
<input type='text' value='$direccion' class='form-control' name='razonSocialDireccion' id='razonSocialDireccion' required style='text-transform:uppercase;'   onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='Ingrese la direccion' >";

?>
