<?php
setcookie("global_almacen", "", time() - 3600);
$global_almacen=$_POST['almacen'];
setcookie("global_almacen",$global_almacen);
echo "<script language='Javascript'>
			alert('El valor fue cambiado con éxito.');
			location.href='cambiar_almacen_trabajo.php';
			</script>";
?>