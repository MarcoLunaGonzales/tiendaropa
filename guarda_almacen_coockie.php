<?php
$variable=$_POST['almacen'];
setcookie("global_almacen",$variable);
echo "<script language='Javascript'>
			alert('El valor fue cambiado con éxito.');
			location.href='inicio_almacenes.php';
			</script>";
?>