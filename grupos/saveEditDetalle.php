<?php
require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$abreviatura=$_POST['abreviatura'];
$codMaestro=$_POST['cod_maestro'];

$sql_upd=mysql_query("update $tableDetalle set nombre='$nombre', abreviatura='$abreviatura' where codigo='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlListDetalle2?codigo=$codMaestro';
			</script>";
?>