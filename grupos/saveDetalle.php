<?php
require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

$codMaestro=$_POST['cod_maestro'];

$sql="insert into $tableDetalle (nombre, abreviatura, estado, $campoForaneo) values('$nombre','$abreviatura','1', '$cod_maestro')";
//echo $sql;
$sql_inserta=mysql_query($sql);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='$urlListDetalle2?codigo=$codMaestro';
			</script>";

?>