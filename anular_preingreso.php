<?php
require("conexionmysqli.php");

$sql="update preingreso_almacenes set ingreso_anulado=1 where cod_ingreso_almacen='".$_GET["codigo_registro"]."'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_preingreso.php';			
			</script>";

?>