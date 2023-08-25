<?php
$estilosVenta=1;
require("conexionmysqli.php");
$cliente=$_GET['cliente'];

$nombre="";
$direccion="";

$sql="select f.nombre_factura, f.dir_cliente from clientes f 
	where f.cod_cliente='$cliente'";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
	$direccion=$dat[1];
}

if($cliente==146){
	$nombre="SN";
	$direccion="";
}
echo "<input type='text' value='$nombre' class='form-control' name='razonSocial' id='razonSocial' required style='text-transform:uppercase;'  onchange='ajaxNitCliente(this.form);' onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='Ingrese la razon social' pattern='[A-Z a-z 0-9 Ññ.-&]+'><br/>
<input type='text' value='$direccion' class='form-control' name='razonSocialDireccion' id='razonSocialDireccion' required style='text-transform:uppercase;'   onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='Ingrese la direccion' >";

?>
